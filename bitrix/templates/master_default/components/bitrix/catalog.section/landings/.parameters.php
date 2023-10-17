<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\ModuleManager;

if (!Loader::includeModule("redsign.devfunc"))
    return;

if (!Loader::includeModule("iblock"))
    return;


$rsIblock = CIBlock::GetList(array('SORT' => 'ASC'), false);
while ($arr = $rsIblock->Fetch())
	$arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];

$propItems = array(); 
if (isset($arCurrentValues['LANDING_ELEMENT_IBLOCK_ID']) && (int)$arCurrentValues['LANDING_ELEMENT_IBLOCK_ID'] > 0) {

    $propItems = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['LANDING_ELEMENT_IBLOCK_ID']);

}

$arTemplateParameters['LANDING_ELEMENT_IBLOCK_ID'] = array(
    'NAME' => Loc::getMessage('RS.LANDING_SECTION.IBLOCK_ITEM'),
    'TYPE' => 'LIST',
    'PARENT' => 'VISUAL',
    'MULTIPLE' => 'N',
    'VALUES' => $arIBlock,
    'REFRESH' => 'Y',
    'DEFAULT' => "-",
);

if (intval($arCurrentValues['IBLOCK_ID']) > 0) {
    $mainProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);
    $arTemplateParameters['PROPERTY_CODE'] = array( 
        'VALUES' => $mainProp["ALL"],
        "PARENT" => "VISUAL",
        "NAME" => GetMessage("RS.LANDING_SECTION.IBLOCK_PROPERTY"), 
        "TYPE" => "LIST",
        "MULTIPLE" => "Y",
        "ADDITIONAL_VALUES" => "Y",
    );
}

if (!empty($propItems)) {

    $arTemplateParameters['IBLOCK_ITEM_ID'] = array(
        'NAME' => Loc::getMessage('RS.LANDING_SECTION.IBLOCK_ITEM_ID'),
        'TYPE' => 'STRING',
        'PARENT' => 'VISUAL',
    );    

    $arTemplateParameters['IBLOCK_ITEM_PROP'] = array(
        'NAME' => Loc::getMessage('RS.LANDING_SECTION.IBLOCK_ITEM_PROP'),
        'TYPE' => 'LIST',
        'PARENT' => 'VISUAL',
        'VALUES' => $propItems["ALL"],
    );

    $arTemplateParameters['PRICE_PROP'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.MASTER.PRICE_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $propItems["SNL"],
        'DEFAULT' => '-',
    );
    $arTemplateParameters['DISCOUNT_PROP'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.MASTER.DISCOUNT_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $propItems["SNL"],
        'DEFAULT' => '-',
    );

    $arTemplateParameters['CURRENCY_PROP'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.MASTER.CURRENCY_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $propItems["SNL"],
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

    $arTemplateParameters['ADD_PICT_PROP'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.MASTER.ADD_PICT_PROP'),
        'TYPE' => 'LIST',
        'VALUES' => $propItems["ALL"],
        'DEFAULT' => '-',
    );
}

if (ModuleManager::isModuleInstalled('redsign.lightbasket')) {

    $addToBasketActions = array(
      //'BUY' => GetMessage('ADD_TO_BASKET_ACTION_BUY'),
      'ADD' => GetMessage('ADD_TO_BASKET_ACTION_ADD'),
      'ASK' => GetMessage('RS.MASTER.ASK_QUESTION'),
    );
    $arTemplateParameters['ADD_TO_BASKET_ACTION'] = array(
        'PARENT' => 'BASKET',
        'NAME' => GetMessage('CP_BC_TPL_DETAIL_ADD_TO_BASKET_ACTION'),
        'TYPE' => 'LIST',
        'VALUES' => $addToBasketActions,
        'DEFAULT' => 'BUY',
        'REFRESH' => 'Y',
        'MULTIPLE' => 'Y',
        'HIDDEN' => (!$useCommonSettingsBasketPopup ? 'N' : 'Y')
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

        $arTemplateParameters['ADD_TO_BASKET_ACTION_PRIMARY'] = array(
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
}
$arTemplateParameters['MESS_BTN_ADD_TO_BASKET'] = array(
	'PARENT' => 'BASKET',
	'NAME' => GetMessage('CP_BC_TPL_MESS_BTN_ADD_TO_BASKET'),
	'TYPE' => 'STRING',
	'DEFAULT' => GetMessage('CP_BC_TPL_MESS_BTN_ADD_TO_BASKET_DEFAULT')
);
$arTemplateParameters['LINK_BTN_ASK'] = array(
    'PARENT' => 'BASKET',
    'NAME' => Loc::getMessage('RS.MASTER.LINK_BTN_ASK'), 
    'TYPE' => 'STRING',
    'DEFAULT' => '/include/forms/product_ask/?element_id=#ELEMENT_ID#'
);
$arTemplateParameters['MESS_BTN_ASK'] = array(
    'PARENT' => 'BASKET',
    'NAME' => Loc::getMessage('RS.MASTER.MESS_BTN_ASK_QUESTION'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('RS.MASTER.MESS_BTN_ASK_QUESTION_DEFAULT')
);
