<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var CBitrixComponent $this */
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;
/** @global CDatabase $DB */
global $DB;

if (!CModule::IncludeModule("iblock")) {
    $this->AbortResultCache();
    ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}

if (!isset($arParams["CACHE_TIME"])) {
    $arParams["CACHE_TIME"] = 3600;
}

$arParams["USER_SECTION"] = intval($arParams["USER_SECTION"]);
$arParams["DOWNLOAD_DOC"] = intval($arParams["DOWNLOAD_DOC"]);
$arParams["CACHE_GROUPS"] = $arParams["CACHE_GROUPS"] == "Y";
$arParams["U_ACCESS_SECTION"] = array();

for ($i = 0; $i < count($arParams['SECTIONS_SELECT']); $i++) {
    if (CIBlockSectionRights::UserHasRightTo($arParams["IBLOCK_ID"], $arParams['SECTIONS_SELECT'][$i],
        'element_read')) {
        $arParams["U_ACCESS_SECTION"][] = "Y";
    } else {
        $arParams["U_ACCESS_SECTION"][] = "N";
    }
}
$arParams["U_ACCESS"] = false;
for ($i = 0; $i < count($arParams["U_ACCESS_SECTION"]); $i++) {
    if ($arParams["U_ACCESS_SECTION"][$i] == 'Y') {
        $arParams["U_ACCESS"] = true;
        break;
    }
}
$arParams["IBLOCK_ID"] = intval(trim($arParams["IBLOCK_ID"]));

if (isset($arParams["SORT_BY2"])) {
    $arParams["SORT_BY2"] = mb_strtoupper($arParams["SORT_BY2"]);
} else {
    $arParams["SORT_BY2"] = "left_margin";
}

if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER2"])) {
    $arParams["SORT_ORDER2"] = "ASC";
}

$arParams["SORT_BY1"] = trim($arParams["SORT_BY1"]);
if (strlen($arParams["SORT_BY1"]) <= 0) {
    $arParams["SORT_BY1"] = "CREATE_DATE";
}
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER1"])) {
    $arParams["SORT_ORDER1"] = "DESC";
}
$arParams["PROPERTY_CODE"] = intval($arParams["PROPERTY_CODE"]);
$arParams["PREVIEW_TRUNCATE_LEN"] = intval($arParams["PREVIEW_TRUNCATE_LEN"]);
if ($arParams["PREVIEW_TRUNCATE_LEN"] <= 0) {
    $arParams["PREVIEW_TRUNCATE_LEN"] = 60;
}
$arParams["DOCS_COUNT"] = intval($arParams["DOCS_COUNT"]);
$arParams["INCLUDE_INTO_CHAIN"] = $arParams["INCLUDE_INTO_CHAIN"] != "N";
$arParams["ACTIVE_DATE_FORMAT"] = trim($arParams["ACTIVE_DATE_FORMAT"]);
if (strlen($arParams["ACTIVE_DATE_FORMAT"]) <= 0) {
    $arParams["ACTIVE_DATE_FORMAT"] = $DB->DateFormatToPHP(CSite::GetDateFormat("SHORT"));
}

$arParams["DISPLAY_DOCSSECTION"] = $arParams["DISPLAY_DOCSSECTION"] == "Y";
$arParams["DISPLAY_LIST_SECTION"] = $arParams["DISPLAY_LIST_SECTION"] == "Y";
$arParams["DISPLAY_TOP_PAGER"] = $arParams["DISPLAY_TOP_PAGER"] == "Y";
$arParams["DISPLAY_BOTTOM_PAGER"] = $arParams["DISPLAY_BOTTOM_PAGER"] != "N";
$arParams["PAGER_TITLE"] = trim($arParams["PAGER_TITLE"]);
$arParams["PAGER_SHOW_ALWAYS"] = $arParams["PAGER_SHOW_ALWAYS"] == "Y";
$arParams["PAGER_TEMPLATE"] = trim($arParams["PAGER_TEMPLATE"]);
$arParams["PAGER_DESC_NUMBERING"] = $arParams["PAGER_DESC_NUMBERING"] == "Y";
$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] = intval($arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]);
$arParams["PAGER_SHOW_ALL"] = $arParams["PAGER_SHOW_ALL"] !== "N";
$arParams["SHOW_IN_ALL_SECT"] = $arParams["SHOW_IN_ALL_SECT"] == "Y";
if (!$arParams["DISPLAY_DOCSSECTION"] && ($arParams["DISPLAY_TOP_PAGER"] || $arParams["DISPLAY_BOTTOM_PAGER"])) {
    if ($arParams["DOCS_COUNT"] <= 0) {
        $arParams["DOCS_COUNT"] = 20;
    }

    $arNavParams = array(
        "nPageSize" => $arParams["DOCS_COUNT"],
        "bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
        "bShowAll" => $arParams["PAGER_SHOW_ALL"],
    );
    $arNavigation = CDBResult::GetNavParams($arNavParams);
    if ($arNavigation["PAGEN"] == 0 && $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] > 0) {
        $arParams["CACHE_TIME"] = $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];
    }
} else {
    if ($arParams["DOCS_COUNT"]) {
        $arNavParams = array(
            "nPageSize" => $arParams["DOCS_COUNT"],
        );
    } else {
        $arNavParams = false;
    }

    $arNavigation = false;
}

