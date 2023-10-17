<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 * @var array $itemIds
 * @var array $actualItem
 * @var array $price
 * @var string $labelPositionClass
 * @var string $discountPositionClass
 */

use \Bitrix\Main\Localization\Loc;
?>
<?
$APPLICATION->IncludeComponent(
    'bitrix:catalog.comments',
    'master',
    array(
        'ELEMENT_ID' => $arResult['ID'],
        'ELEMENT_CODE' => '',
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
        'URL_TO_COMMENT' => '',
        'WIDTH' => '',
        'COMMENTS_COUNT' => '5',
        'BLOG_USE' => $arParams['BLOG_USE'],
        'FB_USE' => $arParams['FB_USE'],
        'FB_APP_ID' => $arParams['FB_APP_ID'],
        'VK_USE' => $arParams['VK_USE'],
        'VK_API_ID' => $arParams['VK_API_ID'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
        'BLOG_TITLE' => '',
        'BLOG_URL' => $arParams['BLOG_URL'],
        'PATH_TO_SMILE' => '',
        'EMAIL_NOTIFY' => $arParams['BLOG_EMAIL_NOTIFY'],
        'AJAX_POST' => 'Y',
        'SHOW_SPAM' => 'Y',
        'SHOW_RATING' => 'N',
        'FB_TITLE' => '',
        'FB_USER_ADMIN_ID' => '',
        'FB_COLORSCHEME' => 'light',
        'FB_ORDER_BY' => 'reverse_time',
        'VK_TITLE' => '',
        'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
        'EXTERNAL_TABS' => 'Y',
        'EXTERNAL_TABS_ACTIVE' => $bActiveTab ? 'Y' : 'N',
        'EXTERNAL_TABS_ID' => $itemIds['TABS_ID'],
    ),
    $component,
    array('HIDE_ICONS' => 'Y')
);
?>
