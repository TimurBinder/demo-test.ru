<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\ParametersUtils;

if (!Loader::includeModule('redsign.master') || !Loader::includeModule('redsign.devfunc')) {
    return;
}

Loc::loadMessages(__FILE__);

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);

$arTemplateParameters['SHOW_TITLE'] = array(
  'PARENT' => 'VISUAL',
  'TYPE' => 'CHECKBOX',
  'DEFAULT' => 'Y',
  'NAME' => Loc::getMessage('RS.SHOW_TITLE')
);

$arTemplateParameters['SHOW_DESCRIPTION'] = array(
  'PARENT' => 'VISUAL',
  'TYPE' => 'CHECKBOX',
  'DEFAULT' => 'Y',
  'NAME' => Loc::getMessage('RS.SHOW_DESCRIPTION')
);

$arTemplateParameters['SECTION_PAGE_URL'] = array(
  'PARENT' => 'VISUAL',
  'TYPE' => 'STRING',
  'NAME' => Loc::getMessage('RS.SECTION_PAGE_URL')
);

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
