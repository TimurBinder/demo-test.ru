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
<div class="product-item-detail-compare-container">
    <div class="product-item-detail-compare">
        <div class="checkbox">
            <label id="<?=$itemIds['COMPARE_LINK']?>">
                <input type="checkbox" data-entity="compare-checkbox">
                <span data-entity="compare-title"><?=$arParams['MESS_BTN_COMPARE']?></span>
            </label>
        </div>
    </div>
</div>
<?