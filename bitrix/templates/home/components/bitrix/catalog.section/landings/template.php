<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$templateData = $arResult;


$path = $templateFolder;
if (count($arResult["ITEMS"]) > 0):
    foreach ($arResult["ITEMS"] as $arItem):

        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT'));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
        $strMainID = $this->GetEditAreaId($arItem['ID']);

        if ($arItem["PROPERTIES"]["BLOCK_TYPE"]["VALUE_XML_ID"]):
            $newPath = $_SERVER["DOCUMENT_ROOT"].$path."/blocks/".$arItem["PROPERTIES"]["BLOCK_TYPE"]["VALUE_XML_ID"].".php";
            if (file_exists($_SERVER["DOCUMENT_ROOT"].$path."/blocks/".$arItem["PROPERTIES"]["BLOCK_TYPE"]["VALUE_XML_ID"].".php")):

                $arProp = $arItem["DISPLAY_PROPERTIES"];
                $mainClass = "";
                $mainStyle = "";

                $name = "";

                if ($arProp["BLOCK_NAME"]["VALUE"] != "")
                    $name = $arItem["PROPERTIES"]["BLOCK_NAME"]["VALUE"];

                $arrPropClass = array("BORDER", "PADDING_TOP", "PADDING_BOTTOM", "IMAGE_SIZE", "MARGIN_TOP", "MARGIN_BOTTOM");

                foreach ($arrPropClass as $prop) {
                    if ($arItem["PROPERTIES"][$prop]["VALUE_XML_ID"] != "")
                        $mainClass .= " landing__".strtolower($prop)."__".$arItem["PROPERTIES"][$prop]["VALUE_XML_ID"];
                }

                if ($arProp["BACKGROUND_IMAGE"]["FILE_VALUE"]["SRC"] != "")
                    $mainStyle .= " background-image:url(".$arItem["DISPLAY_PROPERTIES"]["BACKGROUND_IMAGE"]["FILE_VALUE"]["SRC"].");";
                else if ($arItem["PROPERTIES"]["COLOR_BACKGROUND"]["VALUE"] != "")
                    $mainStyle .= " background-color:#".$arItem["PROPERTIES"]["COLOR_BACKGROUND"]["VALUE"].";";

                if ($arProp["NAME_COLOR"]["VALUE"] != "")
                    $name = '<span style="color:#'.$arItem["PROPERTIES"]["NAME_COLOR"]["VALUE"].'">'.$name.'</span>';

                $buttons = array();
                $primeButtons = array("BUY", "ADD2BASKET");
                if (count($arProp["BUTTONS"]["VALUE"]) > 0) {

                    $roundedButt = false;

                    if ($arProp["BUTTONS_ROUNDED"]["VALUE"] == "Y")
                        $roundedButt = true;

                    foreach ($arProp["BUTTONS"]["VALUE"] as $bKey=>$button) {
                        if (in_array($arProp["BUTTONS"]["DESCRIPTION"][$bKey], $primeButtons)) {
                            $b["CLASS"] = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-default';
                            $b["BUY"] = "Y";
                            $b["FORM"] = "N";
                        } elseif ($b["ACTION"]["0"] == "#") {
                            $b["ANCHOR"] = "Y";
                        } else {
                            $b["CLASS"] = in_array('ASK', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-default';
                            $b["FORM"] = "Y";
                        }
                        if ($roundedButt)
                            $b["CLASS"] .= " landing__button-rounded";
                        $b["TEXT"] = $button;
                        $b["ACTION"] = $arProp["BUTTONS"]["DESCRIPTION"][$bKey];

                        $buttons[] = $b;

                        unset($b);
                    }
                    
                }

            ?>
                <div data-id="<?=$arItem['ID']?>" id="<?=$this->GetEditAreaId($arItem["ID"]);?>" data-sectionId="<?=$arItem["IBLOCK_SECTION_ID"]?>" class="landing__block" <?=($mainStyle!= "" ? 'style="'.$mainStyle.'"' : '');?>>
                    <?php if ($arProp["PROP_FORM_4"]["VALUE"]):?>
                        <div class="landing__banner__shadow-form landing__banner__shadow-all"></div>
                    <?php endif;?>
                    <div id="elem_<?=$arItem['ID']?>" class="landing__main-block <?=$mainClass?>">
                        <?php include($_SERVER["DOCUMENT_ROOT"].$path."/blocks/".$arItem["PROPERTIES"]["BLOCK_TYPE"]["VALUE_XML_ID"].".php"); ?>
                    </div>
                </div>
            <?php else: ?>
                <div id="<?=$this->GetEditAreaId($arItem["ID"]);?>" class="container">
                    <div class="alert alert-warning"><?echo "block not found";?></div>
                </div>
            <?php
            endif;
        endif;        
    endforeach;
endif;
