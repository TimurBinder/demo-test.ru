<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

use \Bitrix\Main\Localization\Loc;
?>
<div class="product-item-detail-info-container<?/* product-item-detail-compare-container*/?>">

    <?php if ($arParams['USE_FAVORITE'] == 'Y'): ?>
        <span class="product-item-detail-info-section-link favorite-link js-favorite" id="<?=$itemIds['FAVORITE_LINK_ID']?>">
            <span class="favorite-link__link">
                <svg class="favorite-link__icon icon-svg"><use xlink:href="#svg-favorite-main"></use></svg>
                <span class="favorite-link__text" data-entity="favorite-title"><?=Loc::getMessage('RS.MASTER.BCE_MASTER.FAVORITE_ADD')?></span>
            </span>
        </span>
    <?php endif; ?>

    <?php if ($arParams['DISPLAY_COMPARE']): ?>
        <span class="product-item-detail-info-section-link compare-link">
            <label class="compare-link__link" id="<?=$itemIds['COMPARE_LINK']?>">
                <input type="checkbox" data-entity="compare-checkbox">
                <svg class="compare-link__icon icon-svg"><use xlink:href="#svg-compare-main"></use></svg>
                <span class="compare-link__text" data-entity="compare-title"><?=$arParams['MESS_BTN_COMPARE']?></span>
            </label>
        </span>
    <?php endif; ?>

</div>
<?