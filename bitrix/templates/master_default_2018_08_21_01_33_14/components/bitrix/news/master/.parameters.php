<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\ParametersUtils;

foreach (array('iblock', 'redsign.master', 'redsign.devfunc') as $module) {
    if (!Loader::includeModule($module)) {
        return;
    }
}

$arAllPropListLand = array();

if (isset($arCurrentValues['IBLOCK_ID_LANDING']) && (int)$arCurrentValues['IBLOCK_ID_LANDING'] > 0) {
    AddMessage2Log($arCurrentValues);
	$rsPropsLand = CIBlockProperty::GetList(
		array('SORT' => 'ASC', 'ID' => 'ASC'),
		array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID_LANDING'], 'ACTIVE' => 'Y')
	);
	while ($arProp = $rsPropsLand->Fetch()) {
		$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
		if ('' == $arProp['CODE']) {
			$arProp['CODE'] = $arProp['ID'];
		}

		$arAllPropListLand[$arProp['CODE']] = $strPropName;
	}
    AddMessage2Log($arAllPropListLand);
}

$rsIblock = CIBlock::GetList(array('SORT' => 'ASC'), false);
while ($arr = $rsIblock->Fetch())
	$arIBlockLand[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];

Loc::loadMessages(__FILE__);

$defaultValue = array('-' => Loc::getMessage('RS.MASTER.UNDEFINED'));

$arIblockViewMode = array(
    '-' => Loc::getMessage('RS.MASTER.UNDEFINED'),
    'VIEW_SECTIONS' => Loc::getMessage('RS.MASTER.IBLOCK_VIEW_MODE.SECTIONS'),
    'VIEW_ELEMENTS' => Loc::getMessage('RS.MASTER.IBLOCK_VIEW_MODE.ELEMENTS')
);

$arNLTemplates = ParametersUtils::getComponentTemplateList('bitrix:news.list');
$arNDTemplates = ParametersUtils::getComponentTemplateList('bitrix:news.detail');
$arCSTTemplates = ParametersUtils::getComponentTemplateList('bitrix:catalog.sections.top');

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);

$isUsualList = $arCurrentValues['LIST_TEMPLATE'] == 'list' || $arCurrentValues['LIST_TEMPLATE'] == 'sale_promotions';
$isSalePromotion = $arCurrentValues['LIST_TEMPLATE'] == 'sale_promotions' || $arCurrentValues['DETAIL_TEMPLATE'] == 'sale_promotion';
$isPartners = $arCurrentValues['SECTIONS_TEMPLATE'] == 'partners' || $arCurrentValues['LIST_TEMPLATE'] == 'partners';// || $arCurrentValues['DETAIL_TEMPLATE'] == 'partners';
$isStaff = $arCurrentValues['SECTIONS_TEMPLATE'] == 'staff' || $arCurrentValues['LIST_TEMPLATE'] == 'staff';// || $arCurrentValues['DETAIL_TEMPLATE'] == 'partners';


$arTemplateParameters = array(
	// "DISPLAY_DATE" => Array(
		// "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_DATE"),
		// "TYPE" => "CHECKBOX",
		// "DEFAULT" => "Y",
	// ),
	"DISPLAY_PICTURE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_PICTURE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PREVIEW_TEXT" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_TEXT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	// "USE_SHARE" => Array(
		// "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_USE_SHARE"),
		// "TYPE" => "CHECKBOX",
		// "MULTIPLE" => "N",
		// "VALUE" => "Y",
		// "DEFAULT" =>"N",
		// "REFRESH"=> "Y",
	// ),
    'IBLOCK_VIEW_MODE' => array(
        'PARENT' => 'SECTIONS_SETTINGS',
        'NAME' => getMessage('RS.MASTER.IBLOCK_VIEW_MODE'),
        'TYPE' => 'LIST',
        'VALUES' => $arIblockViewMode,
        'MULTIPLE' => 'N',
        'DEFAULT' => 'VIEW_ELEMENTS',
        'REFRESH' => 'Y',
    ),
);

