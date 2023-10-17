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
?><!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>">
<head>
<script src="/js/libs.min.js?<?=date('His')?>"></script>
<script src="/js/control.js?<?=date('His')?>"></script>
	<?php //$APPLICATION->IncludeFile(SITE_DIR."include/template/head_start.php",array(),array("MODE"=>"html"))

$asset = Asset::getInstance();
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
$asset->addCss(SITE_TEMPLATE_PATH.'/assets/vendor/fancybox/jquery.fancybox.css');
$asset->addJs('/js/banner.js');
$asset->addCss('/css/main.css');
$asset->addCss('/css/fix.css');
$asset->addCss('/css/slick.css');
$asset->addCss('/css/banner.css');
	?>
   	<?$APPLICATION->ShowHead();?>
   	<title><?php $APPLICATION->ShowTitle(); if ($curPage != SITE_DIR.'index.php' && $arSiteData['SITE_NAME'] != '') {echo ' | '. $arSiteData['SITE_NAME'];}?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">


</head>
<body>
	<?$APPLICATION->IncludeComponent("progress365:realtyfeed","",Array());?>
<div id="panel"><?php $APPLICATION->ShowPanel(); ?></div>