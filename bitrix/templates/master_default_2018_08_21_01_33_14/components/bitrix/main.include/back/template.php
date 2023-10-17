<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

\Bitrix\Main\Page\Asset::getInstance()->addString('<script src="https://yastatic.net/share2/share.js" async="async" charset="utf-8"></script>');

use \Bitrix\Main\Localization\Loc;
?>
<div class="b-back">
    <?php if ($arParams['USE_BACK_BUTTON'] == 'Y' ): ?>
    <a class="b-back__button" href="<?=$arParams['BACK_BUTTON_URL']?>">
        <span class="b-back__button-icon"><svg class="icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-reply"></use></svg></span>
        <?=Loc::getMessage('RS.MI_BACK_BUTTON');?>
    </a>
    <?php endif; ?>
    <?php if ($arParams['USE_SHARE'] == 'Y'): ?>
    <div class="b-back__share">
        <span><?=Loc::getMessage('RS.MI_BACK_SHARE')?></span>
        <div class="ya-share2"
            <?php if (isset($arParams['SOCIAL_SERVICES'])): ?>
                data-services="<?=$arParams['SOCIAL_SERVICES'];?>"
            <?php endif; ?>
            data-lang="<?=LANGUAGE_ID?>"></div>
    </div>
    <?php endif; ?>
</div>
