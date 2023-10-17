<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!CModule::IncludeModule("iblock")) {
    return;
}

$arTypesEx = CIBlockParameters::GetIBlockTypes(Array("-" => " "));
$arIBlocks = Array();
$db_iblock = CIBlock::GetList(
    Array("SORT" => "ASC"),
    Array(
        "SITE_ID" => $_REQUEST["site"],
        "TYPE" => ($arCurrentValues["IBLOCK_TYPE"] ? $arCurrentValues["IBLOCK_TYPE"] : "altasib_docs")
    )
);

while ($arRes = $db_iblock->Fetch()) {
    $currIB_ID[] = $arRes["ID"];
    $arIBlocks[$arRes["ID"]] = "[" . $arRes["ID"] . "] " . $arRes["NAME"];
}

//$arSortSect = (isset($arCurrentValues["SORT_ORDER2"]) && isset($arCurrentValues["SORT_BY2"]) ? array($arCurrentValues["SORT_BY2"] => $arCurrentValues["SORT_ORDER2"]) : Array("left_margin"=>"asc", "sort" => "asc", "name" => "asc"));

$arSortSect = array('left_margin' => 'asc');
$arFilterSect = Array(
    "ACTIVE" => "Y",
    "IBLOCK_ID" => (in_array($arCurrentValues["IBLOCK_ID"], $currIB_ID) ? $arCurrentValues["IBLOCK_ID"] : $currIB_ID[0]),
    array("ID", "NAME", "DEPTH_LEVEL")
);
$rsSect = CIBlockSection::GetList(
    $arSortSect,
    $arFilterSect
);

$arrSections[0] = GetMessage("SHOW_TITLE_SECTION");
while ($arrS = $rsSect->Fetch()) {
    //$currSect_ID[] = $arrS["ID"];
    //$arrSections[$arrS["ID"]] = str_repeat(" . ", $arrS["DEPTH_LEVEL"])." [".$arrS["ID"]."] ".$arrS["NAME"];
    $arrSections[$arrS["ID"]] = str_repeat(" . ", $arrS["DEPTH_LEVEL"]) . " [" . $arrS["ID"] . "] " . $arrS["NAME"];
}

$rsProp = CIBlockProperty::GetList(
    Array(
        "sort" => "asc",
        "name" => "asc"
    ),
    Array(
        "ACTIVE" => "Y",
        "IBLOCK_ID" => (in_array($arCurrentValues["IBLOCK_ID"],
            $currIB_ID) ? $arCurrentValues["IBLOCK_ID"] : $currIB_ID[0])
    )
);
while ($arrProp = $rsProp->Fetch()) {
    $currProp_ID[] = $arrProp["ID"];
    if ($arrProp["PROPERTY_TYPE"] == "F") {
        $arProperty_F[$arrProp["ID"]] = "[" . $arrProp["CODE"] . "] " . $arrProp["NAME"];
    }
}

$arSorts = Array(
    "ASC" => GetMessage("ALTASIB_DL_IBLOCK_DESC_ASC"),
    "DESC" => GetMessage("ALTASIB_DL_IBLOCK_DESC_DESC")
);
$arSortFields = Array(
    "SORT" => GetMessage("ALTASIB_DL_IBLOCK_DESC_FSORT"),
    "TIMESTAMP_X" => GetMessage("ALTASIB_DL_IBLOCK_DESC_FTSAMP"),
    "ID" => GetMessage("ALTASIB_DL_IBLOCK_DESC_FID"),
    "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_FNAME"),
    "CREATE_DATA" => GetMessage("ALTASIB_DL_IBLOCK_DESC_FACT"),
    "ACTIVE_FROM" => GetMessage("ALTASIB_DL_IBLOCK_DESC_FROM"),
    "ACTIVE_TO" => GetMessage("ALTASIB_DL_IBLOCK_DESC_TO"),
);

$arSortFieldsSection = array_merge(array(
    "LEFT_MARGIN" => GetMessage("ALTASIB_DL_IBLOCK_DESC_LM"),
    "DEPTH_LEVEL" => GetMessage("ALTASIB_DL_IBLOCK_DESC_DL")
),
    $arSortFields
);

