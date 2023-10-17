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
<div class="product-item-detail-actions js-buy" id="<?=$itemIds['BASKET_ACTIONS_ID']?>" style="display: <?=($actualItem['CAN_BUY'] ? '' : 'none')?>;">
    <?php
    if ($showAddBtn)
    {
        ?>
        
            <a class="btn btn-lg <?=$showButtonClassName?> product-item-detail-buy-button js-buy__add2cart" id="<?=$itemIds['ADD_BASKET_LINK']?>"
                href="javascript:void(0);">
                <span><?=$arParams['MESS_BTN_ADD_TO_BASKET']?></span>
            </a>
            <a href="<?=$arParams['BASKET_URL']?>" class="btn btn-lg btn-primary js-buy__incart"><?=Loc::getMessage('RS.MASTER.BCE_MASTER.GOTO_CART')?></a>
        <?
    }

    if ($showBuyBtn)
    {
        ?>
            <a class="btn btn-lg <?=$buyButtonClassName?> product-item-detail-buy-button" id="<?=$itemIds['BUY_LINK']?>"
                href="javascript:void(0);">
                <span><?=$arParams['MESS_BTN_BUY']?></span>
            </a>
        <?
    }

    /*
    
    */
?>
</div>

<a class="btn btn-lg btn-link product-item-detail-buy-button" id="<?=$itemIds['NOT_AVAILABLE_MESS']?>"
    href="javascript:void(0)"
    rel="nofollow" style="display: <?=(!$actualItem['CAN_BUY'] ? '' : 'none')?>;">
    <?=$arParams['MESS_NOT_AVAILABLE']?>
</a>

<?

if ($showSubscribe)
{
    ?>
        <?
        $APPLICATION->IncludeComponent(
            'bitrix:catalog.product.subscribe',
            'fancy',
            array(
                'PRODUCT_ID' => $arResult['ID'],
                'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                'BUTTON_CLASS' => 'btn btn-lg '.$showButtonClassName.' product-item-detail-buy-button',
                'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
                'USE_CAPTCHA' => 'Y', //$arParams['USE_CAPTCHA'],
				'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );
        ?>
    <?
}

if ($showAskBtn)
{
    ?>
        <a class="btn btn-lg <?=$askButtonClassName?> product-item-detail-buy-button" id="<?=$itemIds['PRODUCT_ASK_LINK_ID']?>"
            data-type="ajax" data-fancybox="ask"
            title="<?=$arParams['MESS_BTN_ASK']?>"
            href="<?=str_replace('#ELEMENT_ID#', $actualItem['ID'], $arParams['LINK_BTN_ASK'])?>">
            <span><?=$arParams['MESS_BTN_ASK']?></span>
        </a>
    <?
}
