<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/* @var array $arParams */
/* @var array $arResult */

$config = \Bitrix\Main\Web\Json::encode($arResult['CONFIG']);

\Bitrix\Main\Diag\Debug::dump($arResult);
?>
<input  class="custom-checkbox" type="checkbox" value="Y" <?=($arParams['IS_CHECKED'] ? 'checked' : '')?> id="<?=htmlspecialcharsbx($arParams['INPUT_NAME'])?>" name="<?=htmlspecialcharsbx($arParams['INPUT_NAME'])?>" required>
<label for="<?=htmlspecialcharsbx($arParams['INPUT_NAME'])?>">1111111 <?=$arResult['INPUT_LABEL']?></label>
