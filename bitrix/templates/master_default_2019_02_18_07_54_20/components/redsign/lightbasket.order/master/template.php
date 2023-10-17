<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$skipProperties = isset($arParams['SKIP_PROPERTIES']) ? $arParams['SKIP_PROPERTIES']: array();
$commentField = null;
?>
<div class="container">
<?php if ($arResult['ORDER_SUCCESS'] == 'Y'): ?>
    <?php $APPLICATION->SetTitle(Loc::getMessage('RS_LIGHTBASKET_ORDER_SUCCESS')); ?>
    <div class="container-fluid">
        <div class="b-cart-empty">
            <div class="b-cart-empty__icon"><svg class="icon-svg"><use xlink:href="#svg-thumbs-up"></use></svg></div>
            <h3 class="b-cart-empty__title"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_SUCCESS_TITLE');?></h3>
            <div class="b-cart-empty__note"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_SUCCESS_NOTE');?></div>
        </div>
    </div>
    <?$APPLICATION->IncludeFile(SITE_DIR."include/template/order/popular.php", array(), array("MODE" => "html"))?>
    <?php if (!empty($arParams['RS_VK_ID']) || !empty($arParams['RS_FB_PAGE'])): ?>
        <div class="container-fluid">
            <div class="l-community-widgets">
                <h3 class="l-community-widgets__title"><?=Loc::getMessage('RS_LIGHTBASKET_COMMUNITY_WIDGET_TITLE');?></h3>
                <?php if (!empty($arParams['RS_VK_ID'])): ?>
                <div class="b-community-widget">
                    <?php $this->addExternalJS('//vk.com/js/api/openapi.js'); ?>
                    <div id="vk_groups" style="width: 100%;"></div>
                    <script type="text/javascript">
                        //VK.Widgets.Group("vk_groups", {mode: 0, width: 'auto', height: '205'}, 20003922);
                        VK.Widgets.Group("vk_groups", {mode: 0, width: 'auto', height: '205'}, <?=$arParams['RS_VK_ID']?>);
                    </script>
                </div>
                <?php endif; ?>
                <?php if (!empty($arParams['RS_FB_PAGE'])): ?>
                    <div class="b-community-widget">
                        <script>(function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.9";
                        fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));</script>
                        <? /** <div class="fb-page" data-href="https://www.facebook.com/redsignRU/" data-height="225" data-width="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"><blockquote cite="https://www.facebook.com/redsignRU/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/redsignRU/">Facebook</a></blockquote></div> **/?>
						<div class="fb-page" data-href="<?=$arParams['RS_FB_PAGE']?>" data-height="225" data-width="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"><blockquote cite="https://www.facebook.com/redsignRU/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/redsignRU/">Facebook</a></blockquote></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php else: ?>
	<div class="b-order">
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
		<?php if (!empty($arResult['ITEMS'])): ?>
			<div class="b-order-cart">
				<h5 class="b-order-cart__title"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_ORDER_LIST')?></h5>
				<div class="b-order-cart__table">
				
					<table class="b-order-cart-table">
						<tbody>
							<?php
							$i = 0;
							foreach ($arResult['ITEMS'] as $arItem):
								$i++;
							?>
							<tr>
								<td class="b-order-cart-table__index-cell"><?=$i?></td>
								<td class="b-order-cart-table__name-cell"><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="b-order-cart-table__name-link"><?=$arItem['NAME']?> <?=($arItem['COLOR']?'('.$arItem['COLOR'].')':'')?><?=($arItem['KIND']?'<br />('.$arItem['KIND'].')':'')?></a></td>
								<?if($arItem['PRICE']==0):?>
								<td> 
									&nbsp;
								</td>
								<td> 
									&nbsp;
								</td>
								<td> 
									Запрос
								</td>
								<?else:?>
								<td class="b-order-cart-table__price-cell">
									<?php if ($arItem['DISCOUNT'] > 0): ?>
									<div class="product-item-price-current discount"><?=str_replace('#', $arItem['DISCOUNT_PRICE_FORMATTED'], $arItem['CURRENCY'])?></div>
									<div class="product-item-price-old"><?=str_replace('#', $arItem['PRICE_FORMATTED'], $arItem['CURRENCY'])?></div>
									<?php else: ?>
									<span class="product-item-price-current"><?=str_replace('#', $arItem['PRICE_FORMATTED'], $arItem['CURRENCY'])?></span>
									<?php endif; ?>
								</td>
								<?
								$measure = Loc::getMessage('RS_LIGHTBASKET_ORDER_MEASURE_NAME');
								if(isset($arItem['PROPERTIES']['MEASURE']) && $arItem['PROPERTIES']['MEASURE']!='')$measure = $arItem['PROPERTIES']['MEASURE'];
								?>
								<td class="b-order-cart-table__quantity-cell"><?=$arItem['QUANTITY']?> <?=$measure;?></td>
								<td class="b-order-cart-table__sum-cell"><?=str_replace('#', $arItem['FULL_PRICE_FORMATTED'], $arItem['CURRENCY'])?></td>
								<?endif;?>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="b-order-cart__note">
					<?=Loc::getMessage('RS_LIGHTBASKET_ORDER_CART_NOTE');?><br><br>
					<a href="<?=$arParams['PATH_TO_CART']?>"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_EDIT_CART');?></a>
				</div>
			</div>
			<div class="b-cart__summary">
				<div class="b-cart__summary-price" style="margin-right: 80px;">
					Итого:
					<span id="SUMMARY_PRICE">
					<?php foreach ($arResult['PRICE'] as $arPrice): ?>
					  <b><?=str_replace('#', $arPrice['DISCOUNT_PRICE_FORMATTED'], $arPrice['CURRENCY'])?> </b>
					<?php endforeach; ?>
					</span>
				</div>
			</div>
		<?php endif; ?>
		<form class="b-order-form" name="ORDER_FORM" method="POST">
			<?=bitrix_sessid_post()?>
			<h5 class="b-order-form__title"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_HEADING_COSTUMER_DATA');?></h5>
			<div class="b-order-form__note"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_COSTUMER_DATA_NOTE');?></div>
			<div class="row">
				<?php foreach ($arResult['FIELDS'] as $arField): ?>
					<?php
					if ($arField['PROPERTY_TYPE'] == 'S'):
						if ($arField['CODE'] == 'COMMENT') {
							$commentField = $arField;
							continue;
						}
					?>
					<div class="col col-md-6 form-group">
						<label for="FIELD_<?=$arField['CODE']?>" class="b-order-form__label">
							<?=$arField['NAME']?>
							<?php if ($arField['IS_REQUIRED'] == 'Y'): ?><span class="required">*</span><?php endif; ?>
							<?php if ($arField['USER_TYPE'] == 'HTML'): ?>
								<textarea <?php if(!empty($arField['PATTERN'])) echo ' pattern="'.$arField['PATTERN'].'"'; ?> style="max-width: 100%;" id="FIELD_<?=$arField['CODE']?>" name="FIELD_<?=$arField['CODE']?>" class="form-control"<?php if ($arField['IS_REQUIRED'] == 'Y') echo ' required';?>></textarea>
							<?php else: ?>
								<input type="<?=$arField['INPUT_TYPE']?>"<?php if(!empty($arField['PATTERN'])) echo ' pattern="'.$arField['PATTERN'].'"'; ?>  id="FIELD_<?=$arField['CODE']?>" v name="FIELD_<?=$arField['CODE']?>" value="<?=$arField['CURRENT_VALUE']?>" class="form-control"<?php if ($arField['IS_REQUIRED'] == 'Y') echo ' required';?><?php if(isset($arField['MASK'])) echo ' data-inputmask="\'mask\': \''.$arField['MASK'].'\'"'; ?>>
							<?php endif; ?>
						</label>
					</div>
					<?php elseif ($arField['PROPERTY_TYPE'] == 'L' && is_array($arField['VALUES'])): ?>
						<div class="col col-md-6 form-group">
							<label for="FIELD_<?=$arField['CODE']?>" class="b-order-form__label">
								<?=$arField['NAME']?>
								<?php if ($arField['IS_REQUIRED'] == 'Y'): ?><span class="required">*</span><?php endif; ?>
								<select class="form-control" name="FIELD_<?=$arField['CODE']?>" id="FIELD_<?=$arField['CODE']?>">
								<?php foreach ($arField['VALUES'] as $i => $arValue): ?>
									<option <?php if ((empty($arField['CURRENT_VALUE']) && $i == 0) || $arField['CURRENT_VALUE'] == $arValue['ID']): ?>selected="selected"<?php endif; ?> value="<?=$arValue['ID']?>"><?=$arValue['VALUE']?></option>
								<?php endforeach; ?>
								</select>
							</label>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php if ($commentField): ?>
				<h5 class="b-order-form__title"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_HEADING_COMMENT')?></h5>
				<textarea style="max-width: 100%; height: 160px;" id="FIELD_<?=$commentField['CODE']?>" name="FIELD_<?=$commentField['CODE']?>" class="form-control" placeholder="<?=Loc::getMessage('RS_LIGHTBASKET_INPUT_PLACEHOLDER')?>"></textarea>
			<?php endif; ?>

			<div class="b-order-form__note-req"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER_REQ_NOTE');?></div>
			<div class="b-order-form__foot">
				<?php if ($arParams['SHOW_CONFIRM'] == 'Y'): ?>
				<div class="b-order-form__confirm">
					<?$APPLICATION->IncludeComponent(
						"bitrix:main.userconsent.request",
						"form",
						array(
							"ID" => $arParams['USER_CONSENT_ID'],
							"IS_CHECKED" => 'Y',
							"AUTO_SAVE" => "Y",
							"IS_LOADED" => 'Y',
							"INPUT_NAME" => "ORDER_CONFIRM_PDP",
							// 'SUBMIT_EVENT_NAME' => '',
							'REPLACE' => array(
								'button_caption' => Loc::getMessage('RS_LIGHTBASKET_ORDER_CREATE_ORDER'),
								// 'fields' => array()
							)
						)
					);?>
				</div>
				<?php endif; ?>
				<input type="submit" class="btn btn-primary" value="<?=Loc::getMessage('RS_LIGHTBASKET_ORDER_CREATE_ORDER');?>">
			</div>
		</form>
	</div>
</div>
<script>
  $('[name="ORDER_FORM"').validator({focus: false});
  $('[name="ORDER_FORM"]').find('input[data-inputmask]').inputmask();
</script>
<?php endif; ?>