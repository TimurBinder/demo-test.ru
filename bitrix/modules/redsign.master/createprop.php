<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

CModule::IncludeModule("iblock");

function checkIblock($item, $iblockInfo) {
    if ($item["iblock_link"] != "") {
        $res = CIBlock::GetList(Array(), Array("=CODE"=>$item["iblock_link"]["code"],), true);
        if ($ar_res = $res->Fetch()) {
            if ($ar_res["ID"] > 0)
                return $ar_res["ID"];
        }

        $ib = new CIBlock;
        $arFields = Array(
          "ACTIVE" => "Y",
          "NAME" => $item["iblock_link"]["name"],
          "CODE" => $item["iblock_link"]["code"],
          "IBLOCK_TYPE_ID" => $iblockInfo["IBLOCK_TYPE_ID"],
          "SITE_ID" => $arSite['ID'],
          "SORT" => 500,
        );
        $ID = $ib->Add($arFields);

        return $ID;
    }
}

function createElem($item, $iblockInfo) {
        
    $propId = 0;

    $iblockId = $iblockInfo["ID"];

    $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblockId, "CODE"=>$item["code"]));
    if($prop_fields = $properties->GetNext()) {
        if ($prop_fields["ID"] > 0 && $item["code"] == $prop_fields["CODE"])
            $propId = $prop_fields["ID"];
    }

    if ($propId < 1) {

        $arFields = Array(
          "NAME" => $item["name"],
          "ACTIVE" => "Y",
          "SORT" => $item["sort"],
          "CODE" => $item["code"],
          "PROPERTY_TYPE" => "S",
          "IBLOCK_ID" => $iblockId,
        );

        switch ($item["type"]):

            case "list":
                $arFields["PROPERTY_TYPE"] = "L";
                if (count($item["values"]) < 2 && $item["values"]["0"]["name"] == "Y")
                    $arFields["LIST_TYPE"] = "C";
                break;
            case "number":
                $arFields["PROPERTY_TYPE"] = "N";
                break;
            case "file":
                $arFields["PROPERTY_TYPE"] = "F";
                break;
            case "link_elem":
                $id = checkIblock($item, $iblockInfo);
                if ($id > 0)
                    $arFields["LINK_IBLOCK_ID"] = $id;
                $arFields["PROPERTY_TYPE"] = "E";
                break;
            default:
                $arFields["PROPERTY_TYPE"] = "S";
                if ($item["descr"] == "Y") {
                    $arFields["WITH_DESCRIPTION"] = "Y";
                }
                break;

        endswitch;

        if ($item["many"] == "Y") {
            $arFields["MULTIPLE"] = "Y";
        }

       $iblockproperty = new CIBlockProperty;
       $propId = $iblockproperty->Add($arFields);    


    }

    if ($item["type"] == "list") {
        $values = array();
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblockId, "CODE" => $item["code"]));
        while ($enum_fields = $property_enums->GetNext()) {
            $temp["name"] = $enum_fields["VALUE"];
            $temp["sort"] = $enum_fields["SORT"];
            $temp["xml"] = $enum_fields["XML_ID"];
            $vvv[] = $temp;
        }

        $newProp = array();

        $oldProp = array();

        foreach ($item["values"] as $val) {
            $hasProp = false;
            $newValProp = array();
            foreach ($vvv as $xxx) {
                if ($xxx["xml"] == $val["xml"]) {
                    if ($xxx["name"] != $val["name"]) {
                        $newValProp["name"] = $val["name"];
                        $newValProp["xml"] = $val["xml"];
                        $newValProp["sort"] = $val["sort"];
                        $newValProp["id"] = $xxx["id"];
                        $oldProp[] = $newValProp;
                    }
                    $hasProp = true;
                    break;
                }
            }
            if (!$hasProp) {
                $newProp[] = $val;
            }

            
        }
        
        if (is_array($oldProp) && count($oldProp) > 0) {
            foreach ($oldProp as $i) {

                $val = array();
                $ibpenum = new CIBlockPropertyEnum;

                $val["VALUE"] = $i["name"];
                $val["SORT"] = $i["sort"];
                $val["xml"] = $i["xml"];
                $ibpenum->Update($i["id"], Array($val));
            }
        }

        if (is_array($newProp) && count($newProp) > 0) {
            foreach ($newProp as $i) {

                $val = array();
                $ibpenum = new CIBlockPropertyEnum;

                $val["PROPERTY_ID"] = $propId;
                $val["VALUE"] = $i["name"];

                if ($i["sort"] != "")
                    $val["SORT"] = $i["sort"];
                if ($i["xml"] != "")
                    $val["XML_ID"] = $i["xml"];

                $ibpenum->Add($val);
            }
        }

        
        
    }
    
}

