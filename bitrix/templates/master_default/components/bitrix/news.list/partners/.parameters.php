<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\ParametersUtils;

if (!Loader::includeModule('redsign.master') || !Loader::includeModule('redsign.devfunc')) {
    return;
}

Loc::loadMessages(__FILE__);

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);


$arTemplateParameters = array(
	'DISPLAY_NAME' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'DISPLAY_PICTURE' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_PICTURE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'DISPLAY_PREVIEW_TEXT' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_TEXT'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
);

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

$arTemplateParameters['SITE_URL_PROP'] = array(
    'NAME' => Loc::getMessage('RS.MASTER.SITE_URL_PROP'),
    'TYPE' => 'LIST',
    'VALUES' => $listProp['SNL'],
);

$arTemplateParameters['SITE_DOMAIN_PROP'] = array(
    'NAME' => Loc::getMessage('RS.MASTER.SITE_DOMAIN_PROP'),
    'TYPE' => 'LIST',
    'VALUES' => $listProp['SNL'],
);

$arTemplateParameters['COMPANY_PHONE_PROP'] = array(
    'NAME' => Loc::getMessage('RS.MASTER.COMPANY_PHONE_PROP'),
    'TYPE' => 'LIST',
    'VALUES' => $listProp['SNL'],
);

if (Loader::includeModule('redsign.master')) {
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
