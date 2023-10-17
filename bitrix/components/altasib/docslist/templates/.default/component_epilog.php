<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arParams["DISPLAY_LIST_SECTION"])
{
	if($arParams['JQUERY_EN'] == 'jquery2')
		CJSCore::Init(array('jquery2'));
	elseif($arParams['JQUERY_EN'] != 'N')
		CJSCore::Init(array("jquery"));
}
?>