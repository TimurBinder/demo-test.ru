<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

// echo "<pre>" . print_r($arResult['ITEMS'], true) . "</pre>";
?>

<div class="row mt-5">

<?foreach($arResult['ITEMS'] as $plan):?>
    <!-- Карточка -->
    <div class="col-xxl-4 col-md-6 col-12 mt-5">
        <div class="card">
            <input type="hidden" name="roomsCount" value="<?=$plan['PROPS']['ROOMS_COUNT']['VALUE']?>">
            <div class="row justify-content-between">
                <p class="appartment-class col-4">
                    <?=explode(' ', $plan['NAME'])[1]?>
                </p>
                <figure class="col-5 d-flex flex-column align-items-center">
                    <figcaption>Площадь</figcaption> <span class="square"><?=$plan['PROPS']['SQUARE']['VALUE']?> м2</span>
                </figure>
            </div>
            <div class="row mt-5">
                <img src="<?=CFile::GetPath($plan['PROPS']['NO_BG_PHOTO']['VALUE'][0])?>" alt="План">
            </div>
            <div class="row mt-5">
                <a href="<?=$plan['PROPS']['LINK']['VALUE']?>" class="button">Подробнее</a>
            </div>
        </div>
    </div>
<?endforeach;?>    
</div>