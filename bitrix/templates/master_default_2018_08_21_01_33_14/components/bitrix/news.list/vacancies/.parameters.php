<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('redsign.master') || !Loader::includeModule('redsign.devfunc')) {
    return;
}

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);

$arTemplateParameters['SOCIAL_SERVICES'] = array(
    'NAME' => Loc::getMessage('RS.SOCIAL_SERVICES'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'vkontakte,facebook,odnoklassniki,twitter',
);

$arTemplateParameters['NOTE_PROP'] = array(
    'NAME' => Loc::getMessage('RS.NOTE_PROP'),
    'TYPE' => 'LIST',
    'VALUES' => $listProp['SNL'],
);

$arTemplateParameters['JOB_LINK'] = array(
    'NAME' => Loc::getMessage('RS.JOB_LINK'),
    'TYPE' => 'STRING',
    'DEFAULT' => '/include/forms/job/?element_id=#ELEMENT_ID#',
);
