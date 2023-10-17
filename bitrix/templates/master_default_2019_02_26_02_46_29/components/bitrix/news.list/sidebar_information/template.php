<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

if (!empty($arResult['ITEMS'])):
?>
<?php
foreach ($arResult['ITEMS'] as $arItem):
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
?>
<div class="b-sidebar-information" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
    <div class="b-sidebar-information__bookmark"><svg class="icon-svg"><use xlink:href="#svg-information"></use></svg></div>
    <div class="b-sidebar-information__title"><?=$arItem['NAME']?></div>
    <div class="b-sidebar-information__text"><?=$arItem['PREVIEW_TEXT']?></div>
    <?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['RS_PROPERTY_LINK']])): ?>
    <a class="b-sidebar-information__link" href="<?=$arItem['DISPLAY_PROPERTIES'][$arParams['RS_PROPERTY_LINK']]['DISPLAY_VALUE']?>"><?=Loc::getMessage('RS.NL_MORE_LINK');?></a>
    <?php endif; ?>
</div>
<?php endforeach; ?>
<?php endif;
