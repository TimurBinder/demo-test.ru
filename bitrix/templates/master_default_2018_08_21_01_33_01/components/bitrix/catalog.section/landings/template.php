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

                $mainClass = "";
                $mainStyle = "";

                $name = "";
                if ($arItem["PROPERTIES"]["BLOCK_NAME"]["VALUE"] != "")
                    $name = $arItem["PROPERTIES"]["BLOCK_NAME"]["VALUE"];

                $arrPropClass = array("BORDER", "PADDING_TOP", "PADDING_BOTTOM", "IMAGE_SIZE");

                foreach ($arrPropClass as $prop) {
                    if ($arItem["PROPERTIES"][$prop]["VALUE_XML_ID"] != "")
                        $mainClass .= " landing__".strtolower($prop)."__".$arItem["PROPERTIES"][$prop]["VALUE_XML_ID"];
                }

                if ($arItem["DISPLAY_PROPERTIES"]["BACKGROUND_IMAGE"]["FILE_VALUE"]["SRC"] != "")
                    $mainStyle .= " background-image:url(".$arItem["DISPLAY_PROPERTIES"]["BACKGROUND_IMAGE"]["FILE_VALUE"]["SRC"].");";
                else if ($arItem["PROPERTIES"]["COLOR_BACKGROUND"]["VALUE"] != "")
                    $mainStyle .= " background-color:#".$arItem["PROPERTIES"]["COLOR_BACKGROUND"]["VALUE"].";";

                if ($arItem["PROPERTIES"]["NAME_COLOR"]["VALUE"] != "")
                    $name = '<span style="color:#fff">'.$name.'</span>';
            ?>
                <div data-id="<?=$arItem['ID']?>" id="<?=$this->GetEditAreaId($arItem["ID"]);?>" data-sectionId="<?=$arItem["IBLOCK_SECTION_ID"]?>" class="landing__block <?=$mainClass?>" <?=($mainStyle!= "" ? 'style="'.$mainStyle.'"' : '');?>>
                    <?php include($_SERVER["DOCUMENT_ROOT"].$path."/blocks/".$arItem["PROPERTIES"]["BLOCK_TYPE"]["VALUE_XML_ID"].".php"); ?>
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
