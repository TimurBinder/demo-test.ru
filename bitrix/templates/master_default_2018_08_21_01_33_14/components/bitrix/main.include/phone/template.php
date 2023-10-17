<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

$arPhones = $arParams['PHONES'];

if ($arPhones > 0):
?>
<?php foreach ($arPhones as $phone): if (empty($phone)) continue; ?>
    <a href="tel:<?=preg_replace('/\D/', '', $phone)?>" class="b-head-phone">
        <span class="b-head-icon hidden-lg hidden-md">
            <svg class="icon-svg icon-svg-phone"><use xlink:href="#svg-phone"></use></svg>
        </span>
        <span class="b-head-phone__text"><?=$phone?></span>
    </a>
<?php endforeach;?>
<?php endif;
