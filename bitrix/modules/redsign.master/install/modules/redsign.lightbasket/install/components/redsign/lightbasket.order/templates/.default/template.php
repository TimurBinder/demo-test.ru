<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Diag\Debug;

$skipProperties = isset($arParams['SKIP_PROPERTIES') ? $arParams['SKIP_PROPERTIES']: array();

?>
<?php if ($arResult['ORDER_SUCCESS'] == 'Y'): ?>
    <h4><?=str_replace('#ORDER_ID#', $arResult['ORDER_ID'], Loc::getMessage('RS_LIGHTBASKET_ORDER_SUCCESS')); ?></h4>
    <p><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_SUCCESS_TEXT'); ?></p>
<?php else: $commentField = null; ?>
    <?php if (count($arResult['MESSAGES']['ERROR']) > 0): ?>
    <div class="alert alert-danger">
        <?php
        foreach ($arResult['MESSAGES']['ERROR'] as $arError):
            if (empty($arError)) {
                continue;
            }
        ?><?=$arError?><br><?php endforeach; ?>
    </div>
    <?php endif; ?>
    <form method="POST" action="/cart/order/">
        <?=bitrix_sessid_post()?>
        <div class="panel-group panel-group-order" data-toggle="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingCostumerData">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" href="#costumerData" aria-expanded="true" aria-controls="costumerData"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_HEADING_COSTUMER_DATA'); ?></a>
                    </h4>
                </div>
                <div id="costumerData" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingCostumerData">
                    <div class="panel-body">
                        <div class="row">
                            <?php foreach ($arResult['FIELDS'] as $arField):
                              if ($arField['CODE'] == 'COMMENT') {
                                  $commentField = $arField;
                                  continue;
                              }
                            ?>
                                <?php if ($arField['PROPERTY_TYPE'] == 'S'): ?>
                                <div class="col col-md-6 form-group">
                                      <label for="FIELD_<?=$arField['CODE']?>" class="control-label">
                                          <?=$arField['NAME']?>
                                          <?php if ($arField['IS_REQUIRED'] == 'Y'): ?><span class="required">*</span><?php endif; ?>
                                      </label>
                                      <?php if ($arField['USER_TYPE'] == 'HTML'): ?>
                                          <textarea style="max-width: 100%;" id="FIELD_<?=$arField['CODE']?>" name="FIELD_<?=$arField['CODE']?>" class="form-control"></textarea>
                                      <?php else: ?>
                                          <input type="text" id="FIELD_<?=$arField['CODE']?>" v name="FIELD_<?=$arField['CODE']?>" value="<?=$arField['CURRENT_VALUE']?>" class="form-control">
                                      <?php endif; ?>
                                </div>
                                <?php elseif ($arField['PROPERTY_TYPE'] == 'L' && is_array($arField['VALUES'])): ?>
                                <div class="col col-md-6 form-group">
                                    <label for="FIELD_<?=$arField['CODE']?>" class="control-label">
                                        <?=$arField['NAME']?>
                                        <?php if ($arField['IS_REQUIRED'] == 'Y'): ?><span class="required">*</span><?php endif; ?>
                                    </label>
                                    <select class="form-control" name="FIELD_<?=$arField['CODE']?>" id="FIELD_<?=$arField['CODE']?>">
                                    <?php foreach ($arField['VALUES'] as $i => $arValue): ?>
                                        <option <?php if ((empty($arField['CURRENT_VALUE']) && $i == 0) || $arField['CURRENT_VALUE'] == $arValue['ID']): ?>selected="selected"<?php endif; ?> value="<?=$arValue['ID']?>"><?=$arValue['VALUE']?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if ($arParams['SHOW_CONFIRM'] == 'Y'): ?>
                            <div class="col col-md-12 text-right form-group">
                                <input type="checkbox" name="ORDER_CONFIRM_PDP" id="ORDER_CONFIRM_PDP">
                                <label class="order-confirm" for="ORDER_CONFIRM_PDP"><?=!empty($arParams['CONFIRM_TEXT']) ? $arParams['CONFIRM_TEXT'] : Loc::getMessage('RS_LIGHTBASKET_ORDER_CONFIRM');?></label>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($commentField): ?>
            <div class="panel panel-default">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingComment">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" href="#comment" aria-expanded="true" aria-controls="comment"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_HEADING_COMMENT'); ?></a>
                        </h4>
                    </div>
                    <div id="comment" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingComment">
                        <div class="panel-body">
                            <div class="form-group">
                                <textarea style="max-width: 100%; height: 60px;" id="FIELD_<?=$commentField['CODE']?>" name="FIELD_<?=$commentField['CODE']?>" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; unset($commentField); ?>

        </div>
        <table class="table table-stripped table-basket">
            <thead>
                <th class="name" colspan="2"><?=Loc::getMessage('RS_LIGHTBASKET_FIELD_NAME'); ?></th>
                <th><?=Loc::getMessage('RS_LIGHTBASKET_FIELD_PRICE'); ?></th>
                <th class="quantity"><?=Loc::getMessage('RS_LIGHTBASKET_FIELD_QUANTITY'); ?></th>
                <th><?=Loc::getMessage('RS_LIGHTBASKET_FIELD_FULL_PRICE'); ?></th>
            </thead>
            <tbody>
                <?php foreach ($arResult['ITEMS'] as $id => $arItem): ?>
                    <tr data-id="<?=$id?>" data-product-id="<?=$arItem['PRODUCT_ID']?>">
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
                            foreach ($arResult['CATALOG_PROPERTIES'] as $code => $arProperty):
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
                        <td class="quantity"><?=$arItem['QUANTITY']?></td>
                        <td class="fullprice"><?=str_replace('#', $arItem['FULL_PRICE_FORMATTED'], $arItem['CURRENCY'])?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-right">
            <span><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_PRODCUTS'); ?>: <?=count($arResult['ITEMS'])?></span>
        </div>

        <div class="panel panel-order">
            <div class="panel-body">
                <div class="row orderline">
                    <div class="col col-xs-6 col-md-5 buttons">
                        <a class="btn btn-default" href="<?=$arParams['PATH_TO_CART']?>"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_EDIT_CART'); ?></a>
                    </div>
                    <div class="col col-xs-6 col-md-3 col-md-push-4 buttons text-right">
                        <input type="submit" class="btn btn-primary" value="<?=Loc::getMessage('RS_LIGHTBASKET_ORDER_CREATE_ORDER');?>">
                    </div>
                    <div class="col col-xs-12 col-md-4 col-md-pull-3 col-lg-pull-2 text-right">
                        <?=Loc::getMessage('RS_LIGHTBASKET_ORDER_SUMMARY'); ?>:
                        <?php foreach ($arResult['PRICE'] as $arPrice): ?>
                            <span class="price cool"><?=str_replace('#', $arPrice['DISCOUNT_PRICE_FORMATTED'], $arPrice['CURRENCY'])?> </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </form>
<?php endif; ?>