$res = CIBlock::GetList(Array(), Array("ACTIVE"=> "Y", "CODE"=> "landings", "SITE_ID" => $arSite['ID']), true);
while($ar_res = $res->Fetch()) {
    $iblockIdLanding = $ar_res;
}

$rrr = array(
    0 => array(
        "name" => Loc::getMessage("prop_block_type"),
        "code" => "BLOCK_TYPE",
        "type" => "list",
        "values" => array(
            0 => array(
                "name" => Loc::getMessage("prop_numer_list"),
                "sort" => "230",
                "xml" => "type_number_list"
            ),
            1 => array(
                "name" => Loc::getMessage("prop_quest_answ"),
                "sort" => "800",
                "xml" => "type_question-answer"
            ),
            2 => array(
                "name" => Loc::getMessage("prop_form"),
                "sort" => "900",
                "xml" => "type_form"
            ),
        )
    ),
    1 => array(
        "name" => Loc::getMessage("prop_padding_top"),
        "code" => "PADDING_TOP",
        "type" => "list",
        "values" => array(
            0 => array(
                "name" => Loc::getMessage("prop_padd_small"),
                "sort" => "10",
                "xml" => "small"
            ),
            1 => array(
                "name" => Loc::getMessage("prop_padd_middle_s"),
                "sort" => "20",
                "xml" => "middle"
            ),
            2 => array(
                "name" => Loc::getMessage("prop_padd_middle_b"),
                "sort" => "30",
                "xml" => "high"
            ),
            3 => array(
                "name" => Loc::getMessage("prop_padd_big"),
                "sort" => "40",
                "xml" => "big"
            ),
            4 => array(
                "name" => Loc::getMessage("prop_padd_vbig"),
                "sort" => "50",
                "xml" => "v_big"
            ),
        )
    ),
    2 => array(
        "name" => Loc::getMessage("prop_padding_bottom"),
        "code" => "PADDING_BOTTOM",
        "type" => "list",
        "values" => array(
            0 => array(
                "name" => Loc::getMessage("prop_padd_small"),
                "sort" => "10",
                "xml" => "small"
            ),
            1 => array(
                "name" => Loc::getMessage("prop_padd_middle_s"),
                "sort" => "20",
                "xml" => "middle"
            ),
            2 => array(
                "name" => Loc::getMessage("prop_padd_middle_b"),
                "sort" => "30",
                "xml" => "high"
            ),
            3 => array(
                "name" => Loc::getMessage("prop_padd_big"),
                "sort" => "40",
                "xml" => "big"
            ),
            4 => array(
                "name" => Loc::getMessage("prop_padd_vbig"),
                "sort" => "50",
                "xml" => "v_big"
            ),
        )
    ),
    3 => array(
        "name" => Loc::getMessage("buttons"),
        "code" => "BUTTONS",
        "type" => "string",
        "descr" => "Y",
        "many" => "Y",
        "sort" => "60"
    ),
    4 => array(
        "name" => Loc::getMessage("prop_rad_buttons"),
        "code" => "BUTTONS_ROUNDED",
        "type" => "list",
        "sort" => 65,
        "values" => array(
            0 => array(
                "name" => "Y",
                "sort" => "10",
            ),
        )
    ),
    5 => array(
        "name" => Loc::getMessage("prop_pozition_descr_ban"),
        "code" => "PROP_BANNER_4",
        "type" => "list",
        "sort" => 130,
        "values" => array(
            0 => array(
                "name" => Loc::getMessage("prop_center"),
                "sort" => "10",
                "xml" => "center"
            ),
            1 => array(
                "name" => Loc::getMessage("prop_left"),
                "sort" => "20",
                "xml" => "left"
            ),
            2 => array(
                "name" => Loc::getMessage("prop_right"),
                "sort" => "30",
                "xml" => "right"
            ),
        )
    ),
    6 => array(
        "name" => Loc::getMessage("prop_darken_color"),
        "code" => "PROP_BANNER_5",
        "type" => "list",
        "sort" => 140,
        "values" => array(
            0 => array(
                "name" => "Y",
                "sort" => "10",
            ),
        )
    ),
    7 => array(
        "name" => Loc::getMessage("prop_link_form"),
        "code" => "PROP_QUEST_4",
        "type" => "string",
        "sort" => 630
    ),
    8 => array(
        "name" => Loc::getMessage("prop_pic_item"),
        "code" => "PROP_BUY_6",
        "type" => "file",
        "sort" => 835
    ),
    9 => array(
        "name" => Loc::getMessage("prop_quest_answ"),
        "code" => "PROP_QUES_ANSW_1",
        "type" => "link_elem",
        "sort" => 1000,
        "many" => "Y",
        "iblock_link" => array(
            "code" => "faq",
            "name" => Loc::getMessage("prop_quest_answ")
        )
    ),
    10 => array(
        "name" => Loc::getMessage("prop_id_iblock_form"),
        "code" => "PROP_FORM_1",
        "type" => "number",
        "sort" => 1100
    ),
    11 => array(
        "name" => Loc::getMessage("prop_type_mail_s"),
        "code" => "PROP_FORM_2",
        "type" => "string",
        "sort" => 1110
    ),
    12 => array(
        "name" => Loc::getMessage("prop_view_form"),
        "code" => "PROP_FORM_3",
        "type" => "list",
        "sort" => 1120,
        "values" => array(
            0 => array(
                "name" => Loc::getMessage("prop_form_default"),
                "sort" => "10",
                "xml" => "popups"
            ),
            1 => array(
                "name" => Loc::getMessage("prop_transparent"),
                "sort" => "20",
                "xml" => "landing"
            ),
        )
    ),
    13 => array(
        "name" => Loc::getMessage("prop_form_darken"),
        "code" => "PROP_FORM_4",
        "type" => "list",
        "sort" => 1130,
        "values" => array(
            0 => array(
                "name" => "Y",
                "sort" => "10",
            ),
        )
    ),
    14 => array(
        "name" => Loc::getMessage("prop_list_element_type1"),
        "code" => "PROP_NUMBER_LIST_2",
        "type" => "string",
        "many" => "Y",
        "sort" => 1200,
    ),
    15 => array(
        "name" => Loc::getMessage("prop_list_element_type2"),
        "code" => "PROP_NUMBER_LIST_1",
        "type" => "link_elem",
        "many" => "Y",
        "sort" => 1230,
        "iblock_link" => array(
            "code" => "elem_number_list",
            "name" => Loc::getMessage("prop_elems_nlist_land")
        )
    ),

);

foreach ($rrr as $item) {
    createElem($item, $iblockIdLanding);
}

$properties = CIBlockProperty::GetList(array('sort' => 'asc', 'name' => 'asc'), array('ACTIVE' => 'Y', 'IBLOCK_ID' => $iblockIdLanding["ID"]));
$arReplace = array();
while ($arProp = $properties->GetNext()) {
    $arReplace['#'.$arProp['CODE'].'#'] = $arProp['ID'];
}

$s = Loc::getMessage("form", $arReplace);

if (count($arReplace) > 0) {
    CUserOptions::SetOption(
        'form',
        'form_element_'.$iblockIdLanding["ID"],
        array(
        'tabs' => $s,
        )
    );
}

?>