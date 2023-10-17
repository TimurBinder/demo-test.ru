<?php

/**
 * @var CMain $APPLICATION
 * @var CBitrixComponentTemplate $this
 * @var Redsign\Components\FavoriteList $component
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


if ($arResult["COUNT"] > 0) {
    ?>
    <div class="rsfavorite"><?=GetMessage("RSFAVORITE_COUNT")?>: <?=$arResult["COUNT"]?></div>
    <?php
}