if ($arCurrentValues['IBLOCK_VIEW_MODE'] == 'VIEW_SECTIONS') {

    $arTemplateParameters['SECTIONS_TEMPLATE'] = array(
        'PARENT' => 'SECTIONS_SETTINGS',
        'NAME' => Loc::getMessage('RS.MASTER.SECTIONS_TEMPLATE'),
        'TYPE' => 'LIST',
        'VALUES' => $arCSTTemplates,
        'DEFAULT' => '',
        'REFRESH' => 'Y',
    );
}

$arTemplateParameters['ADD_PICT_PROP'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('RS.MASTER.ADD_PICT_PROP'),
    'TYPE' => 'LIST',
    'MULTIPLE' => 'N',
    'ADDITIONAL_VALUES' => 'N',
    'REFRESH' => 'N',
    'DEFAULT' => '-',
    'VALUES' => array_merge($defaultValue, $listProp['F']),
);

$arTemplateParameters['USE_ARCHIVE'] = array(
  'TYPE' => 'CHECKBOX',
  'NAME' => Loc::getMessage('RS.USE_ARCHIVE'),
  'PARENT' => 'LIST_SETTINGS',
  'DEFAULT' => 'N',
  'REFRESH' => 'Y'
);
if ($arCurrentValues['USE_ARCHIVE'] == 'Y') {
    $arTemplateParameters["ARCHIVE_URL"] = array(
    		"PARENT" => "SEF_MODE",
    		"NAME" => Loc::getMessage("RS.ARCHIVE_URL"),
    		"TYPE" => "STRING",
    		"DEFAULT" => "archive/",
    );
}

$arTemplateParameters['DETAIL_IS_USE_CUSTOM_SIDEBAR'] = array(
    'NAME' => Loc::getMessage('RS.DETAIL_IS_USE_CUSTOM_SIDEBAR'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y',
    'PARENT' => 'DETAIL_SETTINGS'
);
if ($arCurrentValues['DETAIL_IS_USE_CUSTOM_SIDEBAR'] == 'Y') {
    $arTemplateParameters['DETAIL_CUSTOM_SIDEBAR_PATH'] = array(
        'NAME' => Loc::getMessage('RS.DETAIL_CUSTOM_SIDEBAR_PATH'),
        'TYPE' => 'STRING',
        'DEFAULT' => '/include/sidebar/news_detail_sidebar.php',
        'PARENT' => 'DETAIL_SETTINGS'
    );
}


$arTemplateParameters['DETAIL_BACK_USE_SHARE'] = array(
    'NAME' => Loc::getMessage('RS.DETAIL_BACK_USE_SHARE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
    'REFRESH' => 'Y',
    'PARENT' => 'DETAIL_SETTINGS'
);
if ($arCurrentValues['DETAIL_BACK_USE_SHARE'] == 'Y') {
    $arTemplateParameters['DETAIL_SOCIAL_SERVICES'] = array(
        'NAME' => Loc::getMessage('RS.DETAIL_SOCIAL_SERVICES'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'vkontakte,facebook,odnoklassniki,twitter',
        'PARENT' => 'DETAIL_SETTINGS'
    );
}

$arTemplateParameters['USE_VK_COMMENTS'] = array(
    'NAME' => Loc::getMessage('RS.USE_VK_COMMENTS'),
    'TYPE' => 'CHECKBOX',
    'DEFUALT' => 'Y',
    'REFRESH' => 'Y',
    'PARENT' => 'DETAIL_SETTINGS'
);
if ($arCurrentValues['USE_VK_COMMENTS'] == 'Y') {
    $arTemplateParameters['VK_API_ID'] = array(
        'NAME' => Loc::getMessage('RS.VK_API_ID'),
        'TYPE' => 'STRING',
        'DEFAULT' => '',
        'PARENT' => 'DETAIL_SETTINGS'
    );
    $arTemplateParameters['VK_LIMIT'] = array(
        'NAME' => Loc::getMessage('RS.VK_LIMIT'),
        'TYPE' => 'STRING',
        'DEFAULT' => '',
        'PARENT' => 'DETAIL_SETTINGS'
    );
}

$arTemplateParameters['LIST_TEMPLATE'] = array(
    'NAME' => Loc::getMessage('RS.LIST_TEMPLATES'),
    'TYPE' => 'LIST',
    'VALUES' => $arNLTemplates,
    'DEFAULT' => '',
    'REFRESH' => 'Y',
    'PARENT' => 'LIST_SETTINGS',
);

if ($isUsualList || $isPartners || $isStaff) {
  ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('owlSupport'));
  if ($arCurrentValues['USE_OWL'] == 'Y') {
      $arTemplateParameters['OWL_AUTOPLAY'] = array(
          'NAME' => Loc::getMessage('RS.OWL_AUTOPLAY'),
          'TYPE' => 'CHECKBOX',
          'VALUE' => 'Y',
          'DEFAULT' => 'N'
      );
      ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('owlSettings'));
  } else {
      ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('bootstrapCols'));
  }
}

