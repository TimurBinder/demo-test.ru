<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;

$APPLICATION->SetPageProperty('hide_sidebar', "Y");
$APPLICATION->SetPageProperty('wide_page', "Y");

$APPLICATION->AddViewContent('breadcrumb-center', ' breadcrumb-center');

$asset = Asset::getInstance();

$asset->addString('<script src="https://yastatic.net/share2/share.js" async="async" charset="utf-8"></script>');
$asset->addJs(SITE_TEMPLATE_PATH.'/components/bitrix/news.list/reviews/script.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/components/bitrix/catalog.section/master/script.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/components/bitrix/catalog.item/master/script.js');
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/waypoints/jquery.waypoints.min.js');
$asset->addCss(SITE_TEMPLATE_PATH.'/components/bitrix/news.list/reviews/style.css');
$asset->addCss(SITE_TEMPLATE_PATH.'/components/bitrix/news.list/files/style.css');
$asset->addCss(SITE_TEMPLATE_PATH.'/components/bitrix/main.include/ask_question/style.css');

if ($arParams["LANDING_DETAIL_PAGE"] == "Y") {

    $rsPath = \CIBlockSection::GetNavChain(
        $arParams["LANDING_ELEMENT_IBLOCK_ID"],
        $templateData["ELEMENT"]["IBLOCK_SECTION_ID"],
        array(
        'ID', 'NAME', 'SECTION_PAGE_URL'
        )
    );
    while ($path = $rsPath->GetNext()) {
        $APPLICATION->AddChainItem($path['NAME'], $path['~SECTION_PAGE_URL']);
    }
    $APPLICATION->AddChainItem($templateData["ELEMENT"]['NAME']);

}

global $USER;

if ($USER->IsAdmin()):?>
    <div class="landing_setting_inner">
    </div>
    <div class="landing__crop_settings">
        <div class="landing__setting" data-iblocktype="<?=$templateData["IBLOCK_TYPE_ID"]?>" data-iblockid="<?=$templateData["IBLOCK_ID"]?>">
            <a href="" class="landing__setting__link" target="_blank">
                <svg class="landing__setting__svg icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-settings"></use></svg>
            </a>
        </div>
    </div>
<?php endif;
