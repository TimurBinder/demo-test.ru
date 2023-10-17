<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
  die();

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$sButtonAttributes = 'data-type="ajax"';
$isUseCRMForm = !empty($arParams['RS_FEEDBACK_B24_CRM_FORM_USE']) && $arParams['RS_FEEDBACK_B24_CRM_FORM_USE'] == 'Y';
if ($isUseCRMForm) {
    $sButtonAttributes = 'data-type="crm-form" data-form-id="'.$arParams['RS_FEEDBACK_B24_CRM_FORM_ID'].'" data-sec="'.$arParams['RS_FEEDBACK_B24_CRM_FORM_SEC'].'"';
}

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0):
?>
<div class="b-reviews">

    <?php if ($arParams['SHOW_DESCRIPTION'] == 'Y' || $arParams['SHOW_FEEDBACK_BUTTON'] == 'Y'): ?>
    <div class="b-reviews__top">
        <?php if ($arParams['SHOW_DESCRIPTION'] == 'Y' && !empty($arResult['DESCRIPTION'])): ?>
        <div class="b-note-text"><?=$arResult['DESCRIPTION']?></div>
        <?php endif; ?>
        <?php if ($arParams['SHOW_FEEDBACK_BUTTON'] == 'Y'): ?>
        <div class="b-reviews__new"><a href="<?=$arParams['FEEDBACK_BUTTON_LINK']?>" <?=$sButtonAttributes?> class="btn btn-default"><?=Loc::getMessage('RS.GIVE_FEEDBACK');?></a></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if($arParams['DISPLAY_TOP_PAGER'] && $arParams['IS_AJAX_PAGER'] != 'Y'):?>
    <div class="text-center"><?=$arResult["NAV_STRING"]?></div>
    <?php endif; ?>

    <?php if($arParams['USE_OWL'] == 'Y'): ?>
    <div class="b-reviews__items owl owl-carousel owl-theme" data-slider="true" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arParams['OWL_PARAMS'])?>'>
    <?php else: ?>
    <div class="b-reviews__items">
    <?php endif;?>

    <?php
    $sModeItemsFile = $_SERVER['DOCUMENT_ROOT'].$templateFolder.'/'.$arParams['MODE'].'_items.php';

    if (file_exists($sModeItemsFile)) {
        include($sModeItemsFile);
    }
    ?>
    </div>

    <?php if($arParams['DISPLAY_BOTTOM_PAGER'] && $arParams['IS_AJAX_PAGER'] != 'Y'):?>
    <div class="text-center"><?=$arResult["NAV_STRING"]?></div>
    <?php endif; ?>
</div>
<?php endif;
