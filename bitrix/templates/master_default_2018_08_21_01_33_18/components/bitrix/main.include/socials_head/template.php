<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

$arIcons = CUtil::JsObjectToPhp($arParams['~SOCIAL_ICONS']);

if (count($arIcons) > 0):
?>
<span class="b-head-socials js-shares">
    <a class="b-head-socials__share js-shares__btn" href="#">
        <svg class="icon-svg icon-svg-share"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-share"></use></svg>
    </a>
    <span class="b-head-socials__icons">
        <?php foreach ($arIcons as $arIcon): ?>
        <a class="b-head-socials__icon" href="<?=$arIcon[0]?>" target="_blank">
            <svg class="icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-<?=$arIcon[1]?>"></use></svg>
        </a>
        <?php endforeach; ?>
    </span>
</span>
<?php endif;
