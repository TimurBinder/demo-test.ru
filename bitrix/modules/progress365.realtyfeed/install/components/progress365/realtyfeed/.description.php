<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 
$arComponentDescription = array(
    "NAME" => GetMessage("XML фид"),
    "DESCRIPTION" => GetMessage("XML фид объектов недвижимости"),
    "PATH" => array(
        "ID" => "progress365",
        "CHILD" => array(
            "ID" => "realtyfeed",
            "NAME" => "XML фид"
        )
    ),
);
?>