<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var array $arCurrentValues */

use Bitrix\Iblock;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Web\Json;
use Bitrix\Main\Localization\Loc;
use \Redsign\Master\ParametersUtils;
use \Redsign\Master\MyTemplate;

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('iblock'))
	return;

$boolCatalog = Loader::includeModule('catalog');
$boolLightBasket = Loader::includeModule('redsign.lightbasket');

CBitrixComponent::includeComponentClass('bitrix:catalog.section');
CBitrixComponent::includeComponentClass('bitrix:catalog.top');
CBitrixComponent::includeComponentClass('bitrix:catalog.element');

$arSKU = false;
$boolSKU = false;
$boolIsCatalog = false;

if ($boolCatalog && (isset($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID']) > 0)
{
	$arSKU = CCatalogSKU::GetInfoByProductIBlock($arCurrentValues['IBLOCK_ID']);
	$boolSKU = !empty($arSKU) && is_array($arSKU);
    
    $arCatalog = CCatalog::GetByIDExt($arCurrentValues['IBLOCK_ID']);
    if (false !== $arCatalog) {
        $boolIsCatalog = true;
    }
}

global $USER_FIELD_MANAGER;

$arIBlock = array();
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), array('ACTIVE' => 'Y'));
while ($arr = $rsIBlock->Fetch()){
	$arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}

$arProperty_UF = array();
$arUserFields = $USER_FIELD_MANAGER->GetUserFields('IBLOCK_'.$arCurrentValues['IBLOCK_ID'].'_SECTION', 0, LANGUAGE_ID);
foreach($arUserFields as $FIELD_NAME=>$arUserField)
{
	$arUserField['LIST_COLUMN_LABEL'] = (string)$arUserField['LIST_COLUMN_LABEL'];
	$arProperty_UF[$FIELD_NAME] = $arUserField['LIST_COLUMN_LABEL'] ? '['.$FIELD_NAME.'] '.$arUserField['LIST_COLUMN_LABEL'] : $FIELD_NAME;
}

$defaultValue = array('-' => GetMessage('CP_BC_TPL_PROP_EMPTY'));

$documentRoot = Loader::getDocumentRoot();
$arFilterViewModeList = array(
	"VERTICAL" => GetMessage("CPT_BC_FILTER_VIEW_MODE_VERTICAL"),
	"HORIZONTAL" => GetMessage("CPT_BC_FILTER_VIEW_MODE_HORIZONTAL")
);
$arCatalogViewMode = array(
	'-' => getMessage('CP_BC_TPL_PROP_EMPTY'),
	'VIEW_SECTIONS' => getMessage('RS.MASTER.CATALOG_VIEW_MODE.SECTIONS'),
	'VIEW_ELEMENTS' => getMessage('RS.MASTER.CATALOG_VIEW_MODE.ELEMENTS')
);

$arSectionDescrValues = array(
	'-' => getMessage('CP_BC_TPL_PROP_EMPTY'),
	'top' => getMessage('RS.MASTER.SHOW_SECTION_DESCRIPTION_TOP'),
	'bottom' => getMessage('RS.MASTER.SHOW_SECTION_DESCRIPTION_BOTTOM'),
);

$arCSTemplates = $arCETemplates = $arRCSTemplates = array();

if (Loader::includeModule('redsign.master')) {
	$arCSTemplates = ParametersUtils::getComponentTemplateList('bitrix:catalog.section');
	$arCETemplates = ParametersUtils::getComponentTemplateList('bitrix:catalog.element');
	$arRCSTemplates = ParametersUtils::getComponentTemplateList('redsign:catalog.sorter');
}

$addToBasketActions = array(
    'BUY' => GetMessage('ADD_TO_BASKET_ACTION_BUY'),
    'ADD' => GetMessage('ADD_TO_BASKET_ACTION_ADD'),
    'ASK' => GetMessage('RS.MASTER.ASK_QUESTION'),
);

$arListProductBlocks = array(
    'price' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_PRICE'),
    // 'quantityLimit' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_QUANTITY_LIMIT'),
    'quantity' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_QUANTITY'),
    'buttons' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_BUTTONS'),
    // 'props' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_PROPS'),
    // 'sku' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_SKU'),
    // 'compare' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_COMPARE'),
    //'props' => GetMessage('CP_BCE_TPL_PRODUCT_BLOCK_PROPS'),
    'preview' => GetMessage('RS.MASTER.PRODUCT_BLOCK.PREVIEW'),
);

$arDetailProductInfoBlock = array(
    'props' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_PROPS'),
    'price' => GetMessage('CP_BC_TPL_DETAIL_PRODUCT_BLOCK_PRICE'),
    'buttons' => GetMessage('CP_BC_TPL_DETAIL_PRODUCT_BLOCK_BUTTONS'),
    'preview' => GetMessage('RS.MASTER.PRODUCT_BLOCK.PREVIEW'),
    'deals' => GetMessage('RS.MASTER.DETAIL_PRODUCT_INFO_BLOCK.PRODUCT_DEALS'),
    'cheaper' => GetMessage('RS.MASTER.DETAIL_PRODUCT_INFO_BLOCK.CHEAPER'),
);

if (Loader::includeModule('sale')) {
    $arDetailProductInfoBlock['delivery'] = GetMessage('RS.MASTER.DETAIL_PRODUCT_INFO_BLOCK.DELIVERY_INFO');
}

if (
    isset($arCurrentValues['USE_COMPARE']) && 'Y' == $arCurrentValues['USE_COMPARE']
    || isset($arCurrentValues['USE_FAVORITE']) && 'Y' == $arCurrentValues['USE_FAVORITE']
) {
    $arDetailProductInfoBlock['compare-favorite'] = GetMessage('RS.MASTER.DETAIL_PRODUCT_INFO_BLOCK.COMPARE_FAVORITE');
}

if ($boolIsCatalog) {
    $arDetailProductInfoBlock['sku'] = GetMessage('CP_BC_TPL_DETAIL_PRODUCT_BLOCK_SKU');
    $arDetailProductInfoBlock['quantityLimit'] = GetMessage('CP_BC_TPL_DETAIL_PRODUCT_BLOCK_QUANTITY_LIMIT');
}

if (
    isset($arCurrentValues['ARTNUMBER_PROP']) && $arCurrentValues['ARTNUMBER_PROP'] != '-'
    || isset($arCurrentValues['DETAIL_USE_VOTE_RATING']) && 'Y' == $arCurrentValues['DETAIL_USE_VOTE_RATING']
    || $arCurrentValues['USE_STORE'] == 'Y'
) {
   $arDetailProductInfoBlock['id-rate-stock'] = GetMessage('RS.MASTER.DETAIL_PRODUCT_INFO_BLOCK.ID_RATE_STOCK'); 
}

if (
    isset($arCurrentValues['DETAIL_BRAND_USE']) && 'Y' == $arCurrentValues['DETAIL_BRAND_USE']
    || isset($arCurrentValues['USE_GIFTS_DETAIL']) && $arCurrentValues['USE_GIFTS_DETAIL'] == 'Y'
) {
   $arDetailProductInfoBlock['brand-gift'] = GetMessage('RS.MASTER.DETAIL_PRODUCT_INFO_BLOCK.BRAND_GIFT'); 
}

$arDetailTabs = array (
	'detail' => Loc::getMessage('RS.MASTER.DETAIL_TEXT'),
	'props' => Loc::getMessage('RS.MASTER.DETAIL_PROPERTIES'),
	'comments' => Loc::getMessage('RS.MASTER.DETAIL_COMMENTS'),
	'stock' => Loc::getMessage('RS.MASTER.DETAIL_STOCK'),
);

$arDetailBlockLines = array (
	'detail' => Loc::getMessage('RS.MASTER.DETAIL_TEXT'),
	'props' => Loc::getMessage('RS.MASTER.DETAIL_PROPERTIES'),
	'comments' => Loc::getMessage('RS.MASTER.DETAIL_COMMENTS'),
    'stock' => Loc::getMessage('RS.MASTER.DETAIL_STOCK'),
);

$arTemplateParameters = array(
	"SECTIONS_SHOW_PARENT_NAME" => array(
		"PARENT" => "SECTIONS_SETTINGS",
		"NAME" => GetMessage('CPT_BC_SECTIONS_SHOW_PARENT_NAME'),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y"
	),
	'CATALOG_VIEW_MODE' => array(
		'PARENT' => 'SECTIONS_SETTINGS',
		'NAME' => getMessage('RS.MASTER.CATALOG_VIEW_MODE'),
		'TYPE' => 'LIST',
		'VALUES' => $arCatalogViewMode,
		'MULTIPLE' => 'N',
		'DEFAULT' => 'VIEW_ELEMENTS',
		'REFRESH' => 'Y',
	),
	'SHOW_SECTION_DESCRIPTION' => array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => getMessage('RS.MASTER.SHOW_SECTION_DESCRIPTION'),
		'TYPE' => 'LIST',
		'VALUES' => $arSectionDescrValues,
		'DEFAULT' => '-',
	),
);

/*
if (isset($arCurrentValues['SECTIONS_VIEW_MODE']) && 'TILE' == $arCurrentValues['SECTIONS_VIEW_MODE'])
{
	$arTemplateParameters['SECTIONS_HIDE_SECTION_NAME'] = array(
		'PARENT' => 'SECTIONS_SETTINGS',
		'NAME' => GetMessage('CPT_BC_SECTIONS_HIDE_SECTION_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N'
	);
}
*/
$arTemplateParameters['SEARCH_PAGE_RESULT_COUNT'] = array(
	'PARENT' => 'SEARCH_SETTINGS',
	'NAME' => GetMessage("CP_BC_TPL_SEARCH_PAGE_RESULT_COUNT"),
	"TYPE" => "STRING",
	"DEFAULT" => "50",
);
$arTemplateParameters['SEARCH_RESTART'] = array(
	'PARENT' => 'SEARCH_SETTINGS',
	'NAME' => GetMessage("CP_BC_TPL_SEARCH_RESTART"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "N",
);
$arTemplateParameters['SEARCH_NO_WORD_LOGIC'] = array(
	'PARENT' => 'SEARCH_SETTINGS',
	'NAME' => GetMessage("CP_BC_TPL_SEARCH_NO_WORD_LOGIC"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
);
$arTemplateParameters['SEARCH_USE_LANGUAGE_GUESS'] = array(
	'PARENT' => 'SEARCH_SETTINGS',
	'NAME' => GetMessage("CP_BC_TPL_SEARCH_USE_LANGUAGE_GUESS"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
);
$arTemplateParameters['SEARCH_CHECK_DATES'] = array(
	'PARENT' => 'SEARCH_SETTINGS',
	'NAME' => GetMessage("CP_BC_TPL_SEARCH_CHECK_DATES"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
);

$arAllPropList = array();
$arAllPropListLand = array();
$arListPropList = array();
$arHighloadPropList = array();
$arFilePropList = $defaultValue;

if (isset($arCurrentValues['IBLOCK_ID_LANDING']) && (int)$arCurrentValues['IBLOCK_ID_LANDING'] > 0) {
	$rsPropsLand = CIBlockProperty::GetList(
		array('SORT' => 'ASC', 'ID' => 'ASC'),
		array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID_LANDING'], 'ACTIVE' => 'Y')
	);
	while ($arProp = $rsPropsLand->Fetch())
	{
		$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
		if ('' == $arProp['CODE'])
		{
			$arProp['CODE'] = $arProp['ID'];
		}

		$arAllPropListLand[$arProp['CODE']] = $strPropName;
	}
}

$rsIblock = CIBlock::GetList(array('SORT' => 'ASC'), false);
while ($arr = $rsIblock->Fetch()) {
	$arIBlockLand[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}

if (isset($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0)
{
	$rsProps = CIBlockProperty::GetList(
		array('SORT' => 'ASC', 'ID' => 'ASC'),
		array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], 'ACTIVE' => 'Y')
	);
	while ($arProp = $rsProps->Fetch())
	{
		$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
		if ('' == $arProp['CODE'])
		{
			$arProp['CODE'] = $arProp['ID'];
		}

		$arAllPropList[$arProp['CODE']] = $strPropName;

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE)
		{
			$arFilePropList[$arProp['CODE']] = $strPropName;
		}

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST)
		{
			$arListPropList[$arProp['CODE']] = $strPropName;
		}

		if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
		{
			$arHighloadPropList[$arProp['CODE']] = $strPropName;
		}
	}

	if (!empty($arCurrentValues['LIST_PROPERTY_CODE']))
	{
		$selected = array();

		foreach ($arCurrentValues['LIST_PROPERTY_CODE'] as $code)
		{
			if (isset($arAllPropList[$code]))
			{
				$selected[$code] = $arAllPropList[$code];
			}
		}

		$arTemplateParameters['LIST_PROPERTY_CODE_MOBILE'] = array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_PROPERTY_CODE_MOBILE'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'VALUES' => $selected
		);
	}
    unset($selected);

	$arTemplateParameters['LIST_PRODUCT_BLOCKS'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => Loc::getMessage('RS.MASTER.LIST_PRODUCT_BLOCKS'),
		'TYPE' => 'LIST',
		'VALUES' => $arListProductBlocks,
		'REFRESH' => 'Y',
		'MULTIPLE' => 'Y',
		'DEFAULT' => '-',
	);

    if (is_array($arCurrentValues['LIST_PRODUCT_BLOCKS']) && count($arCurrentValues['LIST_PRODUCT_BLOCKS']) > 0) {
        $selected = array();
        foreach ($arCurrentValues['LIST_PRODUCT_BLOCKS'] as $name)
        {
            if (isset($arListProductBlocks[$name]))
            {
                $selected[$name] = $arListProductBlocks[$name];
            }
        }

        $arTemplateParameters['LIST_PRODUCT_BLOCKS_ORDER'] = array(
            'PARENT' => 'LIST_SETTINGS',
            'NAME' => GetMessage('CP_BC_TPL_PRODUCT_BLOCKS_ORDER'),
            'TYPE' => 'CUSTOM',
            'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'dragdrop_order'),
            'JS_EVENT' => 'initDraggableOrderControl',
            'JS_DATA' => Json::encode($selected),
            'DEFAULT' => 'preview,props,price,buttons'
        );
        unset($selected);
    }

	$lineElementCount = (int)$arCurrentValues['LINE_ELEMENT_COUNT'] ?: 3;
	$pageElementCount = (int)$arCurrentValues['PAGE_ELEMENT_COUNT'] ?: 30;

	$arTemplateParameters['LIST_PRODUCT_ROW_VARIANTS'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_PRODUCT_ROW_VARIANTS'),
		'TYPE' => 'CUSTOM',
		'BIG_DATA' => 'Y',
		'COUNT_PARAM_NAME' => 'PAGE_ELEMENT_COUNT',
		'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'dragdrop_add'),
		'JS_EVENT' => 'initDraggableAddControl',
		'JS_MESSAGES' => Json::encode(array(
			'variant' => GetMessage('CP_BC_TPL_SETTINGS_VARIANT'),
			'delete' => GetMessage('CP_BC_TPL_SETTINGS_DELETE'),
			'quantity' => GetMessage('CP_BC_TPL_SETTINGS_QUANTITY'),
			'quantityBigData' => GetMessage('CP_BC_TPL_SETTINGS_QUANTITY_BIG_DATA')
		)),
		'JS_DATA' => Json::encode(CatalogSectionComponent::getTemplateVariantsMap()),
		'DEFAULT' => Json::encode(CatalogSectionComponent::predictRowVariants($lineElementCount, $pageElementCount))
	);

	$arTemplateParameters['LIST_ENLARGE_PRODUCT'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_ENLARGE_PRODUCT'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'N',
		'ADDITIONAL_VALUES' => 'N',
		'REFRESH' => 'Y',
		'DEFAULT' => 'N',
		'VALUES' => array(
			'STRICT' => GetMessage('CP_BC_TPL_ENLARGE_PRODUCT_STRICT'),
			'PROP' => GetMessage('CP_BC_TPL_ENLARGE_PRODUCT_PROP')
		)
	);

	if (isset($arCurrentValues['LIST_ENLARGE_PRODUCT']) && $arCurrentValues['LIST_ENLARGE_PRODUCT'] === 'PROP')
	{
		$arTemplateParameters['LIST_ENLARGE_PROP'] = array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_ENLARGE_PROP'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '-',
			'VALUES' => $defaultValue + $arListPropList
		);
	}
/*
	$arTemplateParameters['LIST_SHOW_SLIDER'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_SHOW_SLIDER'),
		'TYPE' => 'CHECKBOX',
		'MULTIPLE' => 'N',
		'REFRESH' => 'Y',
		'DEFAULT' => 'Y'
	);

	if (!isset($arCurrentValues['LIST_SHOW_SLIDER']) || $arCurrentValues['LIST_SHOW_SLIDER'] === 'Y')
	{
		$arTemplateParameters['LIST_SLIDER_INTERVAL'] = array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_SLIDER_INTERVAL'),
			'TYPE' => 'TEXT',
			'MULTIPLE' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '3000'
		);
		$arTemplateParameters['LIST_SLIDER_PROGRESS'] = array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_SLIDER_PROGRESS'),
			'TYPE' => 'CHECKBOX',
			'MULTIPLE' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => 'N'
		);
	}
*/

	$arTemplateParameters['ADD_PICT_PROP'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_ADD_PICT_PROP'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'N',
		'ADDITIONAL_VALUES' => 'N',
		'REFRESH' => 'N',
		'DEFAULT' => '-',
		'VALUES' => $arFilePropList
	);
	$arTemplateParameters['LABEL_PROP'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_LABEL_PROP'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'ADDITIONAL_VALUES' => 'N',
		'REFRESH' => 'Y',
		'VALUES' => $arListPropList
	);

	if (!empty($arCurrentValues['LABEL_PROP']))
	{
		if (!is_array($arCurrentValues['LABEL_PROP']))
		{
			$arCurrentValues['LABEL_PROP'] = array($arCurrentValues['LABEL_PROP']);
		}

		$selected = array();
		foreach ($arCurrentValues['LABEL_PROP'] as $name)
		{
			if (isset($arListPropList[$name]))
			{
				$selected[$name] = $arListPropList[$name];
			}
		}

		$arTemplateParameters['LABEL_PROP_MOBILE'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_LABEL_PROP_MOBILE'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'VALUES' => $selected
		);
		unset($selected);

		$arTemplateParameters['LIST_LABEL_PROP_POSITION'] = array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_LABEL_PROP_POSITION'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'position'),
			'JS_EVENT' => 'initPositionControl',
			'JS_DATA' => Json::encode(
				array(
					'positions' => array(
						'top-left', 'top-center', 'top-right',
						'middle-left', 'middle-center', 'middle-right',
						'bottom-left', 'bottom-center', 'bottom-right'
					),
					'className' => ''
				)
			),
			'DEFAULT' => 'top-left'
		);

		$arTemplateParameters['DETAIL_LABEL_PROP_POSITION'] = array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_LABEL_PROP_POSITION'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'position'),
			'JS_EVENT' => 'initPositionControl',
			'JS_DATA' => Json::encode(
				array(
					'positions' => array(
						'top-left', 'top-center', 'top-right',
						'middle-left', 'middle-center', 'middle-right',
						'bottom-left', 'bottom-center', 'bottom-right'
					),
					'className' => ''
				)
			),
			'DEFAULT' => 'top-left'
		);
	}

	if ($boolSKU)
	{
/*
		$arTemplateParameters['PRODUCT_DISPLAY_MODE'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_PRODUCT_DISPLAY_MODE'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'Y',
			'DEFAULT' => 'N',
			'VALUES' => array(
				'N' => GetMessage('CP_BC_TPL_DML_SIMPLE'),
				'Y' => GetMessage('CP_BC_TPL_DML_EXT')
			)
		);
*/
		$arAllOfferPropList = array();
		$arFileOfferPropList = array(
			'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
		);
		$arTreeOfferPropList = array(
			'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
		);
		$rsProps = CIBlockProperty::GetList(
			array('SORT' => 'ASC', 'ID' => 'ASC'),
			array('IBLOCK_ID' => $arSKU['IBLOCK_ID'], 'ACTIVE' => 'Y')
		);
		while ($arProp = $rsProps->Fetch())
		{
			if ($arProp['ID'] == $arSKU['SKU_PROPERTY_ID'])
				continue;
			$arProp['USER_TYPE'] = (string)$arProp['USER_TYPE'];
			$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
			if ('' == $arProp['CODE'])
				$arProp['CODE'] = $arProp['ID'];
			$arAllOfferPropList[$arProp['CODE']] = $strPropName;
			if ('F' == $arProp['PROPERTY_TYPE'])
				$arFileOfferPropList[$arProp['CODE']] = $strPropName;
			if ('N' != $arProp['MULTIPLE'])
				continue;
			if (
				'L' == $arProp['PROPERTY_TYPE']
				|| 'E' == $arProp['PROPERTY_TYPE']
				|| ('S' == $arProp['PROPERTY_TYPE'] && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
			)
				$arTreeOfferPropList[$arProp['CODE']] = $strPropName;
		}
		$arTemplateParameters['OFFER_ADD_PICT_PROP'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_OFFER_ADD_PICT_PROP'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '-',
			'VALUES' => $arFileOfferPropList
		);
		$arTemplateParameters['OFFER_ARTNUMBER_PROP'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => Loc::getMessage('RS.MASTER.OFFER_ARTNUMBER_PROP'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '-',
			'VALUES' => $defaultValue + $arAllOfferPropList
		);
        
		$arTemplateParameters['OFFER_TREE_PROPS'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_OFFER_TREE_PROPS'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'Y',
			'DEFAULT' => '-',
			'VALUES' => $arTreeOfferPropList
		);
        
        if (!empty($arCurrentValues['OFFER_TREE_PROPS']))
        {
            $selected = array();

            foreach ($arCurrentValues['OFFER_TREE_PROPS'] as $code)
            {
                if (isset($arTreeOfferPropList[$code]))
                {
                    $selected[$code] = $arTreeOfferPropList[$code];
                }
            }
            
            $arTemplateParameters['OFFER_TREE_DROPDOWN_PROPS'] = array(
                'PARENT' => 'OFFERS_SETTINGS',
                'NAME' => getMessage('RS.MASTER.OFFER_TREE_DROPDOWN_PROPS'),
                'TYPE' => 'LIST',
                'VALUES' => $defaultValue + $selected,
                'MULTIPLE' => 'Y',
                'DEFAULT' => '-',
            );
        }
	}
}

$arCurrentValues['DETAIL_PROPERTY_CODE'] = isset($arCurrentValues['DETAIL_PROPERTY_CODE']) ? $arCurrentValues['DETAIL_PROPERTY_CODE'] : array();
if (!empty($arCurrentValues['DETAIL_PROPERTY_CODE']))
{
	$selected = array();

	foreach ($arCurrentValues['DETAIL_PROPERTY_CODE'] as $code)
	{
		if (isset($arAllPropList[$code]))
		{
			$selected[$code] = $arAllPropList[$code];
		}
	}

	$arTemplateParameters['DETAIL_MAIN_BLOCK_PROPERTY_CODE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_MAIN_BLOCK_PROPERTY_CODE'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'SIZE' => (count($selected) > 5 ? 8 : 3),
		'VALUES' => $selected
	);
/*
	$arTemplateParameters['DETAIL_DISPLAY_PROPERTIES_MAX'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => getMessage('RS.MASTER.DISPLAY_PROPERTIES_MAX'),
		'TYPE' => 'STRING',
		'DEFAULT' => '',
		'HIDDEN' => count($selected) < 1,
	);
*/
}

$arCurrentValues['DETAIL_OFFERS_PROPERTY_CODE'] = isset($arCurrentValues['DETAIL_OFFERS_PROPERTY_CODE']) ? $arCurrentValues['DETAIL_OFFERS_PROPERTY_CODE'] : array();
if (!empty($arCurrentValues['DETAIL_OFFERS_PROPERTY_CODE']))
{
	$selected = array();

	foreach ($arCurrentValues['DETAIL_OFFERS_PROPERTY_CODE'] as $code)
	{
		if (isset($arAllOfferPropList[$code]))
		{
			$selected[$code] = $arAllOfferPropList[$code];
		}
	}

	$arTemplateParameters['DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_MAIN_BLOCK_OFFERS_PROPERTY_CODE'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'SIZE' => (count($selected) > 5 ? 8 : 3),
		'VALUES' => $selected
	);
}

$arTemplateParameters['DETAIL_USE_VOTE_RATING'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_USE_VOTE_RATING'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'N',
	'REFRESH' => 'Y'
);

if (isset($arCurrentValues['DETAIL_USE_VOTE_RATING']) && 'Y' == $arCurrentValues['DETAIL_USE_VOTE_RATING'])
{
	$arTemplateParameters['DETAIL_VOTE_DISPLAY_AS_RATING'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_DETAIL_VOTE_DISPLAY_AS_RATING'),
		'TYPE' => 'LIST',
		'VALUES' => array(
			'rating' => GetMessage('CP_BC_TPL_DVDAR_RATING'),
			'vote_avg' => GetMessage('CP_BC_TPL_DVDAR_AVERAGE'),
		),
		'DEFAULT' => 'rating'
	);
}

$arTemplateParameters['DETAIL_USE_COMMENTS'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_USE_COMMENTS'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'N',
	'REFRESH' => 'Y'
);

if (isset($arCurrentValues['DETAIL_USE_COMMENTS']) && 'Y' == $arCurrentValues['DETAIL_USE_COMMENTS'])
{
	if (ModuleManager::isModuleInstalled("blog"))
	{
		$arTemplateParameters['DETAIL_BLOG_USE'] = array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_DETAIL_BLOG_USE'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y'
		);
		if (isset($arCurrentValues['DETAIL_BLOG_USE']) && $arCurrentValues['DETAIL_BLOG_USE'] == 'Y')
		{
			$arTemplateParameters['DETAIL_BLOG_URL'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('CP_BC_DETAIL_TPL_BLOG_URL'),
				'TYPE' => 'STRING',
				'DEFAULT' => 'catalog_comments'
			);
			$arTemplateParameters['DETAIL_BLOG_EMAIL_NOTIFY'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('CP_BC_TPL_DETAIL_BLOG_EMAIL_NOTIFY'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'N'
			);
		}
	}

	$boolRus = false;
	$langBy = "id";
	$langOrder = "asc";
	$rsLangs = CLanguage::GetList($langBy, $langOrder, array('ID' => 'ru',"ACTIVE" => "Y"));
	if ($arLang = $rsLangs->Fetch())
	{
		$boolRus = true;
	}

	if ($boolRus)
	{
		$arTemplateParameters['DETAIL_VK_USE'] = array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_DETAIL_VK_USE'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y'
		);

		if (isset($arCurrentValues['DETAIL_VK_USE']) && 'Y' == $arCurrentValues['DETAIL_VK_USE'])
		{
			$arTemplateParameters['DETAIL_VK_API_ID'] = array(
				'PARENT' => 'DETAIL_SETTINGS',
				'NAME' => GetMessage('CP_BC_TPL_DETAIL_VK_API_ID'),
				'TYPE' => 'STRING',
				'DEFAULT' => 'API_ID'
			);
		}
	}

	$arTemplateParameters['DETAIL_FB_USE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_DETAIL_FB_USE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y'
	);

	if (isset($arCurrentValues['DETAIL_FB_USE']) && 'Y' == $arCurrentValues['DETAIL_FB_USE'])
	{
		$arTemplateParameters['DETAIL_FB_APP_ID'] = array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_DETAIL_FB_APP_ID'),
			'TYPE' => 'STRING',
			'DEFAULT' => ''
		);
	}
}

if (ModuleManager::isModuleInstalled("highloadblock"))
{
	$arTemplateParameters['DETAIL_BRAND_USE'] = array(
		'PARENT' => 'DATA_SOURCE',
		'NAME' => GetMessage('CP_BC_TPL_DETAIL_BRAND_USE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y'
	);

	if (isset($arCurrentValues['DETAIL_BRAND_USE']) && 'Y' == $arCurrentValues['DETAIL_BRAND_USE'])
	{
		$arTemplateParameters['BRAND_PROP'] = array(
			'PARENT' => 'DATA_SOURCE',
			"NAME" => GetMessage('RS.MASTER.BRAND_PROP'),
			"TYPE" => "LIST",
			"VALUES" => $arHighloadPropList,
			"MULTIPLE" => "N",
		);
        
        $arTemplateParameters['BRAND_IBLOCK_ID'] = array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => getMessage('RS.MASTER.BRAND_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlock,
            'DEFAULT' => '',
            'REFRESH' => 'Y',
        );

        if (intval($arCurrentValues['BRAND_IBLOCK_ID']) > 0) {
            $arBrandProperty = array();
            if (intval($arCurrentValues['IBLOCK_ID']) > 0) {
                $rsBrandProp = CIBlockProperty::GetList(Array('sort' => 'asc', 'name' => 'asc'), Array('IBLOCK_ID' => $arCurrentValues['BRAND_IBLOCK_ID'], 'ACTIVE' => 'Y'));
                while ($arr = $rsBrandProp->Fetch()) {
                    $arBrandProperty[$arr['CODE']] = '['.$arr['CODE'].'] '.$arr['NAME'];
                }
            }
            $arTemplateParameters['BRAND_IBLOCK_BRAND_PROP'] = array(
                'PARENT' => 'DATA_SOURCE',
                'NAME' => getMessage('RS.MASTER.BRAND_IBLOCK_BRAND_PROP'),
                'TYPE' => 'LIST',
                'VALUES' => $defaultValue + $arBrandProperty,
                'DEFAULT' => '-',
            );
        }
	}
}

/*
$arTemplateParameters['DETAIL_DISPLAY_NAME'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_NAME'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y'
);
$arTemplateParameters['DETAIL_IMAGE_RESOLUTION'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_IMAGE_RESOLUTION'),
	'TYPE' => 'LIST',
	'VALUES' => array(
		'16by9' => GetMessage('CP_BC_TPL_DETAIL_IMAGE_RESOLUTION_16_BY_9'),
		'1by1' => GetMessage('CP_BC_TPL_DETAIL_IMAGE_RESOLUTION_1_BY_1')
	),
	'DEFAULT' => '16by9'
);
*/
$arTemplateParameters['DETAIL_PRODUCT_INFO_BLOCK'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => Loc::getMessage('RS.MASTER.DETAIL_PRODUCT_INFO_BLOCK'),
	'TYPE' => 'LIST',
	'VALUES' => $arDetailProductInfoBlock,
	'REFRESH' => 'Y',
	'MULTIPLE' => 'Y',
	'DEFAULT' => '-',
);

if (is_array($arCurrentValues['DETAIL_PRODUCT_INFO_BLOCK']) && count($arCurrentValues['DETAIL_PRODUCT_INFO_BLOCK']) > 0) {
	$selected = array();
	foreach ($arCurrentValues['DETAIL_PRODUCT_INFO_BLOCK'] as $name)
	{
		if (isset($arDetailProductInfoBlock[$name]))
		{
			$selected[$name] = $arDetailProductInfoBlock[$name];
		}
	}

	$arTemplateParameters['DETAIL_PRODUCT_INFO_BLOCK_ORDER'] = array(
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => GetMessage('CP_BC_TPL_PRODUCT_INFO_BLOCK_ORDER'),
        'TYPE' => 'CUSTOM',
        'JS_FILE' => CatalogElementComponent::getSettingsScript('/bitrix/components/bitrix/catalog.element', 'dragdrop_order'),
        'JS_EVENT' => 'initDraggableOrderControl',
        'JS_DATA' => Json::encode($selected),
        'DEFAULT' => 'preview,props,price,buttons'
	);
	unset($selected);
}
/*
$arTemplateParameters['DETAIL_PRODUCT_PAY_BLOCK_ORDER'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_PRODUCT_PAY_BLOCK_ORDER'),
	'TYPE' => 'CUSTOM',
	'JS_FILE' => CatalogElementComponent::getSettingsScript('/bitrix/components/bitrix/catalog.element', 'dragdrop_order'),
	'JS_EVENT' => 'initDraggableOrderControl',
	'JS_DATA' => Json::encode(array(
		'rating' => GetMessage('CP_BC_TPL_DETAIL_PRODUCT_BLOCK_RATING'),
		'price' => GetMessage('CP_BC_TPL_DETAIL_PRODUCT_BLOCK_PRICE'),
		'priceRanges' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_PRICE_RANGES'),
		'quantityLimit' => GetMessage('CP_BC_TPL_DETAIL_PRODUCT_BLOCK_QUANTITY_LIMIT'),
		'quantity' => GetMessage('CP_BC_TPL_DETAIL_PRODUCT_BLOCK_QUANTITY'),
		'buttons' => GetMessage('CP_BC_TPL_DETAIL_PRODUCT_BLOCK_BUTTONS')
	)),
	'DEFAULT' => 'rating,price,priceRanges,quantityLimit,quantity,buttons'
);
$arTemplateParameters['DETAIL_SHOW_SLIDER'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_SHOW_SLIDER'),
	'TYPE' => 'CHECKBOX',
	'MULTIPLE' => 'N',
	'REFRESH' => 'Y',
	'DEFAULT' => 'N'
);

if (isset($arCurrentValues['DETAIL_SHOW_SLIDER']) && $arCurrentValues['DETAIL_SHOW_SLIDER'] === 'Y')
{
	$arTemplateParameters['DETAIL_SLIDER_INTERVAL'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_DETAIL_SLIDER_INTERVAL'),
		'TYPE' => 'TEXT',
		'MULTIPLE' => 'N',
		'REFRESH' => 'N',
		'DEFAULT' => '5000'
	);
	$arTemplateParameters['DETAIL_SLIDER_PROGRESS'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_DETAIL_SLIDER_PROGRESS'),
		'TYPE' => 'CHECKBOX',
		'MULTIPLE' => 'N',
		'REFRESH' => 'N',
		'DEFAULT' => 'N'
	);
}

$arTemplateParameters['DETAIL_DETAIL_PICTURE_MODE'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_DETAIL_PICTURE_MODE'),
	'TYPE' => 'LIST',
	'MULTIPLE' => 'Y',
	'DEFAULT' => array('POPUP', 'MAGNIFIER'),
	'VALUES' => array(
		'POPUP' => GetMessage('DETAIL_DETAIL_PICTURE_MODE_POPUP'),
		'MAGNIFIER' => GetMessage('DETAIL_DETAIL_PICTURE_MODE_MAGNIFIER'),
	)
);
*/
$arTemplateParameters['DETAIL_ADD_DETAIL_TO_SLIDER'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_ADD_DETAIL_TO_SLIDER'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'N'
);

$arTemplateParameters['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE'),
	'TYPE' => 'LIST',
	'VALUES' => array(
		'H' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE_HIDE'),
		'E' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE_EMPTY_DETAIL'),
		'S' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE_SHOW')
	),
	'DEFAULT' => 'E'
);