$arTemplateParameters['DETAIL_TEMPLATE'] = array(
    'NAME' => Loc::getMessage('RS.DETAIL_TEMPLATES'),
    'TYPE' => 'LIST',
    'VALUES' => $arNDTemplates,
    'DEFAULT' => '',
    'REFRESH' => 'Y',
    'PARENT' => 'DETAIL_SETTINGS',
);

if ($arCurrentValues['DETAIL_TEMPLATE'] == 'master' || $arCurrentValues['DETAIL_TEMPLATE'] == 'sale_promotion') {
    $detailLinkedPropsIterator = \Bitrix\Iblock\PropertyTable::getList(array(
        'filter' => array(
            '=IBLOCK_ID' => $arCurrentValues["IBLOCK_ID"],
            '=CODE' => $arCurrentValues["DETAIL_PROPERTY_CODE"],
            '=PROPERTY_TYPE' => 'E'
        )
    ));

    $arDetailLinkedProps = $detailLinkedPropsIterator->fetchAll();

    $arTemplateParameters['PATH_TO_BLOCKS_AREA'] = array(
        'NAME' => Loc::getMessage('RS.PATH_TO_BLOCKS_AREA'),
        'TYPE' => 'STRING',
        'DEFAULT' => '/include/template/news/news_detail_blocks.php',
        'PARENT' => 'DETAIL_SETTINGS',
    );

    foreach ($arDetailLinkedProps as $arProp) {
        $arTemplateParameters['DETAIL_PATH_TO_'.$arProp['CODE'].'_AREA'] = array(
          'NAME' => Loc::getMessage('RS.PATH_TO_PROP_BLOCKS_AREA', array('#PROP_NAME#' => $arProp['NAME'])),
          'TYPE' => 'STRING',
          'DEFAULT' => '/include/template/news/catalog_items.php',
          'PARENT' => 'DETAIL_SETTINGS',
        );
    }
}

