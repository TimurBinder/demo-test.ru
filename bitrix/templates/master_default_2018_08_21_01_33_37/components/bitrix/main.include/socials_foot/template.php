<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

$arIcons = CUtil::JsObjectToPhp($arParams['~SOCIAL_ICONS']);

use \Bitrix\Main\Localization\Loc;

if ($arIcons > 0):
?>
<div class="b-foot-socials">
    <div class="b-foot-socials__title"><?=Loc::getMessage('RS.MI_SOCIALS_LINKS');?></div>
    <div class="b-foot-socials__icons">
        <?php foreach ($arIcons as $arIcon): ?>
        <a class="b-foot-socials__icon" href="<?=$arIcon[0]?>" target="_blank" style="background-color:<?=$arIcon[2]?>">
            <svg class="icon-svg"><use xlink:href="#svg-<?=$arIcon[1]?>"></use></svg>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif;
