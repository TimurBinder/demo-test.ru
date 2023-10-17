<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arViewModeList = array(/*'LIST',*/ 'LINE', /*'TEXT',*/'TILE', 'BANNER', 'THUMB');

$arDefaultParams = array(
	'SECTIONS_VIEW_MODE' => 'LINE',
	'SHOW_PARENT_NAME' => 'Y',
	'HIDE_SECTION_NAME' => 'N'
);

$arParams = array_merge($arDefaultParams, $arParams);

if ($arParams['BANNER_TYPE'] != '' && $arParams['BANNER_TYPE'] != '-') {
	$arParams['BANNER_TYPE'] = array($arParams['IBLOCK_ID'] => $arParams['BANNER_TYPE']);
} else {
    $arParams['BANNER_TYPE'] = array();
}

if (!isset($arParams['COL_XS']) || (intval($arParams['COL_XS']) < 1  && intval($arParams['COL_MD']) > 12)) {
    $arParams['COL_XS'] = 6;
}
if (!isset($arParams['COL_SM']) || (intval($arParams['COL_SM']) < 1  && intval($arParams['COL_SM']) > 12)) {
    $arParams['COL_SM'] = '';
}
if (!isset($arParams['COL_MD']) || (intval($arParams['COL_MD']) < 1  && intval($arParams['COL_MD']) > 12)) {
    $arParams['COL_MD'] = '';
}
if (!isset($arParams['COL_LG']) || (intval($arParams['COL_LG']) < 1  && intval($arParams['COL_LG']) > 12)) {
    $arParams['COL_LG'] = '';
}

$arParams['PREVIEW_TRUNCATE_LEN'] = intval($arParams['PREVIEW_TRUNCATE_LEN']);

if (!in_array($arParams['SECTIONS_VIEW_MODE'], $arViewModeList))
	$arParams['SECTIONS_VIEW_MODE'] = 'LIST';
if ('N' != $arParams['SHOW_PARENT_NAME'])
	$arParams['SHOW_PARENT_NAME'] = 'Y';
if ('Y' != $arParams['HIDE_SECTION_NAME'])
	$arParams['HIDE_SECTION_NAME'] = 'N';

$arResult['VIEW_MODE_LIST'] = $arViewModeList;

if (0 < $arResult['SECTIONS_COUNT'])
{
	if (
        !in_array($arParams['SECTIONS_VIEW_MODE'], array('LIST', 'LINE')) ||
        $arParams['SECTIONS_VIEW_MODE'] == 'LINE' && 'Y' == $arParams['HIDE_SECTION_NAME']
    ) {
		$boolClear = false;
		$arNewSections = array();
		foreach ($arResult['SECTIONS'] as &$arOneSection)
		{
			if (1 < $arOneSection['RELATIVE_DEPTH_LEVEL'])
			{
				$boolClear = true;
				continue;
			}
			$arNewSections[] = $arOneSection;
		}
		unset($arOneSection);
		if ($boolClear)
		{
			$arResult['SECTIONS'] = $arNewSections;
			$arResult['SECTIONS_COUNT'] = count($arNewSections);
		}
		unset($arNewSections);
	}
}

if (0 < $arResult['SECTIONS_COUNT'])
{
	$boolPicture = false;
	$boolDescr = false;
	$boolBanner = false;
	$arSelect = array('ID');
	$arMap = array();
	if (in_array($arParams['SECTIONS_VIEW_MODE'], array('LINE', 'TILE', 'BANNER', 'THUMB')))
	{
		reset($arResult['SECTIONS']);
		$arCurrent = current($arResult['SECTIONS']);
		if (!isset($arCurrent['PICTURE']))
		{
			$boolPicture = true;
			$arSelect[] = 'PICTURE';
		}
		if ('LINE' == $arParams['SECTIONS_VIEW_MODE'] && !array_key_exists('DESCRIPTION', $arCurrent))
		{
			$boolDescr = true;
			$arSelect[] = 'DESCRIPTION';
			$arSelect[] = 'DESCRIPTION_TYPE';
		}
        
        if (in_array($arParams['SECTIONS_VIEW_MODE'], array('BANNER', 'THUMB')) && isset($arParams['BANNER_TYPE'][$arParams['IBLOCK_ID']])) {
            $boolBanner = true;
            $arSelect[] = 'IBLOCK_ID';
            $arSelect[] = $arParams['BANNER_TYPE'][$arParams['IBLOCK_ID']];
        }
	}

	if ($boolBanner) {
        $arUserFieldValues = array();
        $dbUserField = CUserFieldEnum::GetList(array(), array('USER_FIELD_NAME' => $arParams['BANNER_TYPE']));
        while ($arUserField = $dbUserField->Fetch())
        {
            $arUserFieldValues[$arUserField['ID']] = $arUserField;
        }
    }
   
	if ($boolPicture || $boolDescr || $boolBanner)
	{
		foreach ($arResult['SECTIONS'] as $key => $arSection)
		{
			$arMap[$arSection['ID']] = $key;
		}
        unset($key, $arSection);
        
		$rsSections = CIBlockSection::GetList(array(), array('ID' => array_keys($arMap), 'IBLOCK_ID' => $arParams['IBLOCK_ID']), false, $arSelect);
		while ($arSection = $rsSections->GetNext())
		{
			if (!isset($arMap[$arSection['ID']]))
				continue;
			$key = $arMap[$arSection['ID']];
			if ($boolPicture)
			{
				$arSection['PICTURE'] = intval($arSection['PICTURE']);
				$arSection['PICTURE'] = (0 < $arSection['PICTURE'] ? CFile::GetFileArray($arSection['PICTURE']) : false);
				$arResult['SECTIONS'][$key]['PICTURE'] = $arSection['PICTURE'];
				$arResult['SECTIONS'][$key]['~PICTURE'] = $arSection['~PICTURE'];
			}
			if ($boolDescr)
			{
                $arResult['SECTIONS'][$key]['DESCRIPTION'] = $arSection['DESCRIPTION'];
				$arResult['SECTIONS'][$key]['DESCRIPTION_TYPE'] = $arSection['DESCRIPTION_TYPE'];
			}

			if ($boolBanner) {
                if ($arSection[$arParams['BANNER_TYPE'][$arSection['IBLOCK_ID']]]) {
                    $arResult['SECTIONS'][$key][$arParams['BANNER_TYPE'][$arSection['IBLOCK_ID']]] = $arUserFieldValues[$arSection[$arParams['BANNER_TYPE'][$arSection['IBLOCK_ID']]]];
                }
            }
            
		}
        unset($arSection);
	}
}

if($arParams['PREVIEW_TRUNCATE_LEN'] > 0) {
    $obParser = new CTextParser;
    foreach ($arResult['SECTIONS'] as $key => $arSection) {
        $arResult['SECTIONS'][$key]['DESCRIPTION'] = $obParser->html_cut($arSection['DESCRIPTION'], $arParams['PREVIEW_TRUNCATE_LEN']);
    }
    unset($key, $arSection);
}


if (
    ('Y' == $arParams['SHOW_PARENT_NAME'] || 'Y' == $arParams['SHOW_PARENT_DESCR'])
    && $arResult['SECTION']['ID'] == 0
) {

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
