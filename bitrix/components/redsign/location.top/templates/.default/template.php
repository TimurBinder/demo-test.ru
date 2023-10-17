<?php

use Bitrix\Main\Loader;

/**
 * @var CMain $APPLICATION
 * @var CBitrixComponentTemplate $this
 * @var RedsignLocationTop $component
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


if (Loader::includeModule('redsign.devfunc')) {
    $myCity = \Redsign\DevFunc\Sale\Location\Location::getMyCity();
}
?>
<script data-skip-moving>
if (!window.RSLocationChange) {
  function RSLocationChange(id) {
    if (RS.Location && id != RS.Location.getCityId()) {
      RS.Location.change(id)
    }
  }
}
</script>
<div class="b-locations-top">
    <?php foreach ($arResult['ITEMS'] as $arItem): ?>
    <a href="#" onclick="RS.Location.change('<?=$arItem['ID']?>')"><?=$arItem['LNAME']?></a>
    <?php endforeach; ?>
</div>
