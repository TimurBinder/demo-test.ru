<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;

if (!function_exists('getStringCatalogStoreAmountEx')){
	function getStringCatalogStoreAmountEx($amount, $minAmount, $arReturn){
		$amount = (float)$amount;
		$minAmount = (float)$minAmount;
		$message = $arReturn['NOT_MUCH_GOOD'];
		if ($amount <= 0)
			$message = $arReturn['ABSENT'];
		elseif ($amount >= $minAmount)
			$message = $arReturn['LOT_OF_GOOD'];
		return $message;
	}
}

$arStockStatus = array(
    'NOT_MUCH_GOOD' => Loc::getMessage('RS.MASTER.BCSA_DETAIL.NOT_MUCH_GOOD'),
    'ABSENT' => Loc::getMessage('RS.MASTER.BCSA_DETAIL.ABSENT'),
    'LOT_OF_GOOD' => Loc::getMessage('RS.MASTER.BCSA_DETAIL.LOT_OF_GOOD'),
);
$arStockStatusIcon = array(
    'NOT_MUCH_GOOD' => '<svg class="icon-svg"><use xlink:href="#svg-available"></use></svg>',
    'ABSENT' => '<svg class="icon-svg"><use xlink:href="#svg-not-available"></use></svg>',
    'LOT_OF_GOOD' => '<svg class="icon-svg"><use xlink:href="#svg-available"></use></svg>',
);

$arResult['JS']['ICONS'] = $arStockStatusIcon;
$arResult['JS']['MESSAGES']['NOT_MUCH_GOOD'] = $arStockStatus['NOT_MUCH_GOOD'];
$arResult['JS']['MESSAGES']['ABSENT'] = $arStockStatus['ABSENT'];
$arResult['JS']['MESSAGES']['LOT_OF_GOOD'] = $arStockStatus['LOT_OF_GOOD'];

$iAmountTotal = 0;
?>

<?php $this->SetViewTarget('product-item-detail__stocks'); ?>

<?if(!empty($arResult["STORES"]) && $arParams["MAIN_TITLE"] != ''):?>
	<h4><?=$arParams["MAIN_TITLE"]?></h4>
<?endif;?>

<div class="bx_storege" id="catalog_store_amount_div">
	<?if(!empty($arResult["STORES"])):?>
	<ul id="c_store_amount">
		<?foreach($arResult["STORES"] as $pid => $arProperty):?>
            <?php $iAmountTotal += $arProperty['REAL_AMOUNT']; ?>
			<li class="stock<?=(isset($arProperty['REAL_AMOUNT']) && $arProperty['REAL_AMOUNT'] <= 0 ? ' is-empty': '')?>"<?=($arParams['SHOW_EMPTY_STORE'] == 'N' && isset($arProperty['REAL_AMOUNT']) && $arProperty['REAL_AMOUNT'] <= 0 ? ' style="display:none;"' : '')?>>
				<?if (isset($arProperty["TITLE"])):?>
					<?/*<a href="<?=$arProperty["URL"]?>"><?=$arProperty["TITLE"]?></a><br />*/?>
                    <span class="stock__head">
                        <span class="stock__name" href="<?=$arProperty["URL"]?>"><?=$arProperty["TITLE"]?></span>
<?/*
                        <span>
                        <?if ($arParams['SHOW_GENERAL_STORE_INFORMATION'] == "Y") :?>
                            <?=GetMessage('BALANCE')?>:
                        <?else:?>
                            <?=GetMessage('S_AMOUNT')?>
                        <?endif;?>
                        </span>
*/?>
                        <span class="stock__balance" id="<?=$arResult['JS']['ID']?>_<?=$arProperty['ID']?>">
                            <span class="stock__icon"><?=getStringCatalogStoreAmountEx($arProperty["REAL_AMOUNT"], $arParams['MIN_AMOUNT'], $arStockStatusIcon)?></span>
                            <span class="stock__val"><?=($arParams["USE_MIN_AMOUNT"] == 'Y') ? getStringCatalogStoreAmountEx($arProperty["REAL_AMOUNT"], $arParams['MIN_AMOUNT'], $arStockStatus) : $arProperty["AMOUNT"] ?></span>
                        </span>

                    </span>
				<?endif;?>
				<?if (isset($arProperty["IMAGE_ID"]) && !empty($arProperty["IMAGE_ID"])):?>
					<span class="stock__schedule"><?=GetMessage('S_IMAGE')?> <?=CFile::ShowImage($arProperty["IMAGE_ID"], 200, 200, "border=0", "", true);?></span><br />
				<?endif;?>
				<?if (isset($arProperty["PHONE"])):?>
					<span class="stock__tel"><?=GetMessage('S_PHONE')?> <?=$arProperty["PHONE"]?></span><br />
				<?endif;?>
				<?if (isset($arProperty["SCHEDULE"])):?>
					<span class="stock__schedule"><?=GetMessage('S_SCHEDULE')?> <?=$arProperty["SCHEDULE"]?></span><br />
				<?endif;?>
				<?if (isset($arProperty["EMAIL"])):?>
					<span><?=GetMessage('S_EMAIL')?> <?=$arProperty["EMAIL"]?></span><br />
				<?endif;?>
				<?if (isset($arProperty["DESCRIPTION"])):?>
					<span><?=GetMessage('S_DESCRIPTION')?> <?=$arProperty["DESCRIPTION"]?></span><br />
				<?endif;?>
				<?if (isset($arProperty["COORDINATES"])):?>
					<span><?=GetMessage('S_COORDINATES')?> <?=$arProperty["COORDINATES"]["GPS_N"]?>, <?=$arProperty["COORDINATES"]["GPS_S"]?></span><br />
				<?endif;?>

				<?
				if (!empty($arProperty['USER_FIELDS']) && is_array($arProperty['USER_FIELDS']))
				{
					foreach ($arProperty['USER_FIELDS'] as $userField)
					{
						if (isset($userField['CONTENT']))
						{
							?><span><?=$userField['TITLE']?>: <?=$userField['CONTENT']?></span><br /><?
						}
					}
				}
				?>
			</li>
		<?endforeach;?>
		</ul>
	<?endif;?>
</div>
<?if (isset($arResult["IS_SKU"]) && $arResult["IS_SKU"] == 1):?>
	<script type="text/javascript">
		var obStoreAmount = new JCCatalogStoreSKU(<? echo CUtil::PhpToJSObject($arResult['JS'], false, true, true); ?>);
	</script>
	<?
endif;?>

<?php $this->EndViewTarget(); ?>

<?php $this->SetViewTarget('product-item-detail__stock'); ?>
    <span class="stock<?=($iAmountTotal <= 0 ? ' is-empty': '')?>">
        <span class="stock__balance" id="<?=$arResult['JS']['ID']?>_total">
            <span class="stock__icon"><?=getStringCatalogStoreAmountEx($iAmountTotal, $arParams['MIN_AMOUNT'], $arStockStatusIcon)?></span>
            <a class="stock__val anchor" href="#tab_stock"><?=($arParams["USE_MIN_AMOUNT"] == 'Y') ? getStringCatalogStoreAmountEx($iAmountTotal, $arParams['MIN_AMOUNT'], $arStockStatus) : $iAmountTotal ?></a>
        </span>
    </span>
<?php $this->EndViewTarget(); ?>