<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */

$arParams['SET_TITLE'] = $arParams['SET_TITLE']!='N';

if($arParams['SET_TITLE'])
{
	if ($arResult['SECTION']['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != '')
		$APPLICATION->SetTitle($arResult['SECTION']['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']);
	elseif(isset($arResult['SECTION']['NAME']))
		$APPLICATION->SetTitle($arResult['SECTION']['NAME']);
}