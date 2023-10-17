<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var CMain $APPLICATION
 * @var CBitrixComponentTemplate $this
 * @var RedsignLightBasketBasket $component
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$skipProperties = array();

if (count($arResult['ITEMS']) > 0):
    ?>
    <div class="row">
        <div class="col col-md-12 text-right">
            <a class="aprimary" href="<?=$arResult['PATH_TO_CLEAR']?>"><?=Loc::getMessage('RS_LIGHTBASKET_CLEAR_BASKET');?></a>
        </div>
    </div>
    <div style="overflow-x: auto;min-height: 0.01%;margin-top: 10px;margin-bottom:10px;">
    <table class="table table-striped table-basket" style="border-collapse: separate;">
        <thead>
            <tr>
                <th class="name" colspan="2"><?=Loc::getMessage('RS_LIGHTBASKET_FIELD_NAME'); ?></th>
                <th><?=Loc::getMessage('RS_LIGHTBASKET_FIELD_PRICE'); ?></th>
                <th class="quantity"><?=Loc::getMessage('RS_LIGHTBASKET_FIELD_QUANTITY'); ?></th>
                <th><?=Loc::getMessage('RS_LIGHTBASKET_FIELD_FULL_PRICE'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($arResult['ITEMS'] as $id => $arItem): ?>
            <tr data-id="<?=$arItem['ID']?>" data-product-id="<?=$arItem['PRODUCT_ID']?>">
                <td>
                    <?php if (!empty($arItem['PREVIEW_PICTURE']['SRC'])): ?>
                    <div class="image">
                        <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" class="img-thumbnail" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
                    </div>
                    <?php endif; ?>
                </td>
                <td class="name">
                    <div class="description">
                        <?php if (strlen($arItem['DETAIL_PAGE_URL']) > 0): ?>
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
                        <?php else: ?>
                            <?=$arItem['NAME']?>
                        <?php endif; ?>
                        <div class="properties">
                        <?php
                        foreach ($arResult['PROPERTIES'] as $code => $arProperty):
                            if (in_array($code, $skipProperties)) {
                                continue;
                            }
                            ?>
                            <?php if (!empty($arItem['PROPERTIES'][$code])): ?>
                                <?=$arProperty['NAME']?>: <?=$arItem['PROPERTIES'][$code]?><br>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </td>
                <td class="price">
                <?php if ($arItem['DISCOUNT'] > 0): ?>
                    <div style="font-weight: bold;"><?=str_replace('#', $arItem['DISCOUNT_PRICE_FORMATTED'], $arItem['CURRENCY'])?></div>
                    <div style="text-decoration: line-through;"><?=str_replace('#', $arItem['PRICE_FORMATTED'], $arItem['CURRENCY'])?></div>
                <?php else: ?>
                    <?=str_replace('#', $arItem['PRICE_FORMATTED'], $arItem['CURRENCY'])?>
                <?php endif; ?>
                </td>
                <td class="quantity">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-quantity" data-type="minus" onclick="rsLightBasketOnClickQuantityBtn(<?=$arItem['ID']?>, 'minus')"><i class="fa fa-minus"></i></button>
                        </span>
                        <input type="text" name="quantity" class="form-control input-quantity" onchange="rsLightBasketUpdateQuantity(this, <?=$arItem['ID']?>)" id="QUANTITY_INPUT_<?=$arItem['ID']?>" value="<?=$arItem['QUANTITY']?>">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-quantity" data-type="plus" onclick="rsLightBasketOnClickQuantityBtn(<?=$arItem['ID']?>, 'plus')"><i class="fa fa-plus"></i></button>
                        </span>
                    </div>
                </td>
                <td class="fullprice" id="FULL_PRICE_<?=$arItem['ID']?>"><?=str_replace('#', $arItem['FULL_PRICE_FORMATTED'], $arItem['CURRENCY'])?></td>
                <td class="remove-btn"><a href="<?=$arItem['URL_TO_DELETE']?>"><?=Loc::getMessage('RS_LIGHTBASKET_LINK_TO_DELETE'); ?></a></td>
            </tr>

            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <div class="row">
        <div class="col col-md-12 text-right">
            <a class="btn btn-primary" href="<?=$arParams['PATH_TO_ORDER']?>"><?=Loc::getMessage('RS_LIGHTBASKET_NEW_ORDER');?></a>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info"><?=Loc::getMessage('RS_LIGHTBASKET_NO_ITEMS'); ?></div>
<?php endif; ?>
