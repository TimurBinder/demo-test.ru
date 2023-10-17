<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if($arCurrentValues["DISPLAY_LIST_SECTION"] == "Y")
{
	$arTemplateParameters["JQUERY_EN"] = array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("ALTASIB_DL_ADD_JQUERY"),
		"TYPE" => "LIST",
		"ADDITIONAL_VALUES" => "N",
		"MULTIPLE" => "N",
		"REFRESH" => "N",
		"VALUES" => array(
			"jquery" => GetMessage("ALTASIB_DL_ADD_JQUERY_YES"),
			"jquery2" => GetMessage("ALTASIB_DL_ADD_JQUERY_2"),
			"N" => GetMessage("ALTASIB_DL_ADD_JQUERY_NO")
		),
		"DEFAULT" => "Y",
	);
}
?>