<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

if (!empty($arResult['ITEMS'])):
?>
<?php foreach ($arResult['ITEMS'] as $arItem): if (empty($arItem['PREVIEW_PICTURE'])) return; ?>
<?php
$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
?>
<a href="<?=$arItem['DISPLAY_PROPERTIES'][$arParams['RS_LINK']]['VALUE']?>" class="b-sidebar-banner" id="<?=$this->GetEditAreaId($arItem['ID']);?>" <?php if ($arItem['PROPERTIES'][$arParams['RS_BLANK']]['VALUE'] != ''): ?>target="_blank"<?php endif; ?>>
    <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
</a>
<?php endforeach; ?>
<?php endif; ?>