$arParams["COLOR_BORDER"] = trim($arParams["COLOR_BORDER"]);
$arParams["COLOR_BORDER_TOP"] = trim($arParams["COLOR_BORDER_TOP"]);
$arParams["COLOR_BG_EVEN"] = trim($arParams["COLOR_BG_EVEN"]);
$arParams["COLOR_BG_ODD"] = trim($arParams["COLOR_BG_ODD"]);
$arParams["COLOR_BG_HOVER"] = trim($arParams["COLOR_BG_HOVER"]);
$arParams["COLOR_DATE"] = trim($arParams["COLOR_DATE"]);

if ($arParams["DOWNLOAD_DOC"]) {
    if (!$arParams["CACHE_GROUPS"] ? true : (CIBlockElementRights::UserHasRightTo($arParams["IBLOCK_ID"],
        $arParams["DOWNLOAD_DOC"], 'element_read') ? true : false)) {
        $elements = CIBlockElement::GetList(
            array("sort" => "asc"),
            array(
                "ID" => $arParams["DOWNLOAD_DOC"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"]
            ),
            false, false, array("ID", "IBLOCK_ID", "SHOW_COUNTER", "TIMESTAMP_X")
        );
        if ($arElement = $elements->GetNext()) {
            $elProperty = CIBlockElement::GetProperty(
                $arParams["IBLOCK_ID"],
                $arParams["DOWNLOAD_DOC"],
                array("sort" => "asc"),
                array(
                    "ID" => $arParams["PROPERTY_CODE"],
                    "CHECK_PERMISSION" => "Y"
                )
            ) or die(ShowError(GetMessage("F_DOCS_DOC_FILE_NA")));
            if ($arProperty = $elProperty->Fetch()) {
                $el = new CIBlockElement;
                $el->Update($arParams["DOWNLOAD_DOC"], array(
                        "TIMESTAMP_X" => null,
                        "SHOW_COUNTER" => ($arElement["SHOW_COUNTER"] + 1)
                    )
                ) or die(ShowError(GetMessage("F_DOCS_DOC_SHOW_COUNTER_NU")));

                $arFile = CFile::GetFileArray(intval($arProperty["VALUE"]));
                if ($arParams["HIDE_DIRECT_PATH"] == "N") {
                    LocalRedirect($arFile["SRC"], true);
                } else {
                    set_time_limit(0);
                    CFile::ViewByUser($arFile, array("force_download" => true));
                }
            }
        } else {
            ShowError(GetMessage("F_DOCS_DOC_FILE_NA"));
            @define("ERROR_404", "Y");
            $APPLICATION->SetTitle("404 Not Found");
            CHTTP::SetStatus("404 Not Found");
            return;
        }
    } else {
        ShowError(GetMessage("F_DOCS_DOC_DOWNLOAD_NOT_ALLOWED"));
        @define("ERROR_403", "Y");
        $APPLICATION->SetTitle("403 Forbidden");
        CHTTP::SetStatus("403 Forbidden");
        return;
    }
}

if (!!$arParams["IBLOCK_ID"] &&
    !!count($arParams["SECTIONS_SELECT"]) &&
    !!$arParams["PROPERTY_CODE"] &&
    $this->StartResultCache(false, array(
        (!$arParams["CACHE_GROUPS"] ? false : $USER->IsAuthorized()),
        $arNavigation,
        $arParams["U_ACCESS_SECTION"]
    ))
) {
    if ($arParams["U_ACCESS"]) {
        $arIBFilter = array(
            "ACTIVE" => "Y",
            "ID" => $arParams["IBLOCK_ID"],
            "CHECK_PERMISSIONS" => ($arParams["CACHE_GROUPS"] ? "Y" : "N"),
        );

        $rsIBlock = CIBlock::GetList(array(), $arIBFilter);

        if ($arResult = $rsIBlock->GetNext(true, false)) {
            //SELECT
            $arSelectElm = array(
                "ID",
                "IBLOCK_ID",
                "NAME",
                "DATE_CREATE",
                "TIMESTAMP_X",
                "PREVIEW_TEXT",
                "SHOW_COUNTER",
                "IBLOCK_SECTION_ID",
                "PREVIEW_PICTURE",
            );
            $arSelectSect = array(
                "ID",
                "IBLOCK_ID",
                "NAME",
                "LEFT_MARGIN",
                "RIGHT_MARGIN",
                "DEPTH_LEVEL",
                "IBLOCK_SECTION_ID",
            );
            //WHERE

            $arParams["CACHE_GROUPS"] ? $arFilterElm["CHECK_PERMISSIONS"] = "Y" : false;
            $arFilterSect = array(
                "IBLOCK_ID" => $arResult["ID"],
                "ID" => $arParams["SECTIONS_SELECT"],
                "ACTIVE" => "Y",
            );


            //ORDER BY
            $arSortElm = array(
                $arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
            );
            $arSortSect = array(
                $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"],
            );
            //EXECUTE
            $obParser = new CTextParser;
            $arResult["ITEMS"] = array();
            $arSect = $unSect = array();


            if (isset($arParams["SECTIONS_SELECT"][0]) && $arParams["SECTIONS_SELECT"][0] == '0' && count($arParams["SECTIONS_SELECT"]) > 0) {
                unset($arFilterSect["ID"]);
                unset($arParams['SECTIONS_SELECT'][0]);
                $arFilterSect['!ID'] = $arParams['SECTIONS_SELECT'];
            }

            if ($arParams["DISPLAY_DOCSSECTION"] || $arParams["DISPLAY_LIST_SECTION"]) {
                $rsSection = CIBlockSection::GetList($arSortSect, $arFilterSect, false, $arSelectSect);
                $SECTIONS_SELECT = array();

                while ($obSection = $rsSection->GetNext(true, false)) {

                    if (!array_search($obSection["ID"], $unSect)) {
                        $SECTIONS_SELECT[] = $obSection["ID"];
                        if ($arParams["DISPLAY_DOCSSECTION"]) {
                            $arResult["ITEMS"][$obSection["ID"]] = array(
                                "NAME" => $obSection["NAME"],
                                "ELEMENTS" => array()
                            );
                        }
                        if ($arParams["DISPLAY_LIST_SECTION"]) {
                            $arSect[] = array(
                                "ID" => $obSection["ID"],
                                "NAME" => $obSection["NAME"],
                                "LEFT_MARGIN" => $obSection["LEFT_MARGIN"],
                                "RIGHT_MARGIN" => $obSection["RIGHT_MARGIN"],
                                "DEPTH_LEVEL" => $obSection["DEPTH_LEVEL"],
                                "IBLOCK_SECTION_ID" => $obSection["IBLOCK_SECTION_ID"],
                            );

                        }
                    }
                }
            }
            if ($arParams["DISPLAY_LIST_SECTION"]) {
                $arResult["SECTIONS"] = false;
                $arResult["SECTIONS"] = $arSect;

                $previousDepthLevel = 1;
                $menuIndex = 0;
                foreach ($arResult["SECTIONS"] as &$arSection) {
                    if ($menuIndex > 0) {
                        $arResult["SECTIONS"][$menuIndex - 1]["IS_PARENT"] = $arSection["DEPTH_LEVEL"] > $previousDepthLevel;
                    }
                    $previousDepthLevel = $arSection["DEPTH_LEVEL"];
                    $menuIndex++;
                }
            }
            $arParams["SECTIONS_SELECT"] = $SECTIONS_SELECT;
            $arFilterElm = array(
                "IBLOCK_ID" => $arResult["ID"],
                "IBLOCK_LID" => SITE_ID,
                "ACTIVE" => "Y",
                "SECTION_ID" => (!$arParams["USER_SECTION"] ? $arParams["SECTIONS_SELECT"] : array($arParams["USER_SECTION"]))
            );
            $arElements = array();
            $arIDElem = array();

            $rsElement = CIBlockElement::GetList($arSortElm, $arFilterElm, false, $arNavParams, $arSelectElm);

            while ($obElement = $rsElement->GetNextElement(true, false)) {
                $arItem = $obElement->GetFields(true, false);

                $arItemProps = $obElement->GetProperties();
                foreach ($arItemProps as $prop) {
                    if (((is_array($prop["VALUE"]) && count($prop["VALUE"]) > 0)
                            || (!is_array($prop["VALUE"]) && strlen($prop["VALUE"]) > 0))
                        && $prop["PROPERTY_TYPE"] == "F" && $prop["ID"] == $arParams["PROPERTY_CODE"]
                    ) {
                        $prop = CIBlockFormatProperties::GetDisplayValue($arItem, $prop, false);
                        $file = $prop["FILE_VALUE"];
                        $arItem["DOCFILE"] = array(
                            "FILE_ID" => $file["ID"],
                            "FILE_TYPE" => mb_strtolower(substr($file["FILE_NAME"],
                                strrpos($file["FILE_NAME"], '.') + 1), SITE_CHARSET),
                            "FILE_SIZE" => CFile::FormatSize($file["FILE_SIZE"], 2),
                            "PROPERTY" => $prop["ID"]
                        );
                    }
                }

                $arElements[] = $arItem;
                $arIDElem[] = $arItem["ID"];
            }

            // search multiply sections for elements
            if (!empty($arIDElem) && $arParams["SHOW_IN_ALL_SECT"]) {
                $dbGroups = CIBlockElement::GetElementGroups($arIDElem, true,
                    array("ID", "IBLOCK_ID", "IBLOCK_ELEMENT_ID"));
                while ($arGroup = $dbGroups->Fetch()) {
                    $arElemS[$arGroup["IBLOCK_ELEMENT_ID"]][] = $arGroup["ID"];
                }
            }

            foreach ($arElements as $arItem) {
                $arButtons = CIBlock::GetPanelButtons(
                    $arItem["IBLOCK_ID"],
                    $arItem["ID"],
                    0,
                    array(
                        "SECTION_BUTTONS" => false,
                    )
                );
                $arItem["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
                $arItem["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

                if ($arParams["PREVIEW_TRUNCATE_LEN"] > 0) {
                    $arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"],
                        $arParams["PREVIEW_TRUNCATE_LEN"]);
                }

                $arItem["UPDATE_DATE"] = strlen($arItem["TIMESTAMP_X"]) > 0 ?
                    ($arItem["DATE_CREATE"] !== $arItem["TIMESTAMP_X"] ?
                        CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"],
                            MakeTimeStamp($arItem["TIMESTAMP_X"], CSite::GetDateFormat()))
                        : false)
                    : false;
                $arItem["CREATE_DATE"] = (!$arItem["UPDATE_DATE"] ?
                    (strlen($arItem["DATE_CREATE"]) > 0 ?
                        (CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"],
                            MakeTimeStamp($arItem["DATE_CREATE"], CSite::GetDateFormat())))
                        : false)
                    : false);

                $ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arItem["IBLOCK_ID"], $arItem["ID"]);
                $arItem["IPROPERTY_VALUES"] = $ipropValues->getValues();

                if (isset($arItem["PREVIEW_PICTURE"])) {
                    $arItem["PREVIEW_PICTURE"] = (0 < $arItem["PREVIEW_PICTURE"] ? CFile::GetFileArray($arItem["PREVIEW_PICTURE"]) : false);
                    if ($arItem["PREVIEW_PICTURE"]) {
                        $arItem["PREVIEW_PICTURE"]["ALT"] = $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"];
                        if ($arItem["PREVIEW_PICTURE"]["ALT"] == "") {
                            $arItem["PREVIEW_PICTURE"]["ALT"] = $arItem["NAME"];
                        }
                        $arItem["PREVIEW_PICTURE"]["TITLE"] = $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"];
                        if ($arItem["PREVIEW_PICTURE"]["TITLE"] == "") {
                            $arItem["PREVIEW_PICTURE"]["TITLE"] = $arItem["NAME"];
                        }
                    }
                }

                if ($arParams["DISPLAY_DOCSSECTION"]) {
                    $arResult["ITEMS"][$arItem["IBLOCK_SECTION_ID"]]["ELEMENTS"][] = $arItem;

                    if ($arParams["SHOW_IN_ALL_SECT"]) {
                        if (!empty($arElemS) && count($arElemS[$arItem["ID"]]) > 1) {
                            foreach ($arElemS[$arItem["ID"]] as $elem) {
                                if ($elem == $arItem["IBLOCK_SECTION_ID"]) {
                                    continue;
                                }

                                $arResult["ITEMS"][$elem]["ELEMENTS"][] = $arItem;
                            }
                        }
                    }
                } else {
                    $arResult["ITEMS"][0]["ELEMENTS"][] = $arItem;
                }
            }

            $arResult["NAV_STRING"] = $rsElement->GetPageNavStringEx($navComponentObject, $arParams["PAGER_TITLE"],
                $arParams["PAGER_TEMPLATE"], $arParams["PAGER_SHOW_ALWAYS"]);
            $arResult["NAV_CACHED_DATA"] = $navComponentObject->GetTemplateCachedData();
            $arResult["NAV_RESULT"] = $rsElement;
            $this->SetResultCacheKeys(array(
                "ID",
                "IBLOCK_TYPE_ID",
                "NAV_CACHED_DATA",
                "NAME",
                "SECTION",
                "ELEMENTS",
            ));
            $this->IncludeComponentTemplate();
        } else {
            $this->AbortResultCache();
            ShowError(GetMessage("F_DOCS_DOC_NOT_FOUND"));
            $APPLICATION->SetTitle("404 Not Found");
            @define("ERROR_404", "Y");
            CHTTP::SetStatus("404 Not Found");
        }
    } else {
        $this->AbortResultCache();
        ShowError(GetMessage("F_DOCS_DOC_NOT_ALLOWED"));
        $APPLICATION->SetTitle("403 Forbidden");
        @define("ERROR_403", "Y");
        CHTTP::SetStatus("403 Forbidden");
    }
} else {
    if (!$arParams["IBLOCK_ID"]) {
        $APPLICATION->SetTitle(GetMessage("DOCSLIST_ERROR"));
        ShowError(GetMessage("DOCSLIST_COMPONENT_IS_NOT_SET"));
    } elseif (!count($arParams["SECTIONS_SELECT"])) {
        $APPLICATION->SetTitle(GetMessage("DOCSLIST_ERROR"));
        ShowError(GetMessage("DOCSLIST_SECTION_IS_NOT_SET"));
    } elseif (!$arParams["PROPERTY_CODE"]) {
        $APPLICATION->SetTitle(GetMessage("DOCSLIST_ERROR"));
        ShowError(GetMessage("DOCSLIST_PROPERTY_IS_NOT_SET"));
    }
}

if (isset($arResult["ID"])) {
    if ($USER->IsAuthorized()) {
        if ($APPLICATION->GetShowIncludeAreas() || (is_object($GLOBALS["INTRANET_TOOLBAR"]) && $arParams["INTRANET_TOOLBAR"] !== "N")) {
            if (CModule::IncludeModule("iblock")) {
                $arButtons = CIBlock::GetPanelButtons(
                    $arResult["ID"],
                    0,
                    $arParams["PARENT_SECTION"],
                    array("SECTION_BUTTONS" => false)
                );
                if ($APPLICATION->GetShowIncludeAreas()) {
                    $this->AddIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(),
                        $arButtons));
                }
            }
        }
    }
    if ($arParams["INCLUDE_INTO_CHAIN"] && isset($arResult["NAME"])) {
        $APPLICATION->AddChainItem($arResult["NAME"], "./");
    }
    $this->SetTemplateCachedData($arResult["NAV_CACHED_DATA"]);
}
?>