$arComponentParameters = array(
    "GROUPS" => array(
        "DOCSLIST_STYLE" => array(
            "SORT" => 500,
            "NAME" => GetMessage("ALTASIB_DL_DOCSLIST_GROUP_NAME")
        )
    ),
    "PARAMETERS" => array(
        "IBLOCK_TYPE" => Array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_LIST_TYPE"),
            "TYPE" => "LIST",
            "VALUES" => $arTypesEx,
            "DEFAULT" => "altasib_docs",
            "REFRESH" => "Y"
        ),
        "IBLOCK_ID" => Array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_LIST_ID"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "DEFAULT" => '={$_REQUEST["ID"]}',
            "REFRESH" => "Y"
        ),
        "SECTIONS_SELECT" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("ALTASIB_DL_IBLOCK_SECTIONS_SELECT"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arrSections,
            "DEFAULT" => $currSect_ID
        ),
        "PROPERTY_CODE" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("ALTASIB_DL_IBLOCK_PROPERTY_CODE"),
            "TYPE" => "LIST",
            "VALUES" => $arProperty_F,
            "DEFAULT" => $currProp_ID
        ),
        "DOWNLOAD_DOC" => Array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("ALTASIB_DL_DESC_DOWNLOAD_DOC"),
            "TYPE" => "STRING",
            "DEFAULT" => '={$_REQUEST["EID"]}'
        ),
        "DISPLAY_DOCSSECTION" => array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("ALTASIB_DL_DESC_DISPLAY_DOCSSECTION"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
            "REFRESH" => "Y"
        ),
        "INCLUDE_INTO_CHAIN" => Array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_INCLUDE_IB_INTO_CHAIN"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
        ),
        "DOWNLOAD_COUNT" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("ALTASIB_DL_DESC_DOWNLOAD_COUNT"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y"
        ),
        "DISPLAY_SIZE" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("ALTASIB_DL_DESC_DISPLAY_SIZE"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y"
        ),
        "DISPLAY_DATE" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("ALTASIB_DL_DESC_DISPLAY_DATE"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y"
        ),
        "ACTIVE_DATE_FORMAT" => CIBlockParameters::GetDateFormat(GetMessage("ALTASIB_DL_IBLOCK_DESC_ACTIVE_DATE_FORMAT"),
            "ADDITIONAL_SETTINGS"),
        "CACHE_GROUPS" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("ALTASIB_DL_CACHE_GROUPS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N"
        ),
        "PREVIEW_TRUNCATE_LEN" => Array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_PREVIEW_TRUNCATE_LEN"),
            "TYPE" => "STRING",
            "DEFAULT" => 2048
        ),
        "CACHE_TIME" => Array("DEFAULT" => 3600),
        "COLOR_BORDER_TOP" => array(
            "PARENT" => "DOCSLIST_STYLE",
            "NAME" => GetMessage("ALTASIB_DL_COLOR_BORDER_TOP"),
            "TYPE" => "COLORPICKER",
            "DEFAULT" => "#cccccc"
        ),
        "COLOR_BORDER" => array(
            "PARENT" => "DOCSLIST_STYLE",
            "NAME" => GetMessage("ALTASIB_DL_COLOR_BORDER"),
            "TYPE" => "COLORPICKER",
            "DEFAULT" => "#e9e9e9"
        ),
        "COLOR_BG_EVEN" => array(
            "PARENT" => "DOCSLIST_STYLE",
            "NAME" => GetMessage("ALTASIB_DL_COLOR_BG_EVEN"),
            "TYPE" => "COLORPICKER",
            "DEFAULT" => "#ffffff"
        ),
        "COLOR_BG_ODD" => array(
            "PARENT" => "DOCSLIST_STYLE",
            "NAME" => GetMessage("ALTASIB_DL_COLOR_BG_ODD"),
            "TYPE" => "COLORPICKER",
            "DEFAULT" => "#f9f9f9"
        ),
        "COLOR_BG_HOVER" => array(
            "PARENT" => "DOCSLIST_STYLE",
            "NAME" => GetMessage("ALTASIB_DL_COLOR_BG_HOVER"),
            "TYPE" => "COLORPICKER",
            "DEFAULT" => "#ededed"
        ),
        "COLOR_DATE" => array(
            "PARENT" => "DOCSLIST_STYLE",
            "NAME" => GetMessage("ALTASIB_DL_COLOR_DATE"),
            "TYPE" => "COLORPICKER",
            "DEFAULT" => "#a8a8a8"
        )
    )
);

/*
$arComponentParameters["PARAMETERS"]["JQUERY_EN"] = array(
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
*/

