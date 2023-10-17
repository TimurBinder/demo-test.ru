<?php
use Bitrix\Main\Localization\Loc;

$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-default';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-default';
$showAskBtn = in_array('ASK', $arParams['ADD_TO_BASKET_ACTION']);
$askButtonClassName = in_array('ASK', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-default';

$linkAsk = $arParams["LINK_BTN_ASK"];

if ($linkAsk == "")
    $linkAsk = "/include/forms/product_ask/?element_id=#ELEMENT_ID#";

$messBtnAsk = $arItem["PROPERTIES"]["PROP_BUY_2"]["VALUE"] != "" ? $arItem["PROPERTIES"]["PROP_BUY_2"]["VALUE"] : $arParams['MESS_BTN_ASK'];
$messBtnAddBasket = $arItem["PROPERTIES"]["PROP_BUY_1"]["VALUE"] != "" ? $arItem["PROPERTIES"]["PROP_BUY_1"]["VALUE"] : $arParams['MESS_BTN_ADD_TO_BASKET'];

//$mainId = $this->GetEditAreaId($arResult['ELEMENT_ID']);

?>
    <div class="container landing__buy-center">
        <div class="landing__buy-title">
            <?php if ($name != ""):?>
                <h3><?=$name?></h3>
            <?php endif;?>
        </div>
        <?php if ($arProp["PROP_BUY_6"]["FILE_VALUE"]["SRC"]):?>            
            <div class="landing__buy-pic">
                <img src="<?=$arProp["PROP_BUY_6"]["FILE_VALUE"]["SRC"]?>">
            </div>
        <?php endif;?>
        <div class="landing__buy-left">
            <div class="landing__buy-price">
                <?php if (!empty($arResult["ELEMENT"]["PRICES"])) : ?>
                    <span class="product-item-detail-price-current <?=($arResult["ELEMENT"]['PRICES']['DISCOUNT'] > 0 ? 'discount' : '');?>">
                        <?=$arResult["ELEMENT"]["PRICES"]["PRINT_PRICE"]?>
                    </span>
                    <?php if ($arResult["ELEMENT"]['PRICES']['DISCOUNT'] > 0):?>
                        <span class="product-item-detail-price-old">
                            <?=$arResult["ELEMENT"]["PRICES"]["PRINT_BASE_PRICE"]?>
                        </span>
                    <?php endif;?>
                <?php else:?>
                    <span class="product-item-detail-price-current">
                        <?=Loc::getMessage("RS.MASTER.PRICE_REQUEST")?>
                    </span>
                <?php endif;?>
            </div>
        </div>
        <div>
            <?php if (count($arProp["BUTTONS"]["VALUE"]) > 0):?>
                <?include('buttons_block.php');?>
            <?php else:?>
                <?php if (!empty($arResult["ELEMENT"]["PRICES"])) : ?>
                    <div class="product-item-detail-actions js-buy" data-productid="<?=$arResult['ELEMENT']['ID']?>">
                        <?php if ($showAddBtn) :?>
                            
                                <a class="btn btn-lg <?=$showButtonClassName?> product-item-detail-buy-button js-buy__add2cart" href="javascript:void(0);">
                                    <span><?=$messBtnAddBasket?></span>
                                </a>
                                <a href="<?=$arParams['PATH_TO_CART']?>" class="btn btn-lg btn-primary js-buy__incart"><?=Loc::getMessage('RS.MASTER.BCE_MASTER.GOTO_CART')?></a>
                        <?php
                        endif;

                        if ($showAskBtn):
                            $arResult['LINK_BTN_ASK'] = str_replace('#ELEMENT_ID#', $arResult['ELEMENT']['ID'], $arParams['LINK_BTN_ASK']);
                            ?>
                                <a class="btn btn-lg <?=$askButtonClassName?> product-item-detail-buy-button"
                                    data-type="ajax"
                                    title="<?=$messBtnAsk?>"
                                    href="<?=$arResult['LINK_BTN_ASK']?>">
                                    <span><?=$messBtnAsk?></span>
                                </a>
                            <?
                        endif;
                        ?>
                    </div>
                <?php else: ?>
                    <? $linkAsk = str_replace('#ELEMENT_ID#', $arResult['ELEMENT']['ID'], $linkAsk); ?>
                    <div class="product-item-detail-actions js-buy" data-productid="<?=$arResult['ELEMENT']['ID']?>">
                        <a class="btn btn-lg btn-default product-item-detail-buy-button"
                            data-type="ajax"
                            title="<?=$messBtnAsk?>"
                            href="<?=$linkAsk?>">
                            <span><?=$messBtnAsk?></span>
                        </a>
                    </div>
                
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

