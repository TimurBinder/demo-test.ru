<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

$this->setFrameMode(true);
?>

<a class="b-sidebar-button" href="<?=$arParams['LINK_SIDE_WIDGET']?>"<?=($arParams['IS_LINK_POPUP'] == 'Y' ? ' data-type="ajax"': '')?>>
    <div class="b-sidebar-button__icon">
        <svg class="icon-svg">
            <use xlink:href="#svg-<?=$arParams['NAME_SIDE_SVG']?>"></use>
        </svg>
    </div>
    <div class="b-sidebar-button__text">
        <?php
            if ($arResult["FILE"] <> '')
                include($arResult["FILE"]);
        ?>
    </div>
</a>
