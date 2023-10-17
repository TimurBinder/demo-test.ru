<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

if (count($arResult['ITEMS']) > 0):
?>
<div class="b-faq js-faq">
    <?php if (isset($arResult['FILTER']['VALUES'])): ?>
    <div class="b-faq__btns btn-group">
        <?php foreach ($arResult['FILTER']['VALUES'] as $arFilter): ?>
        <a href="#" class="btn btn-default" data-filter="<?=$arFilter['XML_ID']?>"><?=$arFilter['VALUE']?></a>
        <?php endforeach; ?>
        <a href="#" class="btn btn-primary" data-filter><?=Loc::getMessage('RS.FILTER_ALL');?></a>
    </div>
    <?php endif; ?>
    <div class="panel-group" id="faq_accordion" role="tablist" aria-multiselectable="true">
        <?php
        foreach ($arResult['ITEMS'] as $index => $arItem):
            $isOpen = $index === 0;
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

        ?>
        <div class="panel panel-master" id="<?=$this->GetEditAreaId($arItem['ID']);?>" data-type="<?=$arItem['DISPLAY_PROPERTIES'][$arParams['RS_TYPE_PROPERTY']]['VALUE_XML_ID']?>" data-code="<?=$arItem['CODE']?>">
            <div class="panel-heading" role="tab" id="heading_<?=$arItem['CODE']?>">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#faq_accordion" href="#collapse_<?=$arItem['CODE']?>" aria-expanded="true" aria-controls="collapse_<?=$arItem['CODE']?>" class="<?php if (!$isOpen) echo 'collapsed';?>"><?=$arItem['NAME']?></a>
                </h4>
            </div>
            <div id="collapse_<?=$arItem['CODE']?>" class="panel-collapse collapse<?php if ($isOpen) echo ' in';?>" role="tabpanel" aria-labelledby="heading_<?=$arItem['CODE']?>">
                <div class="panel-body"><?=$arItem['PREVIEW_TEXT']?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php
endif;
