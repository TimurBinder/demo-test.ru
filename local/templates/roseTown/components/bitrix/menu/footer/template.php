<?php
/*
 * Файл bitrix/components/bitrix/menu/templates/.default/template.php
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<?php if (!empty($arResult)): ?>
    <nav class="menu col-xxl-10 col-md-12 d-flex justify-content-between w-100">

    <?php
    foreach ($arResult as $arItem):
        if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;
    ?>
		<a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>

        
    <?php endforeach; ?>

    </nav>
<?php endif; ?>