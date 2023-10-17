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

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arAllPropList = array();
$arAllPropListLand = array();
$arListPropList = array();
$arHighloadPropList = array();
$arFilePropList = $defaultValue;

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
}

$defaultValue = array('-' => GetMessage('CP_BC_TPL_PROP_EMPTY'));

$arSectionDescrValues = array(
	'-' => getMessage('CP_BC_TPL_PROP_EMPTY'),
	'top' => getMessage('RS.MASTER.SHOW_SECTION_DESCRIPTION_TOP'),
	'bottom' => getMessage('RS.MASTER.SHOW_SECTION_DESCRIPTION_BOTTOM'),
);

$documentRoot = Loader::getDocumentRoot();

$arTemplateParameters = array(
/*
	"DISPLAY_DATE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_DATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
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
*/
	'BRAND_PROP' => array(
		'PARENT' => 'DATA_SOURCE',
		'NAME' => GetMessage('RS.MASTER.BRAND_PROP'),
		'TYPE' => 'LIST',
		'VALUES' => $defaultValue + $arHighloadPropList,
		"MULTIPLE" => "N",
		'DEFAULT' => '-',
		"REFRESH" => "Y",
	),
);

if (isset($arCurrentValues['BRAND_PROP']) && $arCurrentValues['BRAND_PROP'] != '-') {
	$arTemplateParameters['CATALOG_IBLOCK_TYPE'] = array(
		"PARENT" => "DATA_SOURCE",
		"NAME" => GetMessage("RS.MASTER.CATALOG_IBLOCK_TYPE"),
		"TYPE" => "LIST",
		"VALUES" => $arIBlockType,
		"REFRESH" => "Y",
	);
	
	$arIBlock = array();
	$iblockFilter = (
		!empty($arCurrentValues['CATALOG_IBLOCK_TYPE'])
		? array('TYPE' => $arCurrentValues['CATALOG_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
		: array('ACTIVE' => 'Y')
	);
	$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
	while ($arr = $rsIBlock->Fetch())
		$arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
	unset($arr, $rsIBlock, $iblockFilter);

	$arTemplateParameters['CATALOG_IBLOCK_ID'] = array(
		'PARENT' => 'DATA_SOURCE',
		'NAME' => getMessage('RS.MASTER.CATALOG_IBLOCK_ID'),
		'TYPE' => 'LIST',
		'VALUES' => $arIBlock,
		'DEFAULT' => '',
		'REFRESH' => 'Y',
	);

	$arAllCatalogPropList = array();
	$arListCatalogPropList = array();
	$arHighloadCatalogPropList = array();
	$arCatalogProperty_X = array();
	$arFileCatalogPropList = $defaultValue;
	
	$arCSTemplates = $arCETemplates = $arRCSTemplates = array();

	if (Loader::includeModule('redsign.master')) {
		$arCSTemplates = ParametersUtils::getComponentTemplateList('bitrix:catalog.section');
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

	if (intval($arCurrentValues['CATALOG_IBLOCK_ID']) > 0) {

		$arSKU = false;
		$boolSKU = false;
		$boolIsCatalog = false;

		if ($boolCatalog && (isset($arCurrentValues['CATALOG_IBLOCK_ID']) && (int)$arCurrentValues['CATALOG_IBLOCK_ID']) > 0)
		{
			$arSKU = CCatalogSKU::GetInfoByProductIBlock($arCurrentValues['CATALOG_IBLOCK_ID']);
			$boolSKU = !empty($arSKU) && is_array($arSKU);
			
			$arCatalog = CCatalog::GetByIDExt($arCurrentValues['CATALOG_IBLOCK_ID']);
			if (false !== $arCatalog) {
				$boolIsCatalog = true;
			}
		}
		
		$rsProps = CIBlockProperty::GetList(
			array('SORT' => 'ASC', 'ID' => 'ASC'),
			array('IBLOCK_ID' => $arCurrentValues['CATALOG_IBLOCK_ID'], 'ACTIVE' => 'Y')
		);
		while ($arProp = $rsProps->Fetch())
		{
			$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
			if ('' == $arProp['CODE'])
			{
				$arProp['CODE'] = $arProp['ID'];
			}

			$arAllCatalogPropList[$arProp['CODE']] = $strPropName;

			if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE)
			{
				$arFileCatalogPropList[$arProp['CODE']] = $strPropName;
			}

			if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST)
			{
				$arListCatalogPropList[$arProp['CODE']] = $strPropName;
			}

			if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
			{
				$arHighloadCatalogPropList[$arProp['CODE']] = $strPropName;
			}
			
			if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_NUMBER)
			{
				$arCatalogNumPropList[$arProp['CODE']] = $strPropName;
			}
			
			if ($property['PROPERTY_TYPE'] != Iblock\PropertyTable::TYPE_FILE)
			{
				if ($property['MULTIPLE'] == 'Y')
					$arCatalogProperty_X[$propertyCode] = $propertyName;
				elseif ($property['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST)
					$arCatalogProperty_X[$propertyCode] = $propertyName;
				elseif ($property['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_ELEMENT && (int)$property['LINK_IBLOCK_ID'] > 0)
					$arCatalogProperty_X[$propertyCode] = $propertyName;
			}
		}
		
		global $USER_FIELD_MANAGER;
		$arProperty_UF = array();
		$arUserFields = $USER_FIELD_MANAGER->GetUserFields('IBLOCK_'.$arCurrentValues['CATALOG_IBLOCK_ID'].'_SECTION', 0, LANGUAGE_ID);
		foreach($arUserFields as $FIELD_NAME=>$arUserField)
		{
			$arUserField['LIST_COLUMN_LABEL'] = (string)$arUserField['LIST_COLUMN_LABEL'];
			$arProperty_UF[$FIELD_NAME] = $arUserField['LIST_COLUMN_LABEL'] ? '['.$FIELD_NAME.'] '.$arUserField['LIST_COLUMN_LABEL'] : $FIELD_NAME;
		}
		unset($arUserFields);


		$arSort = CIBlockParameters::GetElementSortFields(
			array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
			array('KEY_LOWERCASE' => 'Y')
		);

		$arPrice = $arPriceById = array();
		if ($boolIsCatalog)
		{
			$arSort = array_merge($arSort, CCatalogIBlockParameters::GetCatalogSortFields());
			$arPrice = CCatalogIBlockParameters::getPriceTypesList();
			$arPriceById = CCatalogIBlockParameters::getPriceTypesList(true);
			
		}
		else
		{
			$arPrice = $arCatalogNumPropList;
		}

		$arAscDesc = array(
			"asc" => GetMessage("IBLOCK_SORT_ASC"),
			"desc" => GetMessage("IBLOCK_SORT_DESC"),
		);
		
		$arTemplateParameters["PRICE_CODE"] = array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("IBLOCK_PRICE_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arPrice,
		);
		
		if ($boolIsCatalog)
		{
			$arTemplateParameters["USE_PRICE_COUNT"] = array(
				"PARENT" => "PRICES",
				"NAME" => GetMessage("IBLOCK_USE_PRICE_COUNT"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "N",
			);
			$arTemplateParameters["SHOW_PRICE_COUNT"] = array(
				"PARENT" => "PRICES",
				"NAME" => GetMessage("IBLOCK_SHOW_PRICE_COUNT"),
				"TYPE" => "STRING",
				"DEFAULT" => "1"
			);
			$arTemplateParameters["PRICE_VAT_INCLUDE"] = array(
				"PARENT" => "PRICES",
				"NAME" => GetMessage("IBLOCK_VAT_INCLUDE"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "Y",
			);
			$arTemplateParameters["PRICE_VAT_SHOW_VALUE"] = array(
				"PARENT" => "PRICES",
				"NAME" => GetMessage("IBLOCK_VAT_SHOW_VALUE"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "N",
			);
		}
		$arTemplateParameters["BASKET_URL"] = array(
			"PARENT" => "BASKET",
			"NAME" => GetMessage("IBLOCK_BASKET_URL"),
			"TYPE" => "STRING",
			"DEFAULT" => "/personal/cart/",
		);
		$arTemplateParameters["ACTION_VARIABLE"] = array(
			"PARENT" => "ACTION_SETTINGS",
			"NAME"		=> GetMessage("IBLOCK_ACTION_VARIABLE"),
			"TYPE"		=> "STRING",
			"DEFAULT"	=> "action"
		);
		$arTemplateParameters["PRODUCT_ID_VARIABLE"] = array(
			"PARENT" => "ACTION_SETTINGS",
			"NAME"		=> GetMessage("IBLOCK_PRODUCT_ID_VARIABLE"),
			"TYPE"		=> "STRING",
			"DEFAULT"	=> "id"
		);
		$arTemplateParameters["USE_PRODUCT_QUANTITY"] = array(
			"PARENT" => "BASKET",
			"NAME" => GetMessage("CP_BC_USE_PRODUCT_QUANTITY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		);
		$arTemplateParameters["PRODUCT_QUANTITY_VARIABLE"] = array(
			"PARENT" => "BASKET",
			"NAME" => GetMessage("CP_BC_PRODUCT_QUANTITY_VARIABLE"),
			"TYPE" => "STRING",
			"DEFAULT" => "quantity",
			"HIDDEN" => (isset($arCurrentValues['USE_PRODUCT_QUANTITY']) && $arCurrentValues['USE_PRODUCT_QUANTITY'] == 'Y' ? 'N' : 'Y')
		);
		$arTemplateParameters["USE_FILTER"] = array(
			"PARENT" => "FILTER_SETTINGS",
			"NAME" => GetMessage("T_IBLOCK_DESC_USE_FILTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		);
		$arTemplateParameters["USE_COMPARE"] = array(
			"PARENT" => "COMPARE_SETTINGS",
			"NAME" => GetMessage("T_IBLOCK_DESC_USE_COMPARE_EXT"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		);
/*
		$arTemplateParameters["SECTION_COUNT_ELEMENTS"] = array(
			"PARENT" => "SECTIONS_SETTINGS",
			"NAME" => GetMessage('CP_BC_SECTION_COUNT_ELEMENTS'),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		);
		$arTemplateParameters["SECTION_TOP_DEPTH"] = array(
			"PARENT" => "SECTIONS_SETTINGS",
			"NAME" => GetMessage('CP_BC_SECTION_TOP_DEPTH'),
			"TYPE" => "STRING",
			"DEFAULT" => "2",
		);
*/
		$arTemplateParameters["USE_MAIN_ELEMENT_SECTION"] = array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BC_USE_MAIN_ELEMENT_SECTION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		);
/*
		$arTemplateParameters["SECTION_BACKGROUND_IMAGE"] = array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("CP_BC_BACKGROUND_IMAGE"),
			"TYPE" => "LIST",
			"DEFAULT" => "-",
			"MULTIPLE" => "N",
			"VALUES" => array_merge(array("-"=>" "), $arProperty_UF)
		);
		$arTemplateParameters["SECTION_ID_VARIABLE"] = array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME"		=> GetMessage("IBLOCK_SECTION_ID_VARIABLE"),
			"TYPE"		=> "STRING",
			"DEFAULT"	=> "SECTION_ID"
		);
*/
		$arTemplateParameters["SHOW_DEACTIVATED"] = array(
			"PARENT" => "DETAIL_SETTINGS",
			"NAME" => GetMessage('CP_BC_SHOW_DEACTIVATED'),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"
		);
/*
		$arTemplateParameters["SET_LAST_MODIFIED"] = array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BC_SET_LAST_MODIFIED"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		);
		$arTemplateParameters["ADD_SECTIONS_CHAIN"] = array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BC_ADD_SECTIONS_CHAIN"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y"
		);
*/

		if($arCurrentValues["USE_COMPARE"]=="Y")
		{
			$arTemplateParameters["COMPARE_NAME"] = array(
				"PARENT" => "COMPARE_SETTINGS",
				"NAME" => GetMessage("IBLOCK_COMPARE_NAME"),
				"TYPE" => "STRING",
				"DEFAULT" => "CATALOG_COMPARE_LIST"
			);
		}

		if ($boolIsCatalog)
		{
			$arTemplateParameters["ADD_PROPERTIES_TO_BASKET"] = array(
				"PARENT" => "BASKET",
				"NAME" => GetMessage("CP_BC_ADD_PROPERTIES_TO_BASKET"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "Y",
				"REFRESH" => "Y"
			);
			$arTemplateParameters["PRODUCT_PROPS_VARIABLE"] = array(
				"PARENT" => "BASKET",
				"NAME" => GetMessage("CP_BC_PRODUCT_PROPS_VARIABLE"),
				"TYPE" => "STRING",
				"DEFAULT" => "prop",
				"HIDDEN" => (isset($arCurrentValues['ADD_PROPERTIES_TO_BASKET']) && $arCurrentValues['ADD_PROPERTIES_TO_BASKET'] == 'N' ? 'Y' : 'N')
			);
			$arTemplateParameters["PARTIAL_PRODUCT_PROPERTIES"] = array(
				"PARENT" => "BASKET",
				"NAME" => GetMessage("CP_BC_PARTIAL_PRODUCT_PROPERTIES"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "N",
				"HIDDEN" => (isset($arCurrentValues['ADD_PROPERTIES_TO_BASKET']) && $arCurrentValues['ADD_PROPERTIES_TO_BASKET'] == 'N' ? 'Y' : 'N')
			);
			$arTemplateParameters["PRODUCT_PROPERTIES"] = array(
				"PARENT" => "BASKET",
				"NAME" => GetMessage("CP_BC_PRODUCT_PROPERTIES"),
				"TYPE" => "LIST",
				"MULTIPLE" => "Y",
				"VALUES" => $arCatalogProperty_X,
				"HIDDEN" => (isset($arCurrentValues['ADD_PROPERTIES_TO_BASKET']) && $arCurrentValues['ADD_PROPERTIES_TO_BASKET'] == 'N' ? 'Y' : 'N')
			);

			$arTemplateParameters['HIDE_NOT_AVAILABLE'] = array(
				'PARENT' => 'DATA_SOURCE',
				'NAME' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_EXT2'),
				'TYPE' => 'LIST',
				'DEFAULT' => 'N',
				'VALUES' => array(
					'Y' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_HIDE'),
					'L' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_LAST'),
					'N' => GetMessage('CP_BC_HIDE_NOT_AVAILABLE_SHOW')
				),
				'ADDITIONAL_VALUES' => 'N'
			);

			$arTemplateParameters['CONVERT_CURRENCY'] = array(
				'PARENT' => 'PRICES',
				'NAME' => GetMessage('CP_BC_CONVERT_CURRENCY'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'N',
				'REFRESH' => 'Y',
			);

			if (isset($arCurrentValues['CONVERT_CURRENCY']) && $arCurrentValues['CONVERT_CURRENCY'] == 'Y')
			{
				$arTemplateParameters['CURRENCY_ID'] = array(
					'PARENT' => 'PRICES',
					'NAME' => GetMessage('CP_BC_CURRENCY_ID'),
					'TYPE' => 'LIST',
					'VALUES' => Currency\CurrencyManager::getCurrencyList(),
					'DEFAULT' => Currency\CurrencyManager::getBaseCurrency(),
					"ADDITIONAL_VALUES" => "Y",
				);
			}
		}

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
			'BIG_DATA' => 'N',
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
				'VALUES' => $defaultValue + $arListCatalogPropList
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
			'VALUES' => $arFileCatalogPropList
		);
		$arTemplateParameters['LABEL_PROP'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_LABEL_PROP'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'Y',
			'VALUES' => $arListCatalogPropList
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
				if (isset($arListCatalogPropList[$name]))
				{
					$selected[$name] = $arListCatalogPropList[$name];
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
		}

		if ($boolSku)
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
		
		if (ModuleManager::isModuleInstalled("highloadblock"))
		{
			$arTemplateParameters['CATALOG_BRAND_PROP'] = array(
				'PARENT' => 'DATA_SOURCE',
				'NAME' => getMessage('RS.MASTER.CATALOG_BRAND_PROP'),
				'TYPE' => 'LIST',
				'VALUES' => $defaultValue + $arHighloadCatalogPropList,
				'DEFAULT' => '-',
			);

			$arTemplateParameters['CATALOG_PROPERTY_CODE'] = array(
				"PARENT" => "LIST_SETTINGS",
				"NAME" => GetMessage("IBLOCK_PROPERTY"),
				"TYPE" => "LIST",
				"MULTIPLE" => "Y",
				'REFRESH' => isset($templateProperties['LIST_PROPERTY_CODE_MOBILE']) ? 'Y' : 'N',
				"ADDITIONAL_VALUES" => "Y",
				"VALUES" => $arAllCatalogPropList,
				'DEFAULT' => '',
			);
		}
		
		if (!$boolIsCatalog && $boolLightBasket)
		{
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

		}
		else
		{
			
			$arTemplateParameters['CATALOG_PRICE_PROP'] = array(
				'PARENT' => 'VISUAL',
				'NAME' => Loc::getMessage('RS.MASTER.CATALOG_PRICE_PROP'),
				'TYPE' => 'LIST',
				'VALUES' => $defaultValue + $arAllCatalogPropList,
				'DEFAULT' => '-',
			);
			$arTemplateParameters['CATALOG_DISCOUNT_PROP'] = array(
				'PARENT' => 'VISUAL',
				'NAME' => Loc::getMessage('RS.MASTER.CATALOG_DISCOUNT_PROP'),
				'TYPE' => 'LIST',
				'VALUES' => $defaultValue + $arAllCatalogPropList,
				'DEFAULT' => '-',
			);

			$arTemplateParameters['CATALOG_CURRENCY_PROP'] = array(
				'PARENT' => 'VISUAL',
				'NAME' => Loc::getMessage('RS.MASTER.CATALOG_CURRENCY_PROP'),
				'TYPE' => 'LIST',
				'VALUES' => $defaultValue + $arAllCatalogPropList,
				'DEFAULT' => '-',
			);

			$arTemplateParameters['CATALOG_PRICE_DECIMALS'] = array(
				'PARENT' => 'VISUAL',
				'NAME' => Loc::getMessage('RS.MASTER.CATALOG_PRICE_DECIMALS'),
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
			'VALUES' => $addToBasketActions,
			'DEFAULT' => 'ADD',
			'REFRESH' => 'N',
			//'HIDDEN' => (!$useCommonSettingsBasketPopup ? 'N' : 'Y')
		);
		
		$arTemplateParameters['CATALOG_ARTNUMBER_PROP'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => Loc::getMessage('RS.MASTER.CATALOG_ARTNUMBER_PROP'),
			'TYPE' => 'LIST',
			'VALUES' => $defaultValue + $arAllCatalogPropList,
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

		$arTemplateParameters['SECTIONS_SHOW_SIDEBAR'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => getMessage('RS.MASTER.SHOW_SIDEBAR'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
			'SORT' => 800
		);

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
				'VALUES' => $defaultValue + $arAllCatalogPropList
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
/*
			ParametersUtils::addCommonParameters(
				$arTemplateParameters,
				$arCurrentValues,
				array(
					'share',
				)
			);

			unset($arTemplateParameters['ADD_CONTAINER']);
*/
		}
/*
		if ($arCurrentValues['USE_SHARE'] == 'Y') {
			//$arTemplateParameters['LIST_SOCIAL_SERVICES'] = array_merge(array('PARENT' => 'LIST_SETTINGS'), $arTemplateParameters['SOCIAL_SERVICES']);
			$arTemplateParameters['DETAIL_SOCIAL_SERVICES'] = array_merge(array('PARENT' => 'DETAIL_SETTINGS'), $arTemplateParameters['SOCIAL_SERVICES']);
			unset($arTemplateParameters['SOCIAL_SERVICES']);
		}
*/

		if ($arCurrentValues['USE_FILTER'] == 'Y')
		{

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
				'VALUES' => $defaultValue + $arAllCatalogPropList,
				'DEFAULT' => '-',
			);

			$arTemplateParameters['FILTER_SEARCH_PROPS'] = array(
				'PARENT' => 'FILTER_SETTINGS',
				'NAME' => getMessage('RS.MASTER.FILTER_SEARCH_PROPS'),
				'TYPE' => 'LIST',
				'MULTIPLE' => 'Y',
				'VALUES' => $defaultValue + $arAllCatalogPropList,
				'DEFAULT' => '-',
			);
/*
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
		}
/*
		if (ModuleManager::isModuleInstalled('redsign.favorite'))
		{
			$arTemplateParameters['USE_FAVORITE'] = array(
				'NAME' => getMessage('RS.MASTER.USE_FAVORITE'),
				'TYPE' => 'CHECKBOX',
				'MULTIPLE' => 'N',
				'VALUE' => 'Y',
				'DEFAULT' =>'N',
				'REFRESH'=> 'Y',
			);

			if ($arCurrentValues['USE_FAVORITE'] == 'Y')
			{
				$arTemplateParameters['FAVORITE_COUNT_PROP'] = array(
					'NAME' => getMessage('RS.MASTER.FAVORITE_COUNT_PROP'),
					'TYPE' => 'LIST',
					'VALUES' => $defaultValue + $arAllCatalogPropList,
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

		$arTemplateParameters["PREVIEW_TRUNCATE_LEN"] = array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("RS.MASTER.PREVIEW_TRUNCATE_LEN"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		);
*/
		if (ModuleManager::isModuleInstalled('redsign.devcom'))
		{
			$arTemplateParameters['USE_SORTER'] = array(
				'NAME' => GetMessage('RS.MASTER.USE_SORTER'),
				'TYPE' => 'CHECKBOX',
				'VALUE' => 'Y',
				'REFRESH' => 'Y',
				'DEFAULT' => 'Y',
			);

			if ($arCurrentValues['USE_SORTER'] == 'Y')
			{
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

				if ($arCurrentValues['SORTER_CHOSE_TEMPLATES_SHOW'] == 'Y')
				{
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

					for ($i = 0; $i < $arCurrentValues['SORTER_CNT_TEMPLATES']; $i++)
					{
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

				if ($arCurrentValues['SORTER_SORT_BY_SHOW'] == 'Y')
				{
					$arSortByValues = array(
						"sort" => GetMessage('ALFA_MSG_SORT_BY_FIELD_SORT'),
						"name" => GetMessage('ALFA_MSG_SORT_BY_FIELD_NAME'),
					);

					if (is_array($arPriceById) && count($arPriceById) > 0)
					{
						foreach ($arPriceById as $id => $price)
						{
							$arSortByValues['catalog_price_scale_'.$id] = $price;
						}
						unset($id, $price);
					}

					$arTemplateParameters['SORTER_SORT_BY_NAME'] = array(
						// 'PARENT' => 'ALFA_GR_SORTINGS',
						'NAME' => GetMessage('ALFA_MSG_SORT_BY'),
						'TYPE' => 'LIST',
						'VALUES' => $arSortByValues,
						'MULTIPLE' => 'Y',
						'ADDITIONAL_VALUES' => 'Y',
						'REFRESH' => 'Y',
					);

					$selected = array();

					foreach ($arCurrentValues['SORTER_SORT_BY_NAME'] as $code)
					{
						if (strlen($code) > 0)
						{
							if (isset($arSortByValues[$code]))
							{
								$selected[$code] = $arSortByValues[$code];
							}
							else
							{
								$selected[$code] = $code;
							}
						}
					}
		
					$arSortByDefaultValues = array();
		
					foreach ($selected as $code => $name)
					{
						$arSortByDefaultValues[$code.'_asc'] = GetMessage(
							'ALFA_MSG_SORT_BY_FIELD_DIRECTION',
							array(
								'#NAME#' => $name,
								'#DIRECTION#' => GetMessage('ALFA_MSG_SORT_DIRECTION_ASC')
							)
						);
						$arSortByDefaultValues[$code.'_desc'] = GetMessage(
							'ALFA_MSG_SORT_BY_FIELD_DIRECTION',
							array(
								'#NAME#' => $name,
								'#DIRECTION#' => GetMessage('ALFA_MSG_SORT_DIRECTION_DESC')
							)
						);
					}

					$arTemplateParameters['SORTER_SORT_BY_DEFAULT'] = array(
						// 'PARENT' => 'ALFA_GR_SORTINGS',
						'NAME' => GetMessage('ALFA_MSG_SORT_BY_DEFAULT'),
						'TYPE' => 'LIST',
						'VALUES' => $arSortByDefaultValues,
						'VALUE' => 'Y',
						'MULTIPLE' => 'N',
					);
					unset($selected);
				}

				$arTemplateParameters['SORTER_OUTPUT_OF_SHOW'] = array(
					'NAME' => GetMessage('ALFA_MSG_OUTPUT_OF_SHOW'),
					'TYPE' => 'CHECKBOX',
					'VALUE' => 'Y',
					// 'PARENT' => 'ALFA_GR_OUTPUT',
					'REFRESH' => 'Y',
					'DEFAULT' => 'Y',
				);

				if ($arCurrentValues['SORTER_OUTPUT_OF_SHOW'] == 'Y')
				{
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
					if (is_array($arCurrentValues['SORTER_OUTPUT_OF']) && count($arCurrentValues['SORTER_OUTPUT_OF']) > 0)
					{
						foreach ($arCurrentValues['SORTER_OUTPUT_OF'] as $val)
						{
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
	}
}