if ($arCurrentValues["DISPLAY_DOCSSECTION"] != "Y") {
    $arComponentParameters["PARAMETERS"]["DISPLAY_LIST_SECTION"] = array(
        "PARENT" => "DATA_SOURCE",
        "NAME" => GetMessage("ALTASIB_DL_DISPLAY_LIST_SECTION"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y"
    );
    $arComponentParameters["PARAMETERS"]["USER_SECTION"] = array(
        "PARENT" => "DATA_SOURCE",
        "NAME" => GetMessage("ALTASIB_DL_USER_SECTION"),
        "TYPE" => "STRING",
        "DEFAULT" => '={$_REQUEST["SID"]}'
    );
    $arComponentParameters["PARAMETERS"]["SORT_BY1"] = Array(
        "PARENT" => "DATA_SOURCE",
        "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_IBORD1"),
        "TYPE" => "LIST",
        "DEFAULT" => "NAME",
        "VALUES" => $arSortFields
    );
    $arComponentParameters["PARAMETERS"]["SORT_ORDER1"] = array(
        "PARENT" => "DATA_SOURCE",
        "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_IBBY1"),
        "TYPE" => "LIST",
        "DEFAULT" => "ASC",
        "VALUES" => $arSorts,
        "ADDITIONAL_VALUES" => "Y",
    );
    $arComponentParameters["PARAMETERS"]["SORT_BY2"] = Array(
        "PARENT" => "DATA_SOURCE",
        "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_IBORD2"),
        "TYPE" => "LIST",
        "DEFAULT" => "NAME",
        "VALUES" => $arSortFieldsSection
    );
    $arComponentParameters["PARAMETERS"]["SORT_ORDER2"] = Array(
        "PARENT" => "DATA_SOURCE",
        "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_IBBY2"),
        "TYPE" => "LIST",
        "DEFAULT" => "ASC",
        "VALUES" => $arSorts,
        "ADDITIONAL_VALUES" => "Y",
    );
    CIBlockParameters::AddPagerSettings($arComponentParameters, GetMessage("ALTASIB_DL_IBLOCK_DESC_PAGER_DOCS"), true,
        true);
    $arComponentParameters["PARAMETERS"]["PAGER_SHOW_ALWAYS"]["DEFAULT"] = 'N';
    $arComponentParameters["PARAMETERS"]["PAGER_SHOW_ALL"]["DEFAULT"] = 'N';
    $arComponentParameters["PARAMETERS"]["PAGER_TEMPLATE"]["DEFAULT"] = 'modern';
} else {
    $arComponentParameters["PARAMETERS"]["SHOW_IN_ALL_SECT"] = Array(
        "PARENT" => "DATA_SOURCE",
        "NAME" => GetMessage("ALTASIB_IN_ALL_SECT"),
        "DEFAULT" => "Y",
        "TYPE" => "CHECKBOX",
    );
    $arComponentParameters["PARAMETERS"]["SORT_BY1"] = Array(
        "PARENT" => "DATA_SOURCE",
        "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_IBORD1"),
        "TYPE" => "LIST",
        "DEFAULT" => "NAME",
        "VALUES" => $arSortFields
    );
    $arComponentParameters["PARAMETERS"]["SORT_ORDER1"] = array(
        "PARENT" => "DATA_SOURCE",
        "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_IBBY1"),
        "TYPE" => "LIST",
        "DEFAULT" => "ASC",
        "VALUES" => $arSorts,
        "ADDITIONAL_VALUES" => "Y",
    );
    $arComponentParameters["PARAMETERS"]["SORT_BY2"] = Array(
        "PARENT" => "DATA_SOURCE",
        "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_IBORD2"),
        "TYPE" => "LIST",
        "DEFAULT" => "NAME",
        "VALUES" => $arSortFieldsSection
    );
    $arComponentParameters["PARAMETERS"]["SORT_ORDER2"] = Array(
        "PARENT" => "DATA_SOURCE",
        "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_IBBY2"),
        "TYPE" => "LIST",
        "DEFAULT" => "ASC",
        "VALUES" => $arSorts,
        "ADDITIONAL_VALUES" => "Y",
    );
}

$arComponentParameters["PARAMETERS"]["DOCS_COUNT"] = Array(
    "PARENT" => "DATA_SOURCE",
    "NAME" => GetMessage("ALTASIB_DL_IBLOCK_DESC_LIST_CONT"),
    "TYPE" => "STRING",
    "DEFAULT" => 20
);

$arComponentParameters["PARAMETERS"]["HIDE_DIRECT_PATH"] = Array(
    "PARENT" => "DATA_SOURCE",
    "NAME" => GetMessage("ALTASIB_DL_HIDE_DIRECT_PATH"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "Y",
);
?>