if (!$isPartners && !$isStaff && !$isSalePromotion) {
    $arTemplateParameters['DETAIL_SHOW_PREVIEW_TEXT'] = array(
        'NAME' => Loc::getMessage('RS.DETAIL_SHOW_PREVIEW_TEXT'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'PARENT' => 'DETAIL_SETTINGS',
    );

    $arTemplateParameters['DETAIL_SHOW_READING_TIME'] = array(
        'NAME' => Loc::getMessage('RS.DETAIL_SHOW_READING_TIME'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'PARENT' => 'DETAIL_SETTINGS',
    );
}

if ($isSalePromotion) {
    $arTemplateParameters['MARKER_TEXT_PROPERTY'] = array(
        'NAME' => Loc::getMessage('RS.MARKER_TEXT_PROPERTY'),
        'TYPE' => 'LIST',
        'VALUES' => array_merge($defaultValue, $listProp['SNL']),
    );
    $arTemplateParameters['MARKER_COLOR_PROPERTY'] = array(
        'NAME' => Loc::getMessage('RS.MARKER_COLOR_PROPERTY'),
        'TYPE' => 'LIST',
        'VALUES' => array_merge($defaultValue, $listProp['SNL']),
    );
    $arTemplateParameters['SALE_DATE_PROPERTY'] = array(
        'NAME' => Loc::getMessage('RS.SALE_DATE_PROPERTY'),
        'TYPE' => 'LIST',
        'VALUES' => array_merge($defaultValue, $listProp['SNL']),
    );
}

if ($isPartners || $isStaff) {

    $arTemplateParameters['SHOW_TITLE'] = array(
      'PARENT' => 'VISUAL',
      'TYPE' => 'CHECKBOX',
      'DEFAULT' => 'Y',
      'NAME' => Loc::getMessage('RS.MASTER.SHOW_TITLE')
    );

    $arTemplateParameters['SHOW_DESCRIPTION'] = array(
      'PARENT' => 'VISUAL',
      'TYPE' => 'CHECKBOX',
      'DEFAULT' => 'Y',
      'NAME' => Loc::getMessage('RS.MASTER.SHOW_DESCRIPTION')
    );

}
if ($isPartners) {

    $arTemplateParameters['SITE_URL_PROP'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.MASTER.SITE_URL_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => array_merge($defaultValue, $listProp['SNL']),
    );

    $arTemplateParameters['SITE_DOMAIN_PROP'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.MASTER.SITE_DOMAIN_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => array_merge($defaultValue, $listProp['SNL']),
    );

    $arTemplateParameters['COMPANY_PHONE_PROP'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.MASTER.COMPANY_PHONE_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => array_merge($defaultValue, $listProp['SNL']),
    );
}

if ($isStaff) {
/*
    $arTemplateParameters['SECTION_PAGE_URL'] = array(
      'PARENT' => 'VISUAL',
      'TYPE' => 'STRING',
      'NAME' => Loc::getMessage('RS.SECTION_PAGE_URL')
    );
*/
    $arTemplateParameters['PROP_NAME'] = array(
        'NAME' => Loc::getMessage('RS.PROP_NAME'),
        'TYPE' => 'LIST',
        'VALUES' => $listProp['SNL'],
    );

    $arTemplateParameters['PROP_POSITION'] = array(
        'NAME' => Loc::getMessage('RS.PROP_POSITION'),
        'TYPE' => 'LIST',
        'VALUES' => $listProp['SNL'],
    );

    $arTemplateParameters['PROP_DESCRIPTION'] = array(
        'NAME' => Loc::getMessage('RS.PROP_DESCRIPTION'),
        'TYPE' => 'LIST',
        'VALUES' => $listProp['SNL'],
    );

    $arTemplateParameters['PROP_CONTACTS'] = array(
        'NAME' => Loc::getMessage('RS.PROP_CONTACTS'),
        'TYPE' => 'LIST',
        'VALUES' => $listProp['SNL'],
    );

    $arTemplateParameters['PROP_SOCIAL'] = array(
        'NAME' => Loc::getMessage('RS.PROP_SOCIAL'),
        'TYPE' => 'LIST',
        'VALUES' => $listProp['SNL'],
    );

    $arTemplateParameters['PROP_IS_ASK'] = array(
        'NAME' => Loc::getMessage('RS.PROP_IS_ASK'),
        'TYPE' => 'LIST',
        'VALUES' => $listProp['SNL'],
    );

    $arTemplateParameters['ASK_LINK'] = array(
        'NAME' => Loc::getMessage('RS.ASK_LINK'),
        'TYPE' => 'STRING',
        'DEFAULT' => '/include/forms/ask_staff/?element_id=#ELEMENT_ID#',
    );
}

$arTemplateParameters['IBLOCK_ID_LANDING'] = array(
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => Loc::getMessage('RS.MASTER.IBLOCK_ID_LANDING'),
	'TYPE' => 'LIST',
	'VALUES' => $arIBlockLand,
    'DEFAULT' => '-',
    "REFRESH" => 'Y',
);

if (!empty($arAllPropListLand)) {

	$arTemplateParameters['PROPERTY_LANDING'] = array(
	    'PARENT' => 'DETAIL_SETTINGS',
	    'NAME' => Loc::getMessage('RS.MASTER.PROPERTY_LANDING'),
		'TYPE' => 'LIST',
		'VALUES' => $arAllPropListLand,
	    'DEFAULT' => '-',
	    'MULTIPLE' => 'Y',
	);

	$arTemplateParameters['PROPERTY_LANDING_LINK'] = array(
	    'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => Loc::getMessage('RS.MASTER.LANDING_LINK'),
		'TYPE' => 'LIST',
		'VALUES' => $defaultValue + $listProp["ALL"],
		'DEFAULT' => 'LANDING_LINK',
	);

}
