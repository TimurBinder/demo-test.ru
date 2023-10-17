<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\ParametersUtils;

global $USER_FIELD_MANAGER;

$arProperty_UF = array();
$arUserFields = $USER_FIELD_MANAGER->GetUserFields(array());
foreach ($arUserFields as $FIELD_NAME=>$arUserField)
{
	$arProperty_UF[$FIELD_NAME] = $arUserField['LIST_COLUMN_LABEL'] ? $arUserField['LIST_COLUMN_LABEL']: $FIELD_NAME;
}

$defaultListValues = array('-' => getMessage('RS.MASTER.UNDEFINED'));

$arViewModeList = array(
	//'LIST' => GetMessage('CPT_BCSL_VIEW_MODE_LIST'),
	'LINE' => GetMessage('CPT_BCSL_VIEW_MODE_LINE'),
	//'TEXT' => GetMessage('CPT_BCSL_VIEW_MODE_TEXT'),
	'TILE' => GetMessage('CPT_BCSL_VIEW_MODE_TILE'),
);

$arTemplateParameters = array(
	'SHOW_PARENT_NAME' => array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CPT_BCSL_SHOW_PARENT_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y'
	),
	// 'SHOW_PARENT_DESCR' => array(
		// 'PARENT' => 'VISUAL',
		// 'NAME' => GetMessage('RS.MASTER.SHOW_PARENT_DESCR'),
		// 'TYPE' => 'CHECKBOX',
		// 'DEFAULT' => 'Y'
	// ),
	'PREVIEW_TRUNCATE_LEN' => array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('RS.MASTER.PREVIEW_TRUNCATE_LEN'),
		'TYPE' => 'STRING',
		'DEFAULT' => '',
	),
	'SET_TITLE' => array(
	),
);

if (Loader::includeModule('redsign.master')) {
    ParametersUtils::addCommonParameters(
		$arTemplateParameters,
		$arCurrentValues,
		array(
		    'sectionsView',
		)
    );
    
    $arTemplateParameters['PARENT_URL'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('RS.MASTER.PARENT_URL'),
		'TYPE' => 'STRING',
		'DEFAULT' => '',
    );
}

if ('LINE' == $arCurrentValues['SECTIONS_VIEW_MODE'])
{
    $arTemplateParameters['HIDE_SECTION_NAME'] = array(
  		'PARENT' => 'VISUAL',
  		'NAME' => GetMessage('CPT_BCSL_HIDE_SECTION_NAME'),
  		'TYPE' => 'CHECKBOX',
  		'DEFAULT' => 'N'
    );

    ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('bootstrapCols'));
}


if ('LINE' == $arCurrentValues['SECTIONS_VIEW_MODE'])
{
	$arTemplateParameters['MAX_CATEGORY_SHOW'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('RS.MASTER.MAX_CATEGORY_SHOW'),
		'TYPE' => 'STRING',
		'DEFAULT' => '12'
	);
	$arTemplateParameters['SHOW_MORE_BUTTON'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('RS.MASTER.SHOW_MORE_BUTTON'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
		'REFRESH' => 'Y',
	);
	if ('Y' == $arCurrentValues['SHOW_MORE_BUTTON'])
	{
		$arTemplateParameters['MORE_BUTTON_LINK'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('RS.MASTER.MORE_BUTTON_LINK'),
			'TYPE' => 'STRING',
		);
		$arTemplateParameters['MORE_BUTTON_TEXT'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('RS.MASTER.MORE_BUTTON_TEXT'),
			'TYPE' => 'STRING',
			'DEFAULT' => GetMessage('RS.MASTER.MORE_BUTTON_TEXT_DFEFAULT'),
		);
	}
}
