<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

if (!isset($arParams['AUTH_URL'])) {
    $arParams['AUTH_URL'] = '/auth/';
}

if (!isset($arParams['PROFILE_URL'])) {
    $arParams['PROFILE_URL'] = '/personal/';
}

$this->setFrameMode(true);
?>
<div class="b-topline-user">
<?php $frame = $this->createFrame()->begin(); ?>
    <?php if ($arResult['FORM_TYPE'] == 'login'): ?>
    <a href="<?=$arParams['AUTH_URL']?>" class="b-topline-user__link"><svg class="icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-lock-main"></use></svg><?=Loc::getMessage('RS_ENTER');?></a>
    <?php else: ?>
    <a href="<?=$arParams['PROFILE_URL']?>" class="b-topline-user__link"><svg class="icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-user-main"></use></svg><?=$arResult['USER_LOGIN']?></a>
    <?php endif; ?>
<?php $frame->beginStub(); ?>
    <a href="<?=$arParams['AUTH_URL']?>" class="b-topline-user__link"><svg class="icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-lock-main"></use></svg><?=Loc::getMessage('RS_ENTER');?></a>
<?php $frame->end(); ?>
</div>
