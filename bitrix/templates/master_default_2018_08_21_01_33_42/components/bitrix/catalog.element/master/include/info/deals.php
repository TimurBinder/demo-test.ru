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

<?php if (is_array($arResult['PRODUCT_DEALS']) && count($arResult['PRODUCT_DEALS']) > 0): ?>
    
    <div class="product-item-detail-info-container">
    
        <?php foreach ($arResult['PRODUCT_DEALS'] as $arDeal): ?>
            <div class="product-item-detail-deal">
                <div class="product-item-detail-deal-body">
                    <div class="product-item-detail-deal-sticker"><?=Loc::getMessage('RS.MASTER.BCE_MASTER.PRODUCT_DEAL')?>:</div>
                    <a class="product-item-detail-deal-name" href="<?=$arDeal['DETAIL_PAGE_URL']?>"><?=$arDeal['NAME']?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>