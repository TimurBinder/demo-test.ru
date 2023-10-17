<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */

use \Bitrix\Main\Localization\Loc;

if ($bCanBuy)
{
	?>
	<!--noindex-->
	<div class="product-item-info-container product-item-button-container">
		<a class="btn btn-primary" href="<?=$item["BUY_URL"]?>" rel="nofollow">
			<?=GetMessage("CATALOG_COMPARE_BUY")?>
		</a>
	</div>
	<!--/noindex-->
	<?
}
elseif(
	!empty($arResult["PRICES"]) || is_array($item["PRICE_MATRIX"])
	|| !is_array($arResult['CATALOGS'][$item['IBLOCK_ID']])
)
{
	?>
	<div class="product-item-info-container product-item-button-container">
		<a class="btn btn-default" href="<?=$item['DETAIL_PAGE_URL']?>">
			<?=GetMessage("RS.MASTER.BCCR_CATALOG.MORE_INFO")?>
		</a>
	</div>
	<?
}
