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

$showDiscount = $price['DISCOUNT_VALUE'] < $price['VALUE'];
?>

<div class="product-item-info-container product-item-price-container">
	<div class="product-item-info-container-title"><?=Loc::getMessage('RS.MASTER.BCCR_CATALOG.PRICE')?>:</div>
	<span
		class="product-item-price-current<?=($price['DISCOUNT_VALUE'] < $price['VALUE'] ? ' discount' : '')?>">
		<?
		if (!empty($price))
		{
			echo $price['PRINT_DISCOUNT_VALUE'];
		}
		?>
	</span>
	<?
	if ($arParams['SHOW_OLD_PRICE'] === 'Y' && $price['DISCOUNT_VALUE'] < $price['VALUE'])
	{
		?>
		&nbsp;<span class="product-item-price-old">
			<?=$price['PRINT_VALUE']?>
		</span>
		<?
	}
	?>
</div>