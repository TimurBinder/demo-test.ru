<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

Loc::loadMessages(__FILE__);

if (count($arResult['ITEMS'])):
?>
<div class="l-staff">

    <?php if ($arParams['SHOW_TITLE'] == 'Y'): ?>
        <h2 class="l-staff__title">

            <?php
            $sSectionName = '';
            if (intval($arParams['PARENT_SECTION']) > 0 && strlen($arResult['PARENT_SECTION']['NAME']) > 0) {
                $sSectionName = $arResult['PARENT_SECTION']['NAME'];
            } elseif (count($arResult['NAME']) > 0) {
                $sSectionName = $arResult['NAME'];
            }
            ?>

            <?php if (isset($arParams['SECTION_PAGE_URL']) && strlen($arParams['SECTION_PAGE_URL']) > 0): ?>
                <a href="<?=$arParams['SECTION_PAGE_URL']?>"><?=$sSectionName?></a>
            <?php elseif (isset($arResult['PARENT_SECTION']['SECTION_PAGE_URL']) && strlen($arResult['PARENT_SECTION']['SECTION_PAGE_URL']) > 0): ?>
                <a href="<?=$arResult['PARENT_SECTION']['SECTION_PAGE_URL']?>"><?=$sSectionName?></a>
            <?php else: ?>
                <?=$sSectionName?>
            <?php endif; ?>

        </h2>
    <?php endif; ?>

    <?php if ($arParams['SHOW_DESCRIPTION'] == 'Y'): ?>
        <?php if (intval($arParams['PARENT_SECTION']) > 0 && strlen($arResult['PARENT_SECTION']['DESCRIPTION']) > 0): ?>
            <div class="b-note-text"><?=$arResult['PARENT_SECTION']['DESCRIPTION']?></div>
        <?php elseif (count($arResult['DESCRIPTION']) > 0): ?>
            <div class="b-note-text"><?=$arResult['DESCRIPTION']?></div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($arResult['ITEMS'] as $arItem):?>
        <div class="col-xs-12 col-sm-6 col-lg-4">
            <?php
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="b-employee" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <div class="b-employee__picture">
                    <?php if (!empty($arItem['PREVIEW_PICTURE'])):?>
                    <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
                    <?php endif; ?>
                </div>
                <div class="b-employee__name">
                    <?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_NAME']])): ?>
                    <?=$arItem['DISPLAY_PROPERTIES'][$arParams['PROP_NAME']]['DISPLAY_VALUE']?>
                    <?php endif; ?>
                </div>
                <div class="b-employee__position">
                    <?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_POSITION']])): ?>
                    <?=$arItem['DISPLAY_PROPERTIES'][$arParams['PROP_POSITION']]['DISPLAY_VALUE']?>
                    <?php endif; ?>
                </div>
                <div class="b-employee__description">
                    <?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_DESCRIPTION']])): ?>
                    <?=$arItem['DISPLAY_PROPERTIES'][$arParams['PROP_DESCRIPTION']]['DISPLAY_VALUE']?>
                    <?php endif; ?>
                </div>
                <div class="b-employee__contacts">
                  <?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_CONTACTS']])): ?>
                  <?=$arItem['DISPLAY_PROPERTIES'][$arParams['PROP_CONTACTS']]['DISPLAY_VALUE']?>
                  <?php endif; ?>
                </div>
                <div class="b-employee__social">
                <?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_SOCIAL']])): ?>
                    <?php foreach ($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_SOCIAL']]['VALUE'] as $index => $sLink): ?>
                      <a class="b-employee-social-icon" href="<?=$sLink?>">
                          <svg class="icon-svg"><use xlink:href="#svg-<?=$arItem['DISPLAY_PROPERTIES'][$arParams['PROP_SOCIAL']]['DESCRIPTION'][$index]?>"></use></svg>
                      </a>
                    <?php endforeach; ?>
                <?php endif; ?>
                </div>
                <div class="b-employee__quest">
                    <?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['PROP_IS_ASK']]) && $arItem['DISPLAY_PROPERTIES'][$arParams['PROP_IS_ASK']]['DISPLAY_VALUE'] == 'Y'): ?>
                    <a href="<?=str_replace('#ELEMENT_ID#', $arItem['ID'], $arParams['ASK_LINK'])?>" data-type="ajax"><?=Loc::getMessage('RS.ASK_QUESTION');?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif;
