<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (Loader::includeModule('redsign.devfunc')) {
    $listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);

    $arTemplateParameters['RS_PROPERTY_LINK'] = array(
        'NAME' => Loc::getMessage('RS.NL_PROPERTY_LINK'),
        'TYPE' => 'LIST',
        'VALUES' => $listProp['S']
    );
}
