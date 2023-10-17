<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

if(isset($arParams['URL_PARAMS'])) {
    $arParams['BTN_LINK'] = str_replace(array_keys($arParams['URL_PARAMS']), array_values($arParams['URL_PARAMS']), $arParams['BTN_LINK']);
}
?>
<div class="b-ask-question<?php if ($arParams['BLOCK_TYPE'] == 'wide') echo ' b-ask-question--wide'?>">
    <div class="b-ask-question__container">
        <div class="b-ask-question__mark"><svg class="icon-svg"><use xlink:href="#svg-question"></use></svg></div>
        <div class="b-ask-question__content">
            <?php
            if($arResult["FILE"] <> '') {
                include($arResult["FILE"]);
            }
            ?>
        </div>
        <div class="b-ask-question__btns">
            <a href="<?=$arParams['BTN_LINK']?>" data-type="ajax" class="btn btn-primary" title="<?=$arParams['BTN_TEXT']?>"><?=$arParams['BTN_TEXT']?></a>
        </div>
    </div>
</div>
