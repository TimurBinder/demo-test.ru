<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;
?>
<div id="line-reviews">
<?php
if ($arParams['IS_AJAX_REQUEST'] == 'Y') {
    global $APPLICATION;
    $APPLICATION->RestartBuffer();
}
foreach ($arResult['ITEMS'] as $arItem):
$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
?>
<div class="b-review b-review--line" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
    <div class="b-review__face">
    <?php if (isset($arItem['PREVIEW_PICTURE']) && isset($arItem['PREVIEW_PICTURE']['SRC'])): ?>
        <img class="b-review__img" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
    <?php endif; ?>
    </div>
    <div class="b-review__content">
        <div class="b-review__author">
            <?php if (!empty($arParams['RS_AUTHOR_JOB']) && isset($arItem['DISPLAY_PROPERTIES'][$arParams['RS_AUTHOR_JOB']])): ?>
            <div class="b-review__author-job"><?=$arItem['DISPLAY_PROPERTIES'][$arParams['RS_AUTHOR_JOB']]['DISPLAY_VALUE']?></div>
            <?php endif; ?>

            <?php if (!empty($arParams['RS_AUTHOR_NAME']) && isset($arItem['DISPLAY_PROPERTIES'][$arParams['RS_AUTHOR_NAME']])): ?>
            <div class="b-review__author-name"><?=$arItem['DISPLAY_PROPERTIES'][$arParams['RS_AUTHOR_NAME']]['DISPLAY_VALUE']?></div>
            <?php endif; ?>
        </div>
        <div class="b-review__message"><?=$arItem['PREVIEW_TEXT']?></div>
    </div>
</div>
<?php endforeach; ?>
</div>
<?php
if ($arParams['IS_AJAX_REQUEST'] == 'Y') {
  die();
}


if ($arParams['IS_AJAX_PAGER'] == 'Y' && !empty($arResult['NAV_RESULT']) && ($arResult['NAV_RESULT']->NavPageNomer < $arResult['NAV_RESULT']->NavPageCount)) {
    $arNavParams = array(
        'NAV_NUM' => $arResult['NAV_RESULT']->NavNum,
        'NAV_PAGE_NOMER' => $arResult['NAV_RESULT']->NavPageNomer,
        'NAV_PAGE_COUNT' => $arResult['NAV_RESULT']->NavPageCount
    );
    ?><div class="text-center"><a href="" class="btn btn-more" data-type="ajax-nav" data-load-to="#line-reviews" data-nav-options='<?=\Bitrix\Main\Web\Json::encode($arNavParams)?>'><?=Loc::getMessage('RS.SHOW_MORE');?></a></div><?
}