if (!$boolIsCatalog && $boolLightBasket) {
	$arTemplateParameters['IS_USE_CART'] = array(
		'NAME' => Loc::getMessage('RS.MASTER.IS_USE_CART'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
		'REFRESH' => 'Y',
	);
    
    unset($addToBasketActions['BUY']);
}

if ($boolIsCatalog)
{
/*
	$arTemplateParameters['USE_COMMON_SETTINGS_BASKET_POPUP'] = array(
		'PARENT' => 'BASKET',
		'NAME' => GetMessage('CP_BC_TPL_USE_COMMON_SETTINGS_BASKET_POPUP'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y'
	);
	$useCommonSettingsBasketPopup = (
		isset($arCurrentValues['USE_COMMON_SETTINGS_BASKET_POPUP'])
		&& $arCurrentValues['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y'
	);
	$arTemplateParameters['COMMON_ADD_TO_BASKET_ACTION'] = array(
		'PARENT' => 'BASKET',
		'NAME' => GetMessage('CP_BC_TPL_COMMON_ADD_TO_BASKET_ACTION'),
		'TYPE' => 'LIST',
		'VALUES' => $addToBasketActions,
		'DEFAULT' => 'ADD',
		'REFRESH' => 'N',
		'HIDDEN' => ($useCommonSettingsBasketPopup ? 'N' : 'Y')
	);
	$arTemplateParameters['COMMON_SHOW_CLOSE_POPUP'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_COMMON_SHOW_CLOSE_POPUP'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	);
	$arTemplateParameters['MESS_PRICE_RANGES_TITLE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_MESS_PRICE_RANGES_TITLE'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('CP_BC_TPL_MESS_PRICE_RANGES_TITLE_DEFAULT')
	);
	$arTemplateParameters['TOP_ADD_TO_BASKET_ACTION'] = array(
		'PARENT' => 'BASKET',
		'NAME' => GetMessage('CP_BC_TPL_TOP_ADD_TO_BASKET_ACTION'),
		'TYPE' => 'LIST',
		'VALUES' => $addToBasketActions,
		'DEFAULT' => 'ADD',
		'REFRESH' => 'N',
		'HIDDEN' => (!$useCommonSettingsBasketPopup ? 'N' : 'Y')
	);
*/
	$arTemplateParameters['PRODUCT_SUBSCRIPTION'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_PRODUCT_SUBSCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	);
/*
	$arTemplateParameters['SHOW_DISCOUNT_PERCENT'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_SHOW_DISCOUNT_PERCENT'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y',
	);

	if (isset($arCurrentValues['SHOW_DISCOUNT_PERCENT']) && $arCurrentValues['SHOW_DISCOUNT_PERCENT'] === 'Y')
	{
		$arTemplateParameters['DISCOUNT_PERCENT_POSITION'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_DISCOUNT_PERCENT_POSITION'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'position'),
			'JS_EVENT' => 'initPositionControl',
			'JS_DATA' => Json::encode(
				array(
					'positions' => array(
						'top-left', 'top-center', 'top-right',
						'middle-left', 'middle-center', 'middle-right',
						'bottom-left', 'bottom-center', 'bottom-right'
					),
					'className' => 'bx-pos-parameter-block-circle'
				)
			),
			'DEFAULT' => 'bottom-right'
		);
	}
*/
	$arTemplateParameters['SHOW_MAX_QUANTITY'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_SHOW_MAX_QUANTITY'),
		'TYPE' => 'LIST',
		'REFRESH' => 'Y',
		'MULTIPLE' => 'N',
		'VALUES' => array(
			'N' => GetMessage('CP_BC_TPL_SHOW_MAX_QUANTITY_N'),
			'Y' => GetMessage('CP_BC_TPL_SHOW_MAX_QUANTITY_Y'),
			'M' => GetMessage('CP_BC_TPL_SHOW_MAX_QUANTITY_M')
		),
		'DEFAULT' => array('N')
	);

	if (isset($arCurrentValues['SHOW_MAX_QUANTITY']))
	{
		if ($arCurrentValues['SHOW_MAX_QUANTITY'] !== 'N')
		{
			$arTemplateParameters['MESS_SHOW_MAX_QUANTITY'] = array(
				'PARENT' => 'VISUAL',
				'NAME' => GetMessage('CP_BC_TPL_MESS_SHOW_MAX_QUANTITY'),
				'TYPE' => 'STRING',
				'DEFAULT' => GetMessage('CP_BC_TPL_MESS_SHOW_MAX_QUANTITY_DEFAULT')
			);
		}

		if ($arCurrentValues['SHOW_MAX_QUANTITY'] === 'M')
		{
			$arTemplateParameters['RELATIVE_QUANTITY_FACTOR'] = array(
				'PARENT' => 'VISUAL',
				'NAME' => GetMessage('CP_BC_TPL_RELATIVE_QUANTITY_FACTOR'),
				'TYPE' => 'STRING',
				'DEFAULT' => '5'
			);
			$arTemplateParameters['MESS_RELATIVE_QUANTITY_MANY'] = array(
				'PARENT' => 'VISUAL',
				'NAME' => GetMessage('CP_BC_TPL_MESS_RELATIVE_QUANTITY_MANY'),
				'TYPE' => 'STRING',
				'DEFAULT' => GetMessage('CP_BC_TPL_MESS_RELATIVE_QUANTITY_MANY_DEFAULT')
			);
			$arTemplateParameters['MESS_RELATIVE_QUANTITY_FEW'] = array(
				'PARENT' => 'VISUAL',
				'NAME' => GetMessage('CP_BC_TPL_MESS_RELATIVE_QUANTITY_FEW'),
				'TYPE' => 'STRING',
				'DEFAULT' => GetMessage('CP_BC_TPL_MESS_RELATIVE_QUANTITY_FEW_DEFAULT')
			);
		}
	}

} else {
    
    $arTemplateParameters['PRICE_PROP'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.MASTER.PRICE_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    );
    $arTemplateParameters['DISCOUNT_PROP'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.MASTER.DISCOUNT_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    );

    $arTemplateParameters['CURRENCY_PROP'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.MASTER.CURRENCY_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $defaultValue + $arAllPropList,
        'DEFAULT' => '-',
    );

    $arTemplateParameters['PRICE_DECIMALS'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.MASTER.PRICE_DECIMALS'),
        'TYPE' => 'LIST',
        'VALUES' => array(
            '0' => '0',
            '1' => '1',
            '2' => '2',
        ),
        'DEFAULT' => '0',
    );

}

$arTemplateParameters['SHOW_OLD_PRICE'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('CP_BC_TPL_SHOW_OLD_PRICE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
);

$arTemplateParameters['SECTION_ADD_TO_BASKET_ACTION'] = array(
	'PARENT' => 'BASKET',
	'NAME' => GetMessage('CP_BC_TPL_SECTION_ADD_TO_BASKET_ACTION'),
	'TYPE' => 'LIST',
	'VALUES' => array_diff_key($addToBasketActions, array('ASK' => '')),
	'DEFAULT' => 'ADD',
	'REFRESH' => 'N',
	//'HIDDEN' => (!$useCommonSettingsBasketPopup ? 'N' : 'Y')
);
$arTemplateParameters['DETAIL_ADD_TO_BASKET_ACTION'] = array(
	'PARENT' => 'BASKET',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_ADD_TO_BASKET_ACTION'),
	'TYPE' => 'LIST',
	'VALUES' => $addToBasketActions,
	'DEFAULT' => 'BUY',
	'REFRESH' => 'Y',
	'MULTIPLE' => 'Y',
	//'HIDDEN' => (!$useCommonSettingsBasketPopup ? 'N' : 'Y')
);

if (!empty($arCurrentValues['DETAIL_ADD_TO_BASKET_ACTION']))
{
	$selected = array();

	if (!is_array($arCurrentValues['DETAIL_ADD_TO_BASKET_ACTION']))
	{
		$arCurrentValues['DETAIL_ADD_TO_BASKET_ACTION'] = array($arCurrentValues['DETAIL_ADD_TO_BASKET_ACTION']);
	}

	foreach ($arCurrentValues['DETAIL_ADD_TO_BASKET_ACTION'] as $action)
	{
		if (isset($addToBasketActions[$action]))
		{
			$selected[$action] = $addToBasketActions[$action];
		}
	}

	$arTemplateParameters['DETAIL_ADD_TO_BASKET_ACTION_PRIMARY'] = array(
		'PARENT' => 'BASKET',
		'NAME' => GetMessage('CP_BC_TPL_DETAIL_ADD_TO_BASKET_ACTION_PRIMARY'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'VALUES' => $selected,
		'DEFAULT' => 'BUY',
		'REFRESH' => 'N'
	);
	unset($selected);
}

$arTemplateParameters['ARTNUMBER_PROP'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => Loc::getMessage('RS.MASTER.ARTNUMBER_PROP'),
	'TYPE' => 'LIST',
	'VALUES' => $defaultValue + $arAllPropList,
	'DEFAULT' => '-',
);

$arTemplateParameters['LAZY_LOAD'] = array(
	'PARENT' => 'PAGER_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_LAZY_LOAD'),
	'TYPE' => 'CHECKBOX',
	'REFRESH' => 'Y',
	'DEFAULT' => 'N'
);

if (isset($arCurrentValues['LAZY_LOAD']) && $arCurrentValues['LAZY_LOAD'] === 'Y')
{
	$arTemplateParameters['MESS_BTN_LAZY_LOAD'] = array(
		'PARENT' => 'PAGER_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_MESS_BTN_LAZY_LOAD'),
		'TYPE' => 'TEXT',
		'DEFAULT' => GetMessage('CP_BC_TPL_MESS_BTN_LAZY_LOAD_DEFAULT')
	);
}

$arTemplateParameters['LOAD_ON_SCROLL'] = array(
	'PARENT' => 'PAGER_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_LOAD_ON_SCROLL'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'N'
);

/*
$arTemplateParameters['MESS_BTN_BUY'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('CP_BC_TPL_MESS_BTN_BUY'),
	'TYPE' => 'STRING',
	'DEFAULT' => GetMessage('CP_BC_TPL_MESS_BTN_BUY_DEFAULT')
);
*/
$arTemplateParameters['MESS_BTN_ADD_TO_BASKET'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('CP_BC_TPL_MESS_BTN_ADD_TO_BASKET'),
	'TYPE' => 'STRING',
	'DEFAULT' => GetMessage('CP_BC_TPL_MESS_BTN_ADD_TO_BASKET_DEFAULT')
);
$arTemplateParameters['MESS_BTN_COMPARE'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('CP_BC_TPL_MESS_BTN_COMPARE'),
	'TYPE' => 'STRING',
	'DEFAULT' => GetMessage('CP_BC_TPL_MESS_BTN_COMPARE_DEFAULT')
);
$arTemplateParameters['MESS_BTN_DETAIL'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('CP_BC_TPL_MESS_BTN_DETAIL'),
	'TYPE' => 'STRING',
	'DEFAULT' => GetMessage('CP_BC_TPL_MESS_BTN_DETAIL_DEFAULT')
);
$arTemplateParameters['MESS_NOT_AVAILABLE'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('CP_BC_TPL_MESS_NOT_AVAILABLE'),
	'TYPE' => 'STRING',
	'DEFAULT' => GetMessage('CP_BC_TPL_MESS_NOT_AVAILABLE_DEFAULT')
);
$arTemplateParameters['MESS_BTN_SUBSCRIBE'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('CP_BC_TPL_MESS_BTN_SUBSCRIBE'),
	'TYPE' => 'STRING',
	'DEFAULT' => GetMessage('CP_BC_TPL_MESS_BTN_SUBSCRIBE_DEFAULT')
);

if (ModuleManager::isModuleInstalled("sale"))
{
	$arTemplateParameters['USE_SALE_BESTSELLERS'] = array(
		'NAME' => GetMessage('CP_BC_TPL_USE_SALE_BESTSELLERS'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y'
	);

	$arTemplateParameters['USE_BIG_DATA'] = array(
		'PARENT' => 'BIG_DATA_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_USE_BIG_DATA'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
		'REFRESH' => 'Y'
	);
	if (!isset($arCurrentValues['USE_BIG_DATA']) || $arCurrentValues['USE_BIG_DATA'] == 'Y')
	{
		$rcmTypeList = array(
			'personal' => GetMessage('CP_BC_TPL_RCM_PERSONAL'),
			'bestsell' => GetMessage('CP_BC_TPL_RCM_BESTSELLERS'),
			'similar_sell' => GetMessage('CP_BC_TPL_RCM_SOLD_WITH'),
			'similar_view' => GetMessage('CP_BC_TPL_RCM_VIEWED_WITH'),
			'similar' => GetMessage('CP_BC_TPL_RCM_SIMILAR'),
			'any_similar' => GetMessage('CP_BC_TPL_RCM_SIMILAR_ANY'),
			'any_personal' => GetMessage('CP_BC_TPL_RCM_PERSONAL_WBEST'),
			'any' => GetMessage('CP_BC_TPL_RCM_RAND')
		);
		$arTemplateParameters['BIG_DATA_RCM_TYPE'] = array(
			'PARENT' => 'BIG_DATA_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_BIG_DATA_RCM_TYPE'),
			'TYPE' => 'LIST',
			'DEFAULT' => 'personal',
			'VALUES' => $rcmTypeList
		);
		unset($rcmTypeList);
	}
}

/*
if (isset($arCurrentValues['SHOW_TOP_ELEMENTS']) && 'Y' == $arCurrentValues['SHOW_TOP_ELEMENTS'])
{
	$arTemplateParameters['TOP_VIEW_MODE'] = array(
		'PARENT' => 'TOP_SETTINGS',
		'NAME' => GetMessage('CPT_BC_TPL_TOP_VIEW_MODE'),
		'TYPE' => 'LIST',
		'VALUES' => array(
			'BANNER' => GetMessage('CPT_BC_TPL_VIEW_MODE_BANNER'),
			'SLIDER' => GetMessage('CPT_BC_TPL_VIEW_MODE_SLIDER'),
			'SECTION' => GetMessage('CPT_BC_TPL_VIEW_MODE_SECTION')
		),
		'MULTIPLE' => 'N',
		'DEFAULT' => 'SECTION',
		'REFRESH' => 'Y'
	);

	if (isset($arCurrentValues['TOP_VIEW_MODE']) && ('SLIDER' == $arCurrentValues['TOP_VIEW_MODE'] || 'BANNER' == $arCurrentValues['TOP_VIEW_MODE']))
	{
		$arTemplateParameters['TOP_ROTATE_TIMER'] = array(
			'PARENT' => 'TOP_SETTINGS',
			'NAME' => GetMessage('CPT_BC_TPL_TOP_ROTATE_TIMER'),
			'TYPE' => 'STRING',
			'DEFAULT' => '30'
		);
	}

	if (isset($arCurrentValues['TOP_VIEW_MODE']) && $arCurrentValues['TOP_VIEW_MODE'] === 'SECTION')
	{
		if (!empty($arCurrentValues['TOP_PROPERTY_CODE']))
		{
			$selected = array();

			foreach ($arCurrentValues['TOP_PROPERTY_CODE'] as $code)
			{
				if (isset($arAllPropList[$code]))
				{
					$selected[$code] = $arAllPropList[$code];
				}
			}

			$arTemplateParameters['TOP_PROPERTY_CODE_MOBILE'] = array(
				'PARENT' => 'TOP_SETTINGS',
				'NAME' => GetMessage('CP_BC_TPL_PROPERTY_CODE_MOBILE'),
				'TYPE' => 'LIST',
				'MULTIPLE' => 'Y',
				'VALUES' => $selected
			);
		}

		$arTemplateParameters['TOP_PRODUCT_BLOCKS_ORDER'] = array(
			'PARENT' => 'TOP_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_PRODUCT_BLOCKS_ORDER'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => CatalogTopComponent::getSettingsScript('/bitrix/components/bitrix/catalog.top', 'dragdrop_order'),
			'JS_EVENT' => 'initDraggableOrderControl',
			'JS_DATA' => Json::encode(array(
				'price' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_PRICE'),
				'quantityLimit' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_QUANTITY_LIMIT'),
				'quantity' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_QUANTITY'),
				'buttons' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_BUTTONS'),
				'props' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_PROPS'),
				'sku' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_SKU'),
				'compare' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_COMPARE')
			)),
			'DEFAULT' => 'price,props,sku,quantityLimit,quantity,buttons,compare'
		);

		$lineElementCount = (int)$arCurrentValues['TOP_LINE_ELEMENT_COUNT'] ?: 3;
		$pageElementCount = (int)$arCurrentValues['TOP_ELEMENT_COUNT'] ?: 9;

		$arTemplateParameters['TOP_PRODUCT_ROW_VARIANTS'] = array(
			'PARENT' => 'TOP_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_PRODUCT_ROW_VARIANTS'),
			'TYPE' => 'CUSTOM',
			'BIG_DATA' => 'N',
			'COUNT_PARAM_NAME' => 'TOP_ELEMENT_COUNT',
			'JS_FILE' => CatalogTopComponent::getSettingsScript('/bitrix/components/bitrix/catalog.top', 'dragdrop_add'),
			'JS_EVENT' => 'initDraggableAddControl',
			'JS_MESSAGES' => Json::encode(array(
				'variant' => GetMessage('CP_BC_TPL_SETTINGS_VARIANT'),
				'delete' => GetMessage('CP_BC_TPL_SETTINGS_DELETE'),
				'quantity' => GetMessage('CP_BC_TPL_SETTINGS_QUANTITY'),
				'quantityBigData' => GetMessage('CP_BC_TPL_SETTINGS_QUANTITY_BIG_DATA')
			)),
			'JS_DATA' => Json::encode(CatalogTopComponent::getTemplateVariantsMap()),
			'DEFAULT' => Json::encode(CatalogTopComponent::predictRowVariants($lineElementCount, $pageElementCount))
		);

		$arTemplateParameters['TOP_ENLARGE_PRODUCT'] = array(
			'PARENT' => 'TOP_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_ENLARGE_PRODUCT'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'Y',
			'DEFAULT' => 'N',
			'VALUES' => array(
				'STRICT' => GetMessage('CP_BC_TPL_ENLARGE_PRODUCT_STRICT'),
				'PROP' => GetMessage('CP_BC_TPL_ENLARGE_PRODUCT_PROP')
			)
		);

		if (isset($arCurrentValues['TOP_ENLARGE_PRODUCT']) && $arCurrentValues['TOP_ENLARGE_PRODUCT'] === 'PROP')
		{
			$arTemplateParameters['TOP_ENLARGE_PROP'] = array(
				'PARENT' => 'TOP_SETTINGS',
				'NAME' => GetMessage('CP_BC_TPL_ENLARGE_PROP'),
				'TYPE' => 'LIST',
				'MULTIPLE' => 'N',
				'ADDITIONAL_VALUES' => 'N',
				'REFRESH' => 'N',
				'DEFAULT' => '-',
				'VALUES' => $defaultValue + $arListPropList
			);
		}
		$arTemplateParameters['TOP_SHOW_SLIDER'] = array(
			'PARENT' => 'TOP_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_SHOW_SLIDER'),
			'TYPE' => 'CHECKBOX',
			'MULTIPLE' => 'N',
			'REFRESH' => 'Y',
			'DEFAULT' => 'Y'
		);

		if (!isset($arCurrentValues['TOP_SHOW_SLIDER']) || $arCurrentValues['TOP_SHOW_SLIDER'] === 'Y')
		{
			$arTemplateParameters['TOP_SLIDER_INTERVAL'] = array(
				'PARENT' => 'TOP_SETTINGS',
				'NAME' => GetMessage('CP_BC_TPL_SLIDER_INTERVAL'),
				'TYPE' => 'TEXT',
				'MULTIPLE' => 'N',
				'REFRESH' => 'N',
				'DEFAULT' => '3000'
			);
			$arTemplateParameters['TOP_SLIDER_PROGRESS'] = array(
				'PARENT' => 'TOP_SETTINGS',
				'NAME' => GetMessage('CP_BC_TPL_SLIDER_PROGRESS'),
				'TYPE' => 'CHECKBOX',
				'MULTIPLE' => 'N',
				'REFRESH' => 'N',
				'DEFAULT' => 'N'
			);
		}
	}
}

if (isset($arCurrentValues['USE_COMPARE']) && $arCurrentValues['USE_COMPARE'] == 'Y')
{
	$arTemplateParameters['COMPARE_POSITION_FIXED'] = array(
		'PARENT' => 'COMPARE_SETTINGS',
		'NAME' => GetMessage('CPT_BC_TPL_COMPARE_POSITION_FIXED'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
		'REFRESH' => 'Y'
	);
	if (!isset($arCurrentValues['COMPARE_POSITION_FIXED']) || $arCurrentValues['COMPARE_POSITION_FIXED'] == 'Y')
	{
		$positionList = array(
			'top left' => GetMessage('CPT_BC_TPL_PARAM_COMPARE_POSITION_TOP_LEFT'),
			'top right' => GetMessage('CPT_BC_TPL_PARAM_COMPARE_POSITION_TOP_RIGHT'),
			'bottom left' => GetMessage('CPT_BC_TPL_PARAM_COMPARE_POSITION_BOTTOM_LEFT'),
			'bottom right' => GetMessage('CPT_BC_TPL_PARAM_COMPARE_POSITION_BOTTOM_RIGHT')
		);
		$arTemplateParameters['COMPARE_POSITION'] = array(
			'PARENT' => 'COMPARE_SETTINGS',
			'NAME' => GetMessage('CPT_BC_TPL_COMPARE_POSITION'),
			'TYPE' => 'LIST',
			'VALUES' => $positionList,
			'DEFAULT' => 'top left'
		);
		unset($positionList);
	}
}
*/

$arTemplateParameters['SECTIONS_SHOW_SIDEBAR'] = array(
	'PARENT' => 'SECTIONS_SETTINGS',
	'NAME' => getMessage('RS.MASTER.SHOW_SIDEBAR'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'SORT' => 800
);
$arTemplateParameters['LIST_SHOW_SIDEBAR'] = array(
	'PARENT' => 'LIST_SETTINGS',
	'NAME' => getMessage('RS.MASTER.SHOW_SIDEBAR'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
	'SORT' => 800
);
$arTemplateParameters['DETAIL_SHOW_SIDEBAR'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => getMessage('RS.MASTER.SHOW_SIDEBAR'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'N',
	'SORT' => 800
);
/*
$arTemplateParameters['SIDEBAR_PATH'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('CPT_SIDEBAR_PATH'),
	'TYPE' => 'STRING',
	'SORT' => 800
);
*/

$arTemplateParameters['USE_ENHANCED_ECOMMERCE'] = array(
	'PARENT' => 'ANALYTICS_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_USE_ENHANCED_ECOMMERCE'),
	'TYPE' => 'CHECKBOX',
	'REFRESH' => 'Y',
	'DEFAULT' => 'N'
);

if (isset($arCurrentValues['USE_ENHANCED_ECOMMERCE']) && $arCurrentValues['USE_ENHANCED_ECOMMERCE'] === 'Y')
{
	$arTemplateParameters['DATA_LAYER_NAME'] = array(
		'PARENT' => 'ANALYTICS_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_DATA_LAYER_NAME'),
		'TYPE' => 'STRING',
		'DEFAULT' => 'dataLayer'
	);
	$arTemplateParameters['BRAND_PROPERTY'] = array(
		'PARENT' => 'ANALYTICS_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_BRAND_PROPERTY'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'N',
		'DEFAULT' => '',
		'VALUES' => $defaultValue + $arAllPropList
	);
}

$arTemplateParameters['DETAIL_SHOW_POPULAR'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_SHOW_POPULAR'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y'
);
$arTemplateParameters['DETAIL_SHOW_VIEWED'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_SHOW_VIEWED'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y'
);

// hack to hide component parameters by templates
$arTemplateParameters['HIDE_USE_ALSO_BUY'] = array();

$arTemplateParameters['PRODUCT_DEALS_USER_FIELDS'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('RS.MASTER.PRODUCT_DEALS_USER_FIELDS'),
    'TYPE' => 'LIST',
    'VALUES' => array_merge($defaultValue, $arProperty_UF),
    'DEFAULT' => '-',
);

$arTemplateParameters['PRODUCT_DEALS_PROP'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('RS.MASTER.PRODUCT_DEALS_PROP'),
    'TYPE' => 'LIST',
    'VALUES' => $defaultValue + $arAllPropList,
    'DEFAULT' => '-',
);

// TABS
$arTemplateParameters['DETAIL_TAB_PROPERTIES'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => Loc::getMessage('RS.MASTER.DETAIL_TAB_PROPERTIES'),
	'TYPE' => 'LIST',
	'VALUES' => $defaultValue + $arAllPropList,
	'REFRESH' => 'Y',
	'MULTIPLE' => 'Y',
	'DEFAULT' => '-',
);

if (is_array($arCurrentValues['DETAIL_TAB_PROPERTIES']) && count($arCurrentValues['DETAIL_TAB_PROPERTIES']) > 0) {
	foreach ($arCurrentValues['DETAIL_TAB_PROPERTIES'] as $code) {
		if (isset($arAllPropList[$code])) {
			$arDetailTabs['prop_'.$code] = $arAllPropList[$code];
		}
	}
	unset($sPropcode, $sPropName);
}

$arTemplateParameters['DETAIL_TABS'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => Loc::getMessage('RS.MASTER.DETAIL_TABS'),
	'TYPE' => 'LIST',
	'VALUES' => $defaultValue + $arDetailTabs,
	'REFRESH' => 'Y',
	'MULTIPLE' => 'Y',
	'DEFAULT' => '-',
);

if (is_array($arCurrentValues['DETAIL_TABS']) && count($arCurrentValues['DETAIL_TABS']) > 0) {
	$selected = array();
	foreach ($arCurrentValues['DETAIL_TABS'] as $name)
	{
		if (isset($arDetailTabs[$name]))
		{
			$selected[$name] = $arDetailTabs[$name];
		}
	}

	$arTemplateParameters['DETAIL_TABS_ORDER'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('RS.MASTER.DETAIL_TABS_ORDER'),
		'TYPE' => 'CUSTOM',
		'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'dragdrop_order'),
		'JS_EVENT' => 'initDraggableOrderControl',
		'JS_DATA' => Json::encode($selected),
		'DEFAULT' => 'detail,props,comments'
	);
	unset($selected);
}

// BLOCK_LINES
$arTemplateParameters['DETAIL_BLOCK_LINES_PROPERTIES'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => Loc::getMessage('RS.MASTER.DETAIL_BLOCK_LINES_PROPERTIES'),
	'TYPE' => 'LIST',
	'VALUES' => $defaultValue + $arAllPropList,
	'REFRESH' => 'Y',
	'MULTIPLE' => 'Y',
	'DEFAULT' => '-',
);

if (is_array($arCurrentValues['DETAIL_BLOCK_LINES_PROPERTIES']) && count($arCurrentValues['DETAIL_BLOCK_LINES_PROPERTIES']) > 0) {
	foreach ($arCurrentValues['DETAIL_BLOCK_LINES_PROPERTIES'] as $code) {
		if (isset($arAllPropList[$code])) {
			$arDetailBlockLines['prop_'.$code] = $arAllPropList[$code];
		}
	}
	unset($sPropcode, $sPropName);
}

$arTemplateParameters['DETAIL_BLOCK_LINES'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => Loc::getMessage('RS.MASTER.DETAIL_BLOCK_LINES'),
	'TYPE' => 'LIST',
	'VALUES' => $defaultValue + $arDetailBlockLines,
	'REFRESH' => 'Y',
	'MULTIPLE' => 'Y',
	'DEFAULT' => '-',
);

if (is_array($arCurrentValues['DETAIL_BLOCK_LINES']) && count($arCurrentValues['DETAIL_BLOCK_LINES']) > 0) {
	$selected = array();
	foreach ($arCurrentValues['DETAIL_BLOCK_LINES'] as $name)
	{
		if (isset($arDetailBlockLines[$name]))
		{
			$selected[$name] = $arDetailBlockLines[$name];
		}
	}

	$arTemplateParameters['DETAIL_BLOCK_LINES_ORDER'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('RS.MASTER.DETAIL_BLOCK_LINES_ORDER'),
		'TYPE' => 'CUSTOM',
		'JS_FILE' => CatalogSectionComponent::getSettingsScript('/bitrix/components/bitrix/catalog.section', 'dragdrop_order'),
		'JS_EVENT' => 'initDraggableOrderControl',
		'JS_DATA' => Json::encode($selected),
		'DEFAULT' => 'detail,props,comments'
	);
	unset($selected);
}

if (
	in_array('detail', $arCurrentValues['DETAIL_TABS'])
	|| in_array('detail', $arCurrentValues['DETAIL_BLOCK_LINES'])
) {
	$arTemplateParameters['MESS_DESCRIPTION_TAB'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_MESS_DESCRIPTION_TAB'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('CP_BC_TPL_MESS_DESCRIPTION_TAB_DEFAULT')
	);
}

if (
	in_array('props', $arCurrentValues['DETAIL_TABS'])
	|| in_array('props', $arCurrentValues['DETAIL_BLOCK_LINES'])
) {
	$arTemplateParameters['MESS_PROPERTIES_TAB'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_MESS_PROPERTIES_TAB'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('CP_BC_TPL_MESS_PROPERTIES_TAB_DEFAULT')
	);
}

if (
	in_array('comments', $arCurrentValues['DETAIL_TABS'])
	|| in_array('comments', $arCurrentValues['DETAIL_BLOCK_LINES'])
) {
	$arTemplateParameters['MESS_COMMENTS_TAB'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => GetMessage('CP_BC_TPL_MESS_COMMENTS_TAB'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('CP_BC_TPL_MESS_COMMENTS_TAB_DEFAULT')
	);
}


// MASTER
if (Loader::includeModule('redsign.master')) {

	$arTemplateParameters['LIST_TEMPLATE'] = array(
		'PARENT' => 'LIST_SETTINGS',
		'NAME' => Loc::getMessage('RS.MASTER.LIST_TEMPLATE'),
		'TYPE' => 'LIST',
		'VALUES' => $arCSTemplates,
		'DEFAULT' => in_array('master', $arCSTemplates) ? 'master' : $arCSTemplates[0],
		'REFRESH' => 'Y',
	);

	$arTemplateParameters['DETAIL_TEMPLATE'] = array(
		'PARENT' => 'DETAIL_SETTINGS',
		'NAME' => Loc::getMessage('RS.MASTER.DETAIL_TEMPLATE'),
		'TYPE' => 'LIST',
		'VALUES' => $arCETemplates,
		'DEFAULT' => in_array('master', $arCETemplates) ? 'master' : $arCETemplates[0],
		'REFRESH' => 'Y',
	);
	
	if ($arCurrentValues['CATALOG_VIEW_MODE'] == 'VIEW_SECTIONS') {
		ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('sectionsView'));

		$arTemplateParameters['SECTIONS_VIEW_MODE'] = $arTemplateParameters['SECTIONS_VIEW_MODE'];
	}
	
	
	if ('LINE' == $arCurrentValues['SECTIONS_VIEW_MODE']) {
		$arTemplateParameters['HIDE_SECTION_NAME'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CPT_BC_SECTIONS_HIDE_SECTION_NAME'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N'
		);

		ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('bootstrapCols'));
		
		$arTemplateParameters['SECTIONS_COL_XS'] = $arTemplateParameters['COL_XS'];
		$arTemplateParameters['SECTIONS_COL_XS']['PARENT'] = 'SECTIONS_SETTINGS';
		
		$arTemplateParameters['SECTIONS_COL_SM'] = $arTemplateParameters['COL_SM'];
		$arTemplateParameters['SECTIONS_COL_SM']['PARENT'] = 'SECTIONS_SETTINGS';
		
		$arTemplateParameters['SECTIONS_COL_MD'] = $arTemplateParameters['COL_MD'];
		$arTemplateParameters['SECTIONS_COL_MD']['PARENT'] = 'SECTIONS_SETTINGS';
		
		$arTemplateParameters['SECTIONS_COL_LG'] = $arTemplateParameters['COL_LG'];
		$arTemplateParameters['SECTIONS_COL_LG']['PARENT'] = 'SECTIONS_SETTINGS';

		unset(
			$arTemplateParameters['COLS_IN_ROW'],
			$arTemplateParameters['COL_XS'],
			$arTemplateParameters['COL_SM'],
			$arTemplateParameters['COL_MD'],
			$arTemplateParameters['COL_LG']
		);
	}

	ParametersUtils::addCommonParameters(
		$arTemplateParameters,
		$arCurrentValues,
		array(
			'share',
		)
	);

	//unset($arTemplateParameters['SECTIONS_VIEW_MODE']);
	unset($arTemplateParameters['ADD_CONTAINER']);
}

if ($arCurrentValues['USE_SHARE'] == 'Y') {
	//$arTemplateParameters['LIST_SOCIAL_SERVICES'] = array_merge(array('PARENT' => 'LIST_SETTINGS'), $arTemplateParameters['SOCIAL_SERVICES']);
	$arTemplateParameters['DETAIL_SOCIAL_SERVICES'] = array_merge(array('PARENT' => 'DETAIL_SETTINGS'), $arTemplateParameters['SOCIAL_SERVICES']);
	unset($arTemplateParameters['SOCIAL_SERVICES']);
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
		'VALUES' => $defaultValue + $arAllPropList,
		'DEFAULT' => 'LANDING_LINK',
	);
}

