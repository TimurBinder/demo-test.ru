<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_LIST"),
	"DESCRIPTION" => GetMessage("ALTASIB_DL_IBLOCK_DESC_LIST_DESC"),
	"ICON" => "/images/docs_list.gif",
	"SORT" => 20,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "IS-MARKET.RU",
		"CHILD" => array(
			"ID" => "files",
			"NAME" => GetMessage("ALTASIB_DL_SEC_NAME"),
			"SORT" => 10
		),
	),
);
?>