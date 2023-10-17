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
<div class="product-item-detail-info-container product-item-detail-info-container-brand">

	<?php if (isset($arResult['BRANDS'][$sBrandCode])): ?>
		<?php
		if (isset($arResult['BRANDS'][$sBrandCode])) {
			$sBrandUrl = $arResult['BRANDS'][$sBrandCode]['DETAIL_PAGE_URL'];
		} else {
			$sBrandUrl = $arResult['PROPERTIES'][$sBrandPropCode]['FILTER_URL'];
		}
		?>
		<div class="product-item-detail__brand product-item-brand">
			<?php if ($arResult['BRANDS'][$sBrandCode]['PREVIEW_PICTURE']): ?>
				<a class="product-item-brand__canvas" href="<?=$sBrandUrl?>">
					<img class="product-item-brand__pic" src="<?=$arResult['BRANDS'][$sBrandCode]['PREVIEW_PICTURE']['RESIZE']['src']?>" alt="<?=$arResult['BRANDS'][$sBrandCode]['PREVIEW_PICTURE']['ALT']?>">
				</a>
			<?php endif; ?>
			<div class="product-item-brand__body">
				<span class=""><?=Loc::getMessage('RS.MASTER.BCE_MASTER.PRODUCT_BRAND')?></span><br>
				<a href="<?=$sBrandUrl?>">
				<?php
				if (isset($arResult['DISPLAY_PROPERTIES'][$sBrandPropCode]['DISPLAY_VALUE'])) {
					echo $arResult['DISPLAY_PROPERTIES'][$sBrandPropCode]['DISPLAY_VALUE'];
				} else {
					echo $sBrandCode;
				}
				?>
				</a>
			</div>
		</div>
	<?php endif; ?>

    <?php if ($arResult['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale')): ?>
        <div class="product-item-detail__gift">
            <?=$APPLICATION->GetViewContent('product-item-detail__gift')?>
        </div>
    <?php endif; ?>

</div>