<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;

$content = '';
?>
<?php ob_start(); ?>
<div class="b-head-search js-head-search">
    <a href="#" class="b-head-search__icon js-head-search__open">
        <svg class="icon-svg icon-svg-zoom"><use xlink:href="#svg-zoom"></use></svg>
    </a>
    <form action="<?=$arResult["FORM_ACTION"]?>" class="b-head-search__form js-head-search__form" style="display: none">
        <div class="form-group">
        <?php if($arParams["USE_SUGGEST"] === "Y"): ?>
            <?php $APPLICATION->IncludeComponent(
              "bitrix:search.suggest.input",
              "header",
              array(
                  "NAME" => "q",
                  "VALUE" => '',
                  "INPUT_SIZE" => 15,
                  "DROPDOWN_SIZE" => 10
              ),
              $component, array("HIDE_ICONS" => "Y")
            );?>
        <?php else: ?>
            <input class="form-control" type="text" name="q" value="" size="15" maxlength="50">
        <?php endif; ?>
        </div>
        <input name="s" type="submit" class="btn btn-primary" value="<?=Loc::getMessage("RS.SEARCH_BUTTON");?>" />
        <span class="b-head-search__close js-head-search__close">
            <svg class="icon-svg icon-svg-cross"><use xlink:href="#svg-cross"></use></svg>
        </span>
    </form>
</div>
<?php
$content = ob_get_flush();
$APPLICATION->AddViewContent('rs_head_search', $content);
