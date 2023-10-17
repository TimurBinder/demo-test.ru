<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!CModule::IncludeModule("highloadblock"))
	return;

if (!WIZARD_INSTALL_DEMO_DATA)
	return;

$COLOR_ID = $_SESSION["ESHOP_HBLOCK_COLOR_ID"];
unset($_SESSION["ESHOP_HBLOCK_COLOR_ID"]);

$BRAND_ID = $_SESSION["ESHOP_HBLOCK_BRAND_ID"];
unset($_SESSION["ESHOP_HBLOCK_BRAND_ID"]);

$FEATURE_FILTER_ID = $_SESSION["ESHOP_HBLOCK_FEATURE_FILTER_ID"];
unset($_SESSION["ESHOP_HBLOCK_FEATURE_FILTER_ID"]);

//adding rows
WizardServices::IncludeServiceLang("references.php", LANGUAGE_ID);

use Bitrix\Highloadblock as HL;
global $USER_FIELD_MANAGER;

if (intval($COLOR_ID) > 0)
{
	$hldata = HL\HighloadBlockTable::getById($COLOR_ID)->fetch();
	if (is_array($hldata))
	{
		$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

		$entity_data_class = $hlentity->getDataClass();
		$arColors = array(
			"BLACK" => "references_files/000000.png",
			"WHITE" => "references_files/FFFFFF.png",
            "GOLD" => "references_files/FFD700.png",
            "GREEN" => "references_files/008000.png",
			"RED" => "references_files/FF0000.png",
			"FREE_SPEECH_BLUE" => "references_files/3F47CC.png",
            "CARNATION_PINK" => "references_files/FEAEC9.png",
            "PURPLE_HEART" => "references_files/4B3ABE.png",
            "BURGUNDY" => "references_files/790013.png",
            "GRAY" => "references_files/7F7F7F.png",
            "ELECTRIC_LIME" => "references_files/C6FC06.png",
            "PUMPKIN" => "references_files/FF7F26.png",
            "IVORY" => "references_files/FEFFF1.png",
			"DEEP_SKY_BLUE" => "references_files/00A3E8.png",
            "YELLOW" => "references_files/FEF200.png",
			"SANTE_FE" => "references_files/B97A57.png",
			"SILVER" => "references_files/C0C0C0.png",
			"TANGERINE_YELLOW" => "references_files/FFC704.png",
			"LIME" => "references_files/00FF01.png",
			"ALIZARIN" => "references_files/ED1B24.png",
			"DIM_GRAY" => "references_files/666666.png",
			"FRENCH_LILAC" => "references_files/DFB6D8.png",
			"BLACK_WHITE" => "references_files/E6E4D8.png",
			"BEIGE" => "references_files/F5F5DC.png",
			"PRUSSIAN_BLUE" => "references_files/003366.png",
			"COSMOS" => "references_files/FFCCCB.png",
			"PEACH" => "references_files/FFCBA4.png",
			"HEITI" => "references_files/364042.png",
			"INCH_WORM" => "references_files/B6DB19.png",
		);
		$sort = 0;
		foreach($arColors as $colorName=>$colorFile)
		{
			$sort+= 100;
			$arData = array(
				'UF_NAME' => GetMessage("WZD_REF_COLOR.".$colorName),
				'UF_FILE' =>
					array (
						'name' => ToLower($colorName).".jpg",
						'type' => 'image/jpeg',
						'tmp_name' => WIZARD_ABSOLUTE_PATH."/site/services/iblock/".$colorFile
					),
				'UF_SORT' => $sort,
				'UF_DEF' => ($sort > 100) ? "0" : "1",
				'UF_XML_ID' => ToLower($colorName)
			);
			$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$COLOR_ID, $arData);
			$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$COLOR_ID, null, $arData);

			$result = $entity_data_class::add($arData);
		}
	}
}

if (intval($BRAND_ID) > 0)
{
	$hldata = HL\HighloadBlockTable::getById($BRAND_ID)->fetch();
	if (is_array($hldata))
	{
		$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

		$entity_data_class = $hlentity->getDataClass();
		$arBrands = array(
			"APPLE" => "",
			"OASIS" => "",
			"HAIER" => "",
			"ARISTON" => "",
			"ELSOTHERM" => "",
			"ATLANTIC" => "",
			"AIR_O_SWISS" => "",
			"NEOLINE" => "",
			"ELECTROLUX" => "",
			"SAMSUNG" => "",
			"SONY" => "",
			"GOOGLE" => "",
			"LG" => "",
			"SCARLETT" => "",
			"JUKI" => "",
			"AURORORA" => "",
			"TYPICAL" => "",
			"PELETS" => "",
			"TERRANICA" => "",
			"TINGER" => "",
			"JOHN_DEERE" => "",
			"EUROLUX" => "",
			"WESTER" => "",
			"FUBAG" => "",
			"XIAOMI" => "",
			"MEIZU" => "",
		);
		$sort = 0;
		foreach($arBrands as $brandName=>$brandFile)
		{
			$sort+= 100;
			$arData = array(
				'UF_NAME' => GetMessage("WZD_REF_BRAND.".$brandName),
				'UF_FILE' =>
					array (
						/*
						'name' => ToLower($brandName).".png",
						'type' => 'image/png',
						'tmp_name' => WIZARD_ABSOLUTE_PATH."/site/services/iblock/".$brandFile
						*/
					),
				'UF_SORT' => $sort,
				//'UF_DESCRIPTION' => GetMessage("WZD_REF_BRAND_DESCR_".$brandName),
				//'UF_FULL_DESCRIPTION' => GetMessage("WZD_REF_BRAND_FULL_DESCR_".$brandName),
				'UF_XML_ID' => ToLower($brandName)
			);
			$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$BRAND_ID, $arData);
			$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$BRAND_ID, null, $arData);

			$result = $entity_data_class::add($arData);
		}
	}
}

if (intval($FEATURE_FILTER_ID) > 0)
{
	$hldata = HL\HighloadBlockTable::getById($FEATURE_FILTER_ID)->fetch();
	if (is_array($hldata))
	{
		$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

		$entity_data_class = $hlentity->getDataClass();
		$arFeatureFilters = array(
		);
		$sort = 0;
		foreach($arFeatureFilters as $featureFilterName=>$featureFilterFile)
		{
			$sort+= 100;
			$arData = array(
				'UF_NAME' => GetMessage("WZD_REF_FEATURE_FILTER.".$featureFilterName),
				'UF_FILE' =>
					array (
						/*
						'name' => ToLower($featureFilterName).".png",
						'type' => 'image/png',
						'tmp_name' => WIZARD_ABSOLUTE_PATH."/site/services/iblock/".$featureFilterFile
						*/
					),
				'UF_SORT' => $sort,
				//'UF_DESCRIPTION' => GetMessage("WZD_REF_FEATURE_FILTER_DESCR_".$featureFilterName),
				//'UF_FULL_DESCRIPTION' => GetMessage("WZD_REF_FEATURE_FILTER_FULL_DESCR_".$featureFilterName),
				'UF_XML_ID' => ToLower($featureFilterName)
			);
			$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$FEATURE_FILTER_ID, $arData);
			$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$FEATURE_FILTER_ID, null, $arData);

			$result = $entity_data_class::add($arData);
		}
	}
}
?>