if ($arCurrentValues['USE_FILTER'] == 'Y') {

	$arFilterThemes = array(
		"default" => GetMessage("CP_BCT_TPL_THEME_DEFAULT"),
		"lite" => GetMessage("CP_BCT_TPL_THEME_LITE"),
	);
	
	$arTemplateParameters['FILTER_THEME'] = array(
		'PARENT' => 'FILTER_SETTINGS',
		'NAME' => GetMessage("RS.MASTER.FILTER_THEME"),
		'TYPE' => 'LIST',
		'VALUES' => $arFilterThemes,
		'DEFAULT' => 'default',
		'ADDITIONAL_VALUES' => 'Y'
	);
	$arTemplateParameters['FILTER_SCROLL_PROPS'] = array(
		'PARENT' => 'FILTER_SETTINGS',
		'NAME' => getMessage('RS.MASTER.FILTER_SCROLL_PROPS'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'VALUES' => $defaultValue + $arAllPropList,
		'DEFAULT' => '-',
	);

	$arTemplateParameters['FILTER_SEARCH_PROPS'] = array(
		'PARENT' => 'FILTER_SETTINGS',
		'NAME' => getMessage('RS.MASTER.FILTER_SEARCH_PROPS'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'VALUES' => $defaultValue + $arAllPropList,
		'DEFAULT' => '-',
	);
/*
	$arTemplateParameters['FILTER_TOP_PROPS'] = array(
		'PARENT' => 'FILTER_SETTINGS',
		'NAME' => Loc::getMessage('RS.MASTER.FILTER_TOP_PROPS'),
		'TYPE' => 'LIST',
		'VALUES' => $defaultValue + $arAllPropList,
		'MULTIPLE' => 'Y',
		'DEFAULT' => '-',
	);
    $arTemplateParameters["FILTER_VIEW_MODE"] = array(
        "PARENT" => "FILTER_SETTINGS",
        "NAME" => GetMessage('CPT_BC_FILTER_VIEW_MODE'),
        "TYPE" => "LIST",
        "VALUES" => $arFilterViewModeList,
        "DEFAULT" => "VERTICAL",
        "HIDDEN" => (!isset($arCurrentValues['USE_FILTER']) || 'N' == $arCurrentValues['USE_FILTER'])
    );
    $arTemplateParameters["FILTER_HIDE_ON_MOBILE"] = array(
        "PARENT" => "FILTER_SETTINGS",
        "NAME" => GetMessage("CPT_BC_FILTER_HIDE_ON_MOBILE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",
    );
*/
	$arTemplateParameters["INSTANT_RELOAD"] = array(
		"PARENT" => "FILTER_SETTINGS",
		"NAME" => GetMessage("CPT_BC_INSTANT_RELOAD"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	);

	$arTemplateParameters['FEATURE_FILTER_USER_FIELDS'] = array(
		'PARENT' => 'FILTER_SETTINGS',
		'NAME' => GetMessage('RS.MASTER.FEATURE_FILTER_USER_FIELDS'),
		'TYPE' => 'LIST',
		'VALUES' => array_merge($defaultValue, $arProperty_UF),
		'DEFAULT' => '-',
	);
}

$arTemplateParameters['LINK_BTN_ASK'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => Loc::getMessage('RS.MASTER.LINK_BTN_ASK'),
	'TYPE' => 'STRING',
	'DEFAULT' => '/include/forms/product_ask/?element_id=#ELEMENT_ID#',
);

$arTemplateParameters['MESS_BTN_ASK'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => Loc::getMessage('RS.MASTER.MESS_BTN_ASK_QUESTION'),
	'TYPE' => 'STRING',
	'DEFAULT' => Loc::getMessage('RS.MASTER.MESS_BTN_ASK_QUESTION_DEFAULT')
);

if (is_array($arCurrentValues['DETAIL_PRODUCT_INFO_BLOCK']) && in_array('cheaper', $arCurrentValues['DETAIL_PRODUCT_INFO_BLOCK'])) {
    $arTemplateParameters['CHEAPER_FORM_URL'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.MASTER.CHEAPER_FORM_URL'),
        'TYPE' => 'STRING',
        'DEFAULT' => '/include/forms/cheaper/?element_id=#ELEMENT_ID#',
    );
}


if (is_array($arCurrentValues['DETAIL_PRODUCT_INFO_BLOCK']) && in_array('delivery', $arCurrentValues['DETAIL_PRODUCT_INFO_BLOCK'])) {

    $arTemplateParameters['DETAIL_DELIVERY_PAYMENT_INFO'] = array(
        'PARENT' => 'DETAIL_SETTINGS',
        'NAME' => Loc::getMessage('RS.MASTER.DETAIL_DELIVERY_PAYMENT_INFO'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N',
        'REFRESH' => 'Y',
    );

    if (isset($arCurrentValues['DETAIL_DELIVERY_PAYMENT_INFO']) && 'Y' == $arCurrentValues['DETAIL_DELIVERY_PAYMENT_INFO']) {
        $arTemplateParameters['DETAIL_DELIVERY_LINK'] = array(
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('RS.MASTER.DETAIL_DELIVERY_LINK'),
            'TYPE' => 'STRING',
            'DEFAULT' => '/delivery/',
        );
        $arTemplateParameters['DETAIL_PAYMENT_LINK'] = array(
            'PARENT' => 'DETAIL_SETTINGS',
            'NAME' => Loc::getMessage('RS.MASTER.DETAIL_PAYMENT_LINK'),
            'TYPE' => 'STRING',
            'DEFAULT' => '/payment/',
        );
    }
}

if ($arCurrentValues['USE_PRODUCT_QUANTITY'] == 'Y') {
    $arTemplateParameters['LIST_USE_PRODUCT_QUANTITY'] = $arTemplateParameters['USE_PRODUCT_QUANTITY'];
    $arTemplateParameters['DETAIL_USE_PRODUCT_QUANTITY'] = $arTemplateParameters['USE_PRODUCT_QUANTITY'];
    $arTemplateParameters['LIST_USE_PRODUCT_QUANTITY']['NAME'] = Loc::getMessage('RS.MASTER.LIST_USE_PRODUCT_QUANTITY');
    $arTemplateParameters['DETAIL_USE_PRODUCT_QUANTITY']['NAME'] = Loc::getMessage('RS.MASTER.LIST_USE_PRODUCT_QUANTITY');
}

if (ModuleManager::isModuleInstalled('redsign.favorite')) {
	$arTemplateParameters['USE_FAVORITE'] = array(
		'NAME' => getMessage('RS.MASTER.USE_FAVORITE'),
		'TYPE' => 'CHECKBOX',
		'MULTIPLE' => 'N',
		'VALUE' => 'Y',
		'DEFAULT' =>'N',
		'REFRESH'=> 'Y',
	);

	if ($arCurrentValues['USE_FAVORITE'] == 'Y') {
		$arTemplateParameters['FAVORITE_COUNT_PROP'] = array(
			'NAME' => getMessage('RS.MASTER.FAVORITE_COUNT_PROP'),
			'TYPE' => 'LIST',
			'VALUES' => $defaultValue + $arAllPropList,
			'DEFAULT' => '-',
		);
        
        $arTemplateParameters['MESS_BTN_FAVORITE'] = array(
            'PARENT' => 'VISUAL',
            'NAME' => getMessage('RS.MASTER.MESS_BTN_FAVORITE'),
            'TYPE' => 'STRING',
            'DEFAULT' => getMessage('RS.MASTER.MESS_BTN_FAVORITE_DEFAULT'),
        );
	}
}

if (ModuleManager::isModuleInstalled('redsign.devcom')) {

	$arTemplateParameters['USE_SORTER'] = array(
		'NAME' => GetMessage('RS.MASTER.USE_SORTER'),
		'TYPE' => 'CHECKBOX',
		'VALUE' => 'Y',
		'REFRESH' => 'Y',
		'DEFAULT' => 'Y',
	);

	if ($arCurrentValues['USE_SORTER'] == 'Y') {

		$arTemplateParameters['SORTER_TEMPLATE'] = array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => Loc::getMessage('RS.MASTER.SORTER_TEMPLATE'),
			'TYPE' => 'LIST',
			'VALUES' => $arRCSTemplates,
			'DEFAULT' => in_array('master', $arRCSTemplates) ? 'master' : $arRCSTemplates[0],
			'REFRESH' => 'Y',
		);

		$arTemplateParameters['SORTER_ACTION_PARAM_NAME'] = array(
			'NAME' => GetMessage('ALFA_MSG_ACTION_PARAM_NAME'),
			'TYPE' => 'STRING',
			// 'PARENT' => 'BASE',
			'DEFAULT' => 'alfaction',
		);
		
		$arTemplateParameters['SORTER_ACTION_PARAM_VALUE'] = array(
			'NAME' => GetMessage('ALFA_MSG_ACTION_PARAM_VALUE'),
			'TYPE' => 'STRING',
			// 'PARENT' => 'BASE',
			'DEFAULT' => 'alfavalue',
		);

		$arTemplateParameters['SORTER_CHOSE_TEMPLATES_SHOW'] = array(
			'NAME' => GetMessage('ALFA_MSG_CHOSE_TEMPLATES_SHOW'),
			'TYPE' => 'CHECKBOX',
			'VALUE' => 'Y',
			// 'PARENT' => 'ALFA_GR_TEMPLATES',
			'REFRESH' => 'Y',
			'DEFAULT' => 'Y',
		);

		if ($arCurrentValues['SORTER_CHOSE_TEMPLATES_SHOW'] == 'Y') {

			$arTemplateParameters['SORTER_CNT_TEMPLATES'] = array(
				// 'PARENT' => 'ALFA_GR_TEMPLATES',
				'NAME' => GetMessage('ALFA_MSG_CNT_TEMPLATES'),
				'TYPE' => 'STRING',
				'REFRESH' => 'Y',
				'DEFAULT' => '2',
			);

			$arSorterView = array(
				array(
					'NAME' => GetMessage('ALFA_MSG_CNT_TEMPLATES_SOME_NAME_0'),
					'TEMPLATE' => 'vid-2'
				),
				array(
					'NAME' => GetMessage('ALFA_MSG_CNT_TEMPLATES_SOME_NAME_1'),
					'TEMPLATE' => 'vid-1'
				),
			);

			for ($i = 0; $i < $arCurrentValues['SORTER_CNT_TEMPLATES']; $i++) {

				$arTemplateParameters['SORTER_CNT_TEMPLATES_'.$i] = array(
					// 'PARENT' => 'ALFA_GR_TEMPLATES_SOME',
					'NAME' => GetMessage('ALFA_MSG_CNT_TEMPLATES_SOME_NAME_').' #'.($i+1),
					'TYPE' => 'STRING',
					'DEFAULT' => isset($arSorterView[$i]) ? $arSorterView[$i]['NAME'] : $arSorterView[0]['NAME'],
				);

				$arTemplateParameters['SORTER_CNT_TEMPLATES_NAME_'.$i] = array(
					// 'PARENT' => 'ALFA_GR_TEMPLATES_SOME',
					'NAME' => GetMessage('ALFA_MSG_CNT_TEMPLATES_SOME_TMPL_NAME_').' #'.($i+1),
					'TYPE' => 'STRING',
					'DEFAULT' => isset($arSorterView[$i]) ? $arSorterView[$i]['TEMPLATE'] : $arSorterView[0]['TEMPLATE'],
				);

			}

			$arTemplateParameters['SORTER_DEFAULT_TEMPLATE'] = array(
				// 'PARENT' => 'ALFA_GR_TEMPLATES',
				'NAME' => GetMessage('ALFA_MSG_DEFAULT_TEMPLATE'),
				'TYPE' => 'STRING',
				'REFRESH' => 'N',
			);
		}

		$arTemplateParameters['SORTER_SORT_BY_SHOW'] = array(
			'NAME' => GetMessage('ALFA_MSG_SORT_BY_SHOW'),
			'TYPE' => 'CHECKBOX',
			'VALUE' => 'Y',
			// 'PARENT' => 'ALFA_GR_SORTINGS',
			'REFRESH' => 'Y',
			'DEFAULT' => 'Y',
		);

		if ($arCurrentValues['SORTER_SORT_BY_SHOW'] == 'Y') {

			$arTemplateParameters['SORTER_SORT_BY_NAME'] = array(
				// 'PARENT' => 'ALFA_GR_SORTINGS',
				'NAME' => GetMessage('ALFA_MSG_SORT_BY'),
				'TYPE' => 'LIST',
				'VALUES' => array(
					'sort' => GetMessage('ALFA_MSG_SORT_BY_DATE'),
					'name' => GetMessage('ALFA_MSG_SORT_BY_NAME'),
					'catalog_price_1' => GetMessage('ALFA_MSG_SORT_BY_PRICE'),
				),
				'VALUE' => 'Y',
				'MULTIPLE' => 'Y',
				'ADDITIONAL_VALUES' => 'Y',
			);

			$arTemplateParameters['SORTER_SORT_BY_DEFAULT'] = array(
				// 'PARENT' => 'ALFA_GR_SORTINGS',
				'NAME' => GetMessage('ALFA_MSG_SORT_BY_DEFAULT'),
				'TYPE' => 'LIST',
				'VALUES' => array(
					'sort_asc' => GetMessage('ALFA_MSG_SORT_BY_DATE1'),
					'sort_desc' => GetMessage('ALFA_MSG_SORT_BY_DATE2'),
					'name_asc' => GetMessage('ALFA_MSG_SORT_BY_NAME1'),
					'name_desc' => GetMessage('ALFA_MSG_SORT_BY_NAME2'),
					'catalog_price_1_asc' => GetMessage('ALFA_MSG_SORT_BY_PRICE1'),
					'catalog_price_1_desc' => GetMessage('ALFA_MSG_SORT_BY_PRICE2'),
				),
				'VALUE' => 'Y',
				'MULTIPLE' => 'N',
			);
		}

		$arTemplateParameters['SORTER_OUTPUT_OF_SHOW'] = array(
			'NAME' => GetMessage('ALFA_MSG_OUTPUT_OF_SHOW'),
			'TYPE' => 'CHECKBOX',
			'VALUE' => 'Y',
			// 'PARENT' => 'ALFA_GR_OUTPUT',
			'REFRESH' => 'Y',
			'DEFAULT' => 'Y',
		);

		if ($arCurrentValues['SORTER_OUTPUT_OF_SHOW'] == 'Y') {

			$arOutputVars = array(
				'5' => '5',
				'10' => '10',
				'15' => '15',
				'20' => '20',
				'25' => '25',
				'50' => '50',
				'75' => '75',
				'100' => '100',
			);
			$arTemplateParameters['SORTER_OUTPUT_OF'] = array(
				// 'PARENT' => 'ALFA_GR_OUTPUT',
				'NAME' => GetMessage('ALFA_MSG_OUTPUT_OF'),
				'TYPE' => 'LIST',
				'MULTIPLE' => 'Y',
				'VALUES' => $arOutputVars,
				'DEFAULT' => array_slice($arOutputVars, 0, 3),
				'ADDITIONAL_VALUES' => 'Y',
				'REFRESH' => 'Y',
			);

			$arCurrentValues['SORTER_OUTPUT_OF'] = array_values(
				array_filter(
					$arCurrentValues['SORTER_OUTPUT_OF'],
					function($element) {
						return !empty($element);

					}
				)
			);

			$arOutputVarsDefault = array();
			if (is_array($arCurrentValues['SORTER_OUTPUT_OF']) && count($arCurrentValues['SORTER_OUTPUT_OF']) > 0) {
				foreach ($arCurrentValues['SORTER_OUTPUT_OF'] as $val) {
					$arOutputVarsDefault[$val] = $val;
				}
				unset($val);
			}

			$arTemplateParameters['SORTER_OUTPUT_OF_DEFAULT'] = array(
				// 'PARENT' => 'ALFA_GR_OUTPUT',
				'NAME' => GetMessage('ALFA_MSG_OUTPUT_OF_DEFAULT'),
				'TYPE' => 'LIST',
				'MULTIPLE' => 'N',
				'VALUES' => $arOutputVarsDefault,
				'DEFAULT' => count($arOutputVarsDefault) > 0 ? reset($arOutputVarsDefault) : '-',
			);
/*
			$arTemplateParameters['SORTER_OUTPUT_OF_SHOW_ALL'] = array(
				// 'PARENT' => 'ALFA_GR_OUTPUT',
				'NAME' => GetMessage('ALFA_MSG_OUTPUT_OF_SHOW_ALL'),
				'TYPE' => 'CHECKBOX',
				'VALUE' => 'Y',
			);
*/
		}
	}
}
