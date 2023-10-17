<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

use \Bitrix\Main\Loader;

if ($arParams['USE_OWL']) {
    $arParams['OWL_PARAMS'] = array(
        'items' => 4,
        'responsive' => array(
            '0' => array('items' => '1'),
            '480' => array('items' => $arParams['OWL_PHONE']),
            '769' => array('items' => $arParams['OWL_TABLET']),
            '996' => array('items' => $arParams['OWL_PC']),
        ),
        'autoplay' => $arParams['OWL_AUTOPLAY'] == 'Y',
        'autoplaySpeed' => $arParams['OWL_CHANGE_SPEED'],
        'smartSpeed' => $arParams['OWL_CHANGE_SPEED'],
        'autoplayTimeout' => $arParams['OWL_CHANGE_DELAY'],
        'dots' => false,
        'nav' => true,
        'margin' => 20,
        'navContainer' => '.b-newslist__nav',
        'navText' => array(
            '<svg class="icon-svg icon-svg-chevron-left"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-chevron-left"></use></svg>',
            '<svg class="icon-svg icon-svg-chevron-right"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-chevron-right"></use></svg>'
        )
    );
}

if (intval($arParams['PARENT_SECTION']) > 0) {

    if (is_array($arResult['SECTION']['PATH']) && count($arResult['SECTION']['PATH']) > 0) {
        foreach ($arResult['SECTION']['PATH'] as $iSectionKey => $arSection) {
            if ($arParams['PARENT_SECTION'] == $arSection['ID']) {
                $arResult['PARENT_SECTION'] = $arSection;
            }
        }
        unset($arSection);
    }
}

if (isset($arResult['PARENT_SECTION'])) {
    $this->__component->arResult['PARENT_SECTION'] = $arResult['PARENT_SECTION'];
    $this->__component->SetResultCacheKeys(array('PARENT_SECTION'));
}
