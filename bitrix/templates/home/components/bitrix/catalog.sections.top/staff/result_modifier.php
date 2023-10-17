<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

$arParams['HIDE_LINK_WHEN_NO_DETAIL'] = $arParams['HIDE_LINK_WHEN_NO_DETAIL'] == 'Y';
$arParams['SET_TITLE'] = $arParams['SET_TITLE'] != 'N';
$arParams['ADD_SECTIONS_CHAIN'] = $arParams['ADD_SECTIONS_CHAIN'] != 'N'; //Turn on by default

$arParams['SECTION_ID'] = intval($arParams['SECTION_ID']);

if (is_array($arResult['SECTIONS']) && count($arResult['SECTIONS']) > 0) {
    foreach (array_reverse($arResult['SECTIONS'], true) as $iSectionKey => $arSection) {
        
        if ($arSection['ID'] == $arParams['SECTION_ID']) {
            $arResult['SECTION'] = $arSection;
        }

        if ($arSection['IBLOCK_SECTION_ID'] == $arParams['SECTION_ID']) {
            
            $arResult['PATH'] = array();            
            $iParentId = $arSection['IBLOCK_SECTION_ID'];
            
            foreach (array_reverse($arResult['SECTIONS']) as $arParent) {

                if ($arParent['ID'] == $iParentId) {
                    $arResult['PATH'][] = $arParent;
                    
                    if (isset($arParent['IBLOCK_SECTION_ID'])) {
                        $iParentId = $arParent['IBLOCK_SECTION_ID'];
                    } else {
                        break;
                    }
                }
            }
            unset($arParent);
        }

        if (
            $arParams['SECTION_ID'] > 0 && $arSection['IBLOCK_SECTION_ID'] != $arParams['SECTION_ID']
            || $arParams['SECTION_ID'] == 0 && isset($arSection['IBLOCK_SECTION_ID'])
        ) {
            if (is_array($arSection['ITEMS']) && count($arSection['ITEMS']) > 0) {
                foreach ($arResult['SECTIONS'] as $iParentKey => $arParent) {
                    if ($arParent['ID'] == $arSection['IBLOCK_SECTION_ID']) {
                        $arResult['SECTIONS'][$iParentKey]['ITEMS'] = array_merge(
                            $arParent['ITEMS'],
                            $arSection['ITEMS']
                            
                        );
                        break;
                    }
                }
                unset($arParent);
            }
            unset($arResult['SECTIONS'][$iSectionKey]);
        }
    }
    
    $arResult['SECTIONS'] = array_values($arResult['SECTIONS']);
}

if (is_array($arResult['PATH']) && count($arResult['PATH']) > 0) {
    $arResult['PATH'] = array_reverse($arResult['PATH']);
} else {
    $arResult['PATH'] = array();
}

if (!isset($arResult['SECTION'])) {
    $arOrder = array();
    $arFilter = array(
        'TYPE' => $arParams['IBLOCK_TYPE'],
        'ID' => $arParams['IBLOCK_ID'],
    );
    $bIncCnt = false;
    
    $dbIblock = CIBlock::getList($arOrder, $arFilter, $bIncCnt);
    
    if ($arIblock = $dbIblock->getNext()) {
        $arResult['SECTION']['NAME'] = $arIblock['NAME'];
        $arResult['SECTION']['DESCRIPTION'] = $arIblock['DESCRIPTION'];
    }
    unset($arOrder, $arFilter, $bIncCnt);
}

if (isset($arResult['SECTION'])) {
    $this->__component->arResult['SECTION'] = $arResult['SECTION'];
}

$this->__component->arResult['PATH'] = $arResult['PATH'];

$this->__component->SetResultCacheKeys(array('SECTION', 'PATH'));