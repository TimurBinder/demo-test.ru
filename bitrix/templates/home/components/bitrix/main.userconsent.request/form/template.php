<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */

$config = \Bitrix\Main\Web\Json::encode($arResult['CONFIG']);
$inputId = 'CONSTENT_'.$arParams['ID'].'_'.htmlspecialcharsbx($arParams['INPUT_NAME']);
?>
<div data-bx-user-consent="<?=htmlspecialcharsbx($config)?>">
    <input  class="custom-checkbox" type="checkbox" value="Y" <?=($arParams['IS_CHECKED'] ? 'checked' : '')?> name="<?=htmlspecialcharsbx($arParams['INPUT_NAME'])?>" id="<?=$inputId?>" required>
    <label for="<?=$inputId?>"><?=$arResult['INPUT_LABEL']?></label>
</div>
