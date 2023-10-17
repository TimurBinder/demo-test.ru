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
use \Bitrix\Main\ModuleManager;

$arLimitInfo = array(
	'MANY' => array(
		'MESS' => $arParams['MESS_RELATIVE_QUANTITY_MANY'],
		'CLASS' => 'is-instock',
		'ICON' => 'available',
	),
	'FEW' => array(
		'MESS' => $arParams['MESS_RELATIVE_QUANTITY_FEW'],
		'CLASS' => 'is-limited',
		'ICON' => 'available',
	),
	'NONE' => array(
		'MESS' => $arParams['MESS_NOT_AVAILABLE'],
		'CLASS' => 'is-outofstock',
		'ICON' => 'not-available',
	),
);

if ($haveOffers)
{
	if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
	{
		?>
		<span class="product-item-detail-limit <?=$arCurLimitInfo['CLASS']?>" id="<?=$itemIds['QUANTITY_LIMIT']?>" style="display: none;">
			<?php
			if (strlen($arParams['MESS_SHOW_MAX_QUANTITY']) > 0)
			{
				?><span><?=$arParams['MESS_SHOW_MAX_QUANTITY']?></span><?php
			}
			?>
			<svg class="product-item-detail-limit-icon icon-svg" data-entity="quantity-limit-icon"><use xlink:href="#svg-<?=$arCurLimitInfo['ICON']?>"></use></svg>

			<?php
			if ($showStocks)
			{
				?>
				<a class="product-item-detail-limit-quantity anchor js-link-scroll" data-entity="quantity-limit-value" href="#<?=$itemIds['ELEMENT_STOCKS']?>"></a>
				<?php
			}
			else
			{
				?>
				<span class="product-item-detail-limit-quantity" data-entity="quantity-limit-value"></span>
				<?php
			}
			?>
		</span>
		<span class="product-item-detail-limit <?=$arLimitInfo['NONE']['CLASS']?>"
			id="<?=$itemIds['NOT_AVAILABLE_MESS']?>"
			style="display: <?=($actualItem['CAN_BUY'] ? 'none' : '')?>;">
			<?php
			if (strlen($arParams['MESS_SHOW_MAX_QUANTITY']) > 0)
			{
				?><span><?=$arParams['MESS_SHOW_MAX_QUANTITY']?></span><?php
			}
			?>
			<svg class="product-item-detail-limit-icon icon-svg"><use xlink:href="#svg-<?=$arLimitInfo['NONE']['ICON']?>"></use></svg>
			<?php
			if ($showStocks)
			{
				?>
				<a class="product-item-detail-limit-quantity anchor js-link-scroll" href="#<?=$itemIds['ELEMENT_STOCKS']?>"><?=$arLimitInfo['NONE']['MESS']?></a>
				<?php
			}
			else
			{
				?>
				<span class="product-item-detail-limit-quantity"><?=$arLimitInfo['NONE']['MESS']?></span>
				<?php
			}
			?>
		</span>
		<?
	}
}
else
{
	if (
		$measureRatio
		&& (float)$actualItem['CATALOG_QUANTITY'] > 0
		&& $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
		&& $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
	)
	{
		
		if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR'])
		{
			$arCurLimitInfo = $arLimitInfo['MANY'];
		}
		else
		{
			$arCurLimitInfo = $arLimitInfo['FEW'];
		}
		?>
		<span class="product-item-detail-limit <?=$arCurLimitInfo['CLASS']?>" id="<?=$itemIds['QUANTITY_LIMIT']?>">
			<?php
			if (strlen($arParams['MESS_SHOW_MAX_QUANTITY']) > 0)
			{
				?><span><?=$arParams['MESS_SHOW_MAX_QUANTITY']?></span><?php
			}
			?>
			<svg class="product-item-detail-limit-icon icon-svg"><use xlink:href="#svg-<?=$arCurLimitInfo['ICON']?>"></use></svg>
			<?php
			if ($showStocks)
			{
				?>
				<a class="product-item-detail-limit-quantity anchor js-link-scroll" href="#<?=$itemIds['ELEMENT_STOCKS']?>">
					<?php
					if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
					{
						echo $arCurLimitInfo['MESS'];
					}
					else
					{
						echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
					}
					?>
				</a>
				<?php
			}
			else
			{
				?>
				<span class="product-item-detail-limit-quantity">
					<?php
					if ($arParams['SHOW_MAX_QUANTITY'] === 'M')
					{
						echo $arCurLimitInfo['MESS'];
					}
					else
					{
						echo $actualItem['CATALOG_QUANTITY'].' '.$actualItem['ITEM_MEASURE']['TITLE'];
					}
					?>
				</span>
				<?php
			}
			?>
		</span>
		<?
	}
	elseif (!$actualItem['CAN_BUY'])
	{
		?>
		<span class="product-item-detail-limit <?=$arLimitInfo['NONE']['CLASS']?>"
			id="<?=$itemIds['NOT_AVAILABLE_MESS']?>">
			<?php
			if (strlen($arParams['MESS_SHOW_MAX_QUANTITY']) > 0)
			{
				?><span><?=$arParams['MESS_SHOW_MAX_QUANTITY']?></span><?php
			}
			?>
			<svg class="product-item-detail-limit-icon icon-svg"><use xlink:href="#svg-<?=$arLimitInfo['NONE']['ICON']?>"></use></svg>
			<?php
			if ($showStocks)
			{
				?>
				<a class="product-item-detail-limit-quantity anchor js-link-scroll" href="#<?=$itemIds['ELEMENT_STOCKS']?>"><?=$arLimitInfo['NONE']['MESS']?></a>
				<?php
			}
			else
			{
				?>
				<span class="product-item-detail-limit-quantity"><?=$arLimitInfo['NONE']['MESS']?></span>
				<?php
			}
			?>
		</span>
		<?
	}
}
