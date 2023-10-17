<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\Page\Asset;
use \Bitrix\Main\Config\Option;
use \Redsign\Master;
use \Redsign\Master\SVGIconsManager;
use \Redsign\Master\MyTemplate;

$minModuleMainVersion = '17.0.13';
if (CheckVersion($minModuleMainVersion, SM_VERSION)) {
    ShowError(Loc::getMessage('RS.MASTER.ERROR_MAIN_MIN_VERSION', array("#MIN_MODULE_MAIN_VERSION#" => $minModuleMainVersion, "#CURRENT_MODULE_MAIN_VERSION#" => SM_VERSION)));
    die();
}

Loc::loadMessages(__FILE__);
if (!Loader::includeModule('redsign.master')) {
    ShowError(Loc::getMessage('RS.MASTER.ERROR_NOT_INSTALLED'));
    die();
} elseif (!Loader::includeModule('redsign.devfunc')) {
    ShowError(Loc::getMessage('RS.MASTER.ERROR_DEVFUNC_NOT_INSTALLED'));
    die();
} elseif (!Loader::includeModule('redsign.tuning')) {
    ShowError(Loc::getMessage('RS.MASTER.ERROR_TUNING_NOT_INSTALLED'));
    die();
}

$documentRoot = Application::getDocumentRoot();
$request = Application::getInstance()->getContext()->getRequest();
$curPage = $APPLICATION->GetCurPage(true);

$tuning = \Redsign\Tuning\TuningCore::getInstance();
$instanceOptionManager = $tuning->getInstanceOptionMananger();
$isFlyingHead = $instanceOptionManager->get('FLYING_HEAD') == 'Y';
$fontFamily = $instanceOptionManager->get('FONT_FAMILY');
$fontSize = $instanceOptionManager->get('FONT_SIZE');
$containerWidth = $instanceOptionManager->get('CONTAINER_WIDTH');
$template = $instanceOptionManager->get('TEMPLATE');
if (empty($template)) {
    $template = Option::get('redsign.master', 'head_type', '', SITE_ID);
}
$menuView = $instanceOptionManager->get('MAIN_MENU_COLOR_SCHEME');

$master = MyTemplate::getInstance();
$master->setHeadType($template);
$master->setView($menuView);

// get site data
$cacheTime = 86400;
$cacheId = 'CSiteGetByID'.SITE_ID;
$cacheDir = '/siteData/'.SITE_ID.'/';

$cache = Bitrix\Main\Data\Cache::createInstance();
if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
    $arSiteData = $cache->getVars();
} elseif ($cache->startDataCache()) {

    $arSiteData = array();

    $rsSites = CSite::GetByID(SITE_ID);
    if ($arSite = $rsSites->Fetch()) {
        $arSiteData['SITE_NAME'] = $arSite['SITE_NAME'];
    }

    if (empty($arSiteData)) {
        $cache->abortDataCache();
    }

    $cache->endDataCache($arSiteData);
}

// svg icons
SVGIconsManager::addPath(SITE_TEMPLATE_PATH.'/assets/images/icons/svg');

// cart
$isUseCart = false;
if(Loader::includeModule('redsign.lightbasket')) {
    //TODO: add in option.php
    $isUseCart = Option::get('redsign.master', 'is_use_cart') == 'Y' ? true : false;
}


/* JS options */
$arJsOptions = array(
    'SITE_ID' => SITE_ID,
    'CRM_FORM_SCRIPT' => Option::get('redsign.master', 'b24_crm_form_script')
);

/* /JS options */

/* Add assets */
$asset = Asset::getInstance();
$asset->addString('<link href="'.CHTTP::URN2URI('/favicon.ico').'" rel="shortcut icon"  type="image/x-icon">');
$asset->addString('<meta http-equiv="X-UA-Compatible" content="IE=edge">');
$asset->addString('<meta name="viewport" content="width=device-width, initial-scale=1">');
$asset->addString('<script data-skip-moving="true">
    (function () {
        window.RS = window.RS || {};
        window.RS.options = '.CUtil::PhpToJSObject($arJsOptions).'
    })();
</script>', true);

CJSCore::Init(array('ajax', 'ls'));
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/jquery/jquery-3.2.1.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/bootstrap/bootstrap.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/bootstrap/validator.bootstrap.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/owl.carousel/owl.carousel.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/fancybox/jquery.fancybox.min.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/inputmask/jquery.inputmask.bundle.js');

$asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/load_more.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/slider.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/app.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/main.js');

if (ModuleManager::isModuleInstalled('catalog') && ModuleManager::isModuleInstalled('sale')) {
    $asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/basket.js');
} else if ($isUseCart) {
    CJSCore::Init('rs_lightbasket');
}

$asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/b24_crm_form.js');;

$asset->addCss(SITE_TEMPLATE_PATH.'/assets/vendor/fancybox/jquery.fancybox.css');
$asset->addCss(SITE_TEMPLATE_PATH.'/assets/css/common.css');

if ($fontFamily == 'ff_pt_sans') {
    $asset->addString('<link href="https://fonts.googleapis.com/css?family=PT+Sans|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">');
} elseif ($fontFamily == 'ff_roboto') {
    $asset->addString('<link href="https://fonts.googleapis.com/css?family=Roboto|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">');
} else {
    $asset->addString('<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext" rel="stylesheet">');
}

$asset->addCss(SITE_TEMPLATE_PATH.'/assets/css/custom.css', true);
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/js/custom.js', true);
$asset->addCss(SITE_DIR.'assets/css/custom.css', true);
$asset->addJs(SITE_DIR.'assets/js/custom.js', true);

$asset->addString('<!--[if lte IE 8]><script src="'.SITE_TEMPLATE_PATH.'/assets/vendor/html5shiv.min.js" async="async" data-skip-moving="true"></script><![endif]-->');
$asset->addString('<!--[if lte IE 8]><script src="'.SITE_TEMPLATE_PATH.'/assets/vendor/respond.min.js" async="async" data-skip-moving="true"></script><![endif]-->');

$isHomePage = Master\PageUtils::isHome();
?><!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
    <?php $APPLICATION->IncludeFile(SITE_DIR."include/template/head_start.php",array(),array("MODE"=>"html"))?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?$APPLICATION->ShowHead();?>
    <title>
    <?php
    $APPLICATION->ShowTitle();
    if (
        $curPage != SITE_DIR.'index.php' &&
        $arSiteData['SITE_NAME'] != ''
    ) {
        echo ' | '. $arSiteData['SITE_NAME'];
    }
    ?>
    </title>
    <script data-skip-moving="true">
        <?php
        $jsMessages = array(
            'CHANGE_ORDER' => Loc::getMessage('RS.JS_CHANGE_ORDER'),
            'YOUR_ORDER' => Loc::getMessage('RS.JS_YOUR_ORDER')
        );
        ?>
        BX.message(<?=CUtil::PhpToJSObject($jsMessages, false)?>);
    </script>
    <?$APPLICATION->IncludeFile(SITE_DIR."include/template/head_end.php",array(),array("MODE"=>"html"))?>
</head>
<body class="<?=$fontFamily?> <?=$fontSize?> <?=$containerWidth?>" <?=$APPLICATION->ShowProperty("backgroundImage")?>>
    <?$APPLICATION->IncludeFile(SITE_DIR."include/template/body_start.php",array(),array("MODE"=>"html"))?>
    <div class="wrapper">
        <div id="panel"><?php $APPLICATION->ShowPanel(); ?></div>
        <div id="svg-icons" style="display: none"></div>
        <?php
        include 'include/headers/'.MyTemplate::getInstance()->getHeadType().'.php';

        if ($isFlyingHead) {
            include 'include/headers/fly.php';
        }
        ?>
        <div class="l-page<?=$APPLICATION->ShowViewContent('has-sidebar')?><?=$APPLICATION->ShowViewContent('breadcrumb-center')?>">
            <div class="l-page__content">
                <?php if (!$isHomePage): ?>
                <div class="l-page__title">
                    <div class="l-page__breadcrumb">
                    <?php
                    $sBreadcrumbPath = $documentRoot.SITE_DIR.'include/template/breadcrumb.php';

                    if (file_exists($sBreadcrumbPath)) {
                        include($sBreadcrumbPath);
                    }
                    ?>
                    </div><h1><?=$APPLICATION->ShowTitle(false, false);?></h1>
                </div>
                <?php endif; ?>
				
			<?php
			if ($request->isAjaxRequest() || $request->get('AJAX_CALL') == 'Y')
			{
                $APPLICATION->restartBuffer();
			}
