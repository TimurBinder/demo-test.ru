<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
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

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Redsign\Master\MyTemplate;

$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$mainId = $this->GetEditAreaId('compare');
$itemIds = array(
	'ID' => $mainId,
	'TABLE' => $mainId.'table',
);

$jsParams = array(
	'ITEMS' => array(
		// 'item' => 'item',
	),
	'CONFIG' => array(
		'NAME' => $arParams['NAME'],
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'TEMPLATE_FOLDER' => $templateFolder,
	),
	'VISUAL' => $itemIds,
);

$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);

$isAjax = (
	$request->get('ajax_id') == $itemIds['ID']
	&& $request->get('ajax_action') == 'Y'
);

if (isset($_REQUEST[$arParams['ACTION_VARIABLE']]))
{
	switch (ToUpper($_REQUEST[$arParams['ACTION_VARIABLE']]))
	{
		case "COMPARE_CLEAR":
			if (isset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"]))
			{
				$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]] = array();
				$arResult['ITEMS'] = array();
			}
			break;
	}
}
?>

<div class="compare" id="<?=$itemIds['ID']?>">
<?php
if ($isAjax)
{
	$APPLICATION->restartBuffer();
}
else
{
	$frame = $this->createFrame($itemIds['ID'], false)->begin('');
}

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS'])):
?>

<div class="compare__top">
	<span><?=GetMessage("CATALOG_SHOWN_CHARACTERISTICS")?>:</span>
	<?php if (!$arResult["DIFFERENT"]): ?>
		<span><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></span>
		<a class="anchor" href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=Y'; ?>" rel="nofollow"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></a>
	<?php else: ?>
		<a class="anchor" href="<? echo $arResult['COMPARE_URL_TEMPLATE'].'DIFFERENT=N'; ?>" rel="nofollow"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></a>
		<span><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></span>
	<?php endif; ?>

	<span class="compare__clear anchor" onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($arResult['COMPARE_URL_TEMPLATE'].$arParams['ACTION_VARIABLE'].'=COMPARE_CLEAR')?>');"><?=GetMessage('RS.MASTER.BCCR_CATALOG.COMPARE_CLEAR')?></span>
</div>

<div class="compare__page" id="<?=$itemIds['TABLE']?>">
<?php
	$iTableCol = count($arResult['ITEMS']);
	$arFieldsHide = array(
		'NAME',
		'PREVIEW_PICTURE',
		'DETAIL_PICTURE',
		'CATALOG_QUANTITY',
		'CATALOG_MEASURE',
		'CATALOG_QUANTITY_TRACE',
		'CATALOG_CAN_BUY_ZERO',
		'CAN_BUY',
	);

	$iMinColumsCount = 3;
/*
<?
if (!empty($arResult["ALL_FIELDS"]) || !empty($arResult["ALL_PROPERTIES"]) || !empty($arResult["ALL_OFFER_FIELDS"]) || !empty($arResult["ALL_OFFER_PROPERTIES"]))
{
?>
<div class="bx_filtren_container">
	<h5><?=GetMessage("CATALOG_COMPARE_PARAMS")?></h5>
	<ul><?
	if (!empty($arResult["ALL_FIELDS"]))
	{
		foreach ($arResult["ALL_FIELDS"] as $propCode => $arProp)
		{
			if (!isset($arResult['FIELDS_REQUIRED'][$propCode]))
			{
		?>
		<li><span onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">
			<span><input type="checkbox" id="PF_<?=$propCode?>"<? echo ($arProp["IS_DELETED"] == "N" ? ' checked="checked"' : ''); ?>></span>
			<label for="PF_<?=$propCode?>"><?=GetMessage("IBLOCK_FIELD_".$propCode)?></label>
		</span></li>
		<?
			}
		}
	}
	if (!empty($arResult["ALL_OFFER_FIELDS"]))
	{
		foreach($arResult["ALL_OFFER_FIELDS"] as $propCode => $arProp)
		{
			?>
			<li><span onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">
		<span><input type="checkbox" id="OF_<?=$propCode?>"<? echo ($arProp["IS_DELETED"] == "N" ? ' checked="checked"' : ''); ?>></span>
		<label for="OF_<?=$propCode?>"><?=GetMessage("IBLOCK_OFFER_FIELD_".$propCode)?></label>
	</span></li>
		<?
		}
	}
	if (!empty($arResult["ALL_PROPERTIES"]))
	{
		foreach($arResult["ALL_PROPERTIES"] as $propCode => $arProp)
		{
	?>
		<li><span onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">
			<span><input type="checkbox" id="PP_<?=$propCode?>"<?echo ($arProp["IS_DELETED"] == "N" ? ' checked="checked"' : '');?>></span>
			<label for="PP_<?=$propCode?>"><?=$arProp["NAME"]?></label>
		</span></li>
	<?
		}
	}
	if (!empty($arResult["ALL_OFFER_PROPERTIES"]))
	{
		foreach($arResult["ALL_OFFER_PROPERTIES"] as $propCode => $arProp)
		{
	?>
		<li><span onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($arProp["ACTION_LINK"])?>')">
			<span><input type="checkbox" id="OP_<?=$propCode?>"<? echo ($arProp["IS_DELETED"] == "N" ? ' checked="checked"' : ''); ?>></span>
			<label for="OP_<?=$propCode?>"><?=$arProp["NAME"]?></label>
		</span></li>
	<?
		}
	}
	?>
	</ul>
</div>
<?
}
?>
*/
	$sTableHTML = '';
	?>
	<div class="row">
		<div class="compare__names col col-xs-6 col-md-3 col-lg-2d4" data-entity="column-names">
			<table>
				<thead>
				<tr class="compare__row">
					<td></td>

					<?php ob_start(); ?>
					<thead>
					<tr class="compare__row">
					<?php foreach ($arResult['ITEMS'] as $item): ?>
						<?php
						// $bHaveOffer = $item['ID'] != $item['PARENT_ID'];

						//$this->AddEditAction($item['ID'], $item['EDIT_LINK'], $strEdit);
						//$this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], $strDelete, $arDeleteParams);
						// $bHaveOffer = false;
						if ($item['ID'] == $item['PARENT_ID'])
						{
							//$bHaveOffer = true;
						}

						$jsParams['ITEMS'][] = $item['ID'];

						if ($arResult['MODULES']['catalog'] && $arResult['MODULES']['sale'])
						{
						}
						else
						{
							$item['MIN_PRICE'] = $item['RS_PRICES'];
							$item['CAN_BUY'] = false; //$item['MIN_PRICE']['RATIO_PRICE'] > 0 && $item['MIN_PRICE']['RATIO_BASE_PRICE'] > 0;
						}

						$productTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
							? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
							: $item['NAME'];

						$productAlt = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_ALT']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_ALT'] != ''
							? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_ALT']
							: $item['NAME'];

						$picture = (!empty($item['PREVIEW_PICTURE']['SRC']) && is_array($item['PREVIEW_PICTURE']))
							? $item['PREVIEW_PICTURE']['SRC']
							: (
								!empty($item['DETAIL_PICTURE']['SRC']) && is_array($item['DETAIL_PICTURE'])
									? $item['DETAIL_PICTURE']['SRC']
									: $this->GetFolder().'/images/no_photo.png'
							);

						$itemHasDetailUrl = isset($item['DETAIL_PAGE_URL']) && $item['DETAIL_PAGE_URL'] != '';
						?>
						<td>
							<div class="product-item-container" data-entity="compare-item">
								<div class="product-item">
									<a class="product-item-image-wrapper" href="<?=$item['DETAIL_PAGE_URL']?>">
											<?php
											include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-image.php'));
											include(MyTemplate::getTemplatePart($templateFolder.'/include/picture-labels.php'));
											?>
										
										<span class="product-item-del" onclick="<?=$obName?>.MakeAjaxAction('<?=CUtil::JSEscape($item['~DELETE_URL'])?>', event);" title="<?=GetMessage("CATALOG_REMOVE_PRODUCT")?>">
											<svg class="product-item-del-icon icon icon-svg"><use xlink:href="#svg-cross"></use></svg>
										</span>
									</a>

									<h2 class="product-item-title">
												<? if ($itemHasDetailUrl): ?>
													<a href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$productTitle?>">
												<? endif; ?>

												<?=$productTitle?>

												<? if ($itemHasDetailUrl): ?>
													</a>
												<? endif; ?>
									</h2>

									<div class="product-item-info-container product-item-price-container">
													<?php
													if (isset($item['MIN_PRICE']) && is_array($item['MIN_PRICE']))
													{
														$price = $item['MIN_PRICE'];
														$bCanBuy = $item['CAN_BUY'];
														include(MyTemplate::getTemplatePart($templateFolder.'/include/price.php'));
													}
													elseif (!empty($item['PRICE_MATRIX']) && is_array($item['PRICE_MATRIX']))
													{

														$matrix = $item['PRICE_MATRIX'];
														$rows = $matrix['ROWS'];
														$rowsCount = count($rows);
														if ($rowsCount > 0)
														{
															if (count($rows) > 1)
															{
																foreach ($rows as $index => $rowData)
																{
																	if (empty($matrix['MIN_PRICES'][$index]))
																		continue;
																	if ($rowData['QUANTITY_FROM'] == 0)
																		$rowTitle = GetMessage('CP_TPL_CCR_RANGE_TO', array('#TO#' => $rowData['QUANTITY_TO']));
																	elseif ($rowData['QUANTITY_TO'] == 0)
																		$rowTitle = GetMessage('CP_TPL_CCR_RANGE_FROM', array('#FROM#' => $rowData['QUANTITY_FROM']));
																	else
																		$rowTitle = GetMessage(
																			'CP_TPL_CCR_RANGE_FULL',
																			array('#FROM#' => $rowData['QUANTITY_FROM'], '#TO#' => $rowData['QUANTITY_TO'])
																		);
																	$price = $matrix['MIN_PRICES'][$index];
																	$bCanBuy = $matrix['CAN_BUY'][$index];

																	include(MyTemplate::getTemplatePart($templateFolder.'/include/price.php'));

																	unset($rowTitle);
																	break;
																}
																unset($index, $rowData);
															}
															else
															{
																$price = current($matrix['MIN_PRICES']);
																$bCanBuy = current($matrix['CAN_BUY']);

																include(MyTemplate::getTemplatePart($templateFolder.'/include/price.php'));
															}
														}
														unset($rowsCount, $rows, $matrix);

													}
													?>
										</div>

										<?php include(MyTemplate::getTemplatePart($templateFolder.'/include/actions.php')); ?>
								</div>
							</div>
						</td>
					<?php endforeach; ?>
					<?php
					if ($iMinColumsCount > $iTableCol)
					{
						echo str_repeat('<td></td>', $iMinColumsCount - $iTableCol);
					}
					?>
					</tr>
					</thead>
					<?php $sTableHTML .= ob_get_clean() ?>
				</tr>
				</thead>
				<tbody>
					<tr class="compare__row"><td></td></tr>
					<?php ob_start(); ?>
					<tr class="compare__row">
						<td colspan="<?=($iMinColumsCount > $iTableCol ? $iMinColumsCount : $iTableCol)?>">
							<div class="compare__scroll" data-entity="scroll"></div>
						</td>
					</tr>
					<?php $sTableHTML .= ob_get_clean() ?>
				<?php
				if (!empty($arResult['SHOW_FIELDS']))
				{
					foreach ($arResult['SHOW_FIELDS'] as $sPropCode => $arProp)
					{
						if (in_array($sPropCode, $arFieldsHide))
						{
							continue;
						}
						$showRow = true;
						if (!isset($arResult['FIELDS_REQUIRED'][$sPropCode]) || $arResult['DIFFERENT'])
						{
							$arCompare = array();
							foreach($arResult['ITEMS'] as &$item)
							{
								$arPropertyValue = $item['FIELDS'][$sPropCode];
								if (is_array($arPropertyValue))
								{
									sort($arPropertyValue);
									$arPropertyValue = implode(' / ', $arPropertyValue);
								}
								$arCompare[] = $arPropertyValue;
							}
							unset($item);
							$showRow = (count(array_unique($arCompare)) > 1);
						}
						if ($showRow)
						{
							?><tr class="compare__row"><?
								?><td><?=getMessage('IBLOCK_FIELD_'.$sPropCode)?></td><?
								ob_start();
									?><tr class="compare__row"><?
									foreach ($arResult['ITEMS'] as &$item)
									{
										?><td><?echo $item['FIELDS'][$sPropCode]?></td><?
									}
									if ($iMinColumsCount > $iTableCol)
									{
										echo str_repeat('<td></td>', $iMinColumsCount - $iTableCol);
									}
									?></tr><?
								$sTableHTML .= ob_get_clean();
								unset($item);
							?></tr><?
						}
					}
				}

				if (!empty($arResult['SHOW_OFFER_FIELDS']))
				{
					foreach ($arResult['SHOW_OFFER_FIELDS'] as $sPropCode => $arProp)
					{
						if (in_array($sPropCode, $arFieldsHide))
						{
							continue;
						}
						$showRow = true;
						if ($arResult['DIFFERENT'])
						{
							$arCompare = array();
							foreach ($arResult['ITEMS'] as &$item)
							{
								$Value = $item['OFFER_FIELDS'][$sPropCode];
								if (is_array($Value))
								{
									sort($Value);
									$Value = implode(' / ', $Value);
								}
								$arCompare[] = $Value;
							}
							unset($item);
							$showRow = (count(array_unique($arCompare)) > 1);
						}
						if ($showRow)
						{
							?><tr class="compare__row"><?
								?><td><?=getMessage('IBLOCK_OFFER_FIELD_'.$sPropCode)?></td><?
								ob_start();
									?><tr class="compare__row"><?
									foreach ($arResult['ITEMS'] as &$item)
									{
										?><td><?
											echo (is_array($item['OFFER_FIELDS'][$sPropCode])? implode('/ ', $item['OFFER_FIELDS'][$sPropCode]): $item['OFFER_FIELDS'][$sPropCode]);
										?></td><?
									}
									if ($iMinColumsCount > $iTableCol)
									{
										echo str_repeat('<td></td>', $iMinColumsCount - $iTableCol);
									}
									?></tr><?
								$sTableHTML .= ob_get_clean();
								unset($item);
							?></tr><?
						}
					}
				}

				if (!empty($arResult['PROPERTIES_GROUPS']) && (!empty($arResult['SHOW_PROPERTIES']) || !empty($arResult['SHOW_OFFER_PROPERTIES'])))
				{
					$first = true;
					foreach ($arResult['PROPERTIES_GROUPS'] as $key => $arGroup)
					{
						if ($arGroup['IS_SHOW'])
						{
							if (strlen($arGroup['NAME']) > 0)
							{
								?><tr class="compare__row"><th class="compare__group"><?= isset($arGroup['NAME']) ? $arGroup['NAME'] : getMessage('RS_SLINE.BCCR_AL.NOT_GRUPED_PROPS')?></th></tr><?
								$sTableHTML .= '<tr class="compare__row"><th class="compare__group" colspan="'.($iMinColumsCount > $iTableCol ? $iMinColumsCount : $iTableCol).'"></th></tr>';
							}
							if (!empty($arGroup['BINDS']))
							{
								foreach ($arGroup['BINDS'] as $iPropId => $sPropCode)
								{
									if (
										isset($arResult['SHOW_PROPERTIES'][$sPropCode])
										&& $arResult['SHOW_PROPERTIES'][$sPropCode]['ID'] == $iPropId
										&& $arResult['SHOW_PROPERTIES'][$sPropCode]['IS_SHOW']
									) {
										?><tr class="compare__row"><?
											?><td><?=$arResult['SHOW_PROPERTIES'][$sPropCode]['NAME']?></td><?
											ob_start();
												?><tr class="compare__row"><?
												foreach($arResult['ITEMS'] as &$item)
												{
													?><td><?
														echo (is_array($item['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'])? implode('/ ', $item['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']): $item['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']);
													?></td><?
												}
												if ($iMinColumsCount > $iTableCol)
												{
													echo str_repeat('<td></td>', $iMinColumsCount - $iTableCol);
												}
												?></tr><?
											$sTableHTML .= ob_get_clean();
											unset($item);
										?></tr><?
									}

									if (
										isset($arResult['SHOW_OFFER_PROPERTIES'][$sPropCode])
										&& $arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['ID'] == $iPropId
										&& $arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['IS_SHOW']
									) {
										?><tr class="compare__row"><?
											?><td><?=$arResult['SHOW_OFFER_PROPERTIES'][$sPropCode]['NAME']?></td><?
											ob_start();
												?><tr class="compare__row"><?
												foreach ($arResult['ITEMS'] as &$item)
												{
													?><td><?
														echo (is_array($item['OFFER_DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'])? implode('/ ', $item['OFFER_DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']): $item['OFFER_DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']);
													?></td><?
												}
												if ($iMinColumsCount > $iTableCol)
												{
													echo str_repeat('<td></td>', $iMinColumsCount - $iTableCol);
												}
												?></tr><?
											$sTableHTML .= ob_get_clean();
											unset($item);
										?></tr><?
									}
								}
							}
						}
					}
				}
				?>
				</tbody>
			</table>
		</div>
		<div class="compare__items scrollbar-rail col col-xs-6 col-md-9 col-lg-9d6" data-entity="column-items">
			<table class="compare__table" data-entity="items-table"><?=$sTableHTML?></table>
		</div>
	</div>
	<?php
else:
	ShowNote(GetMessage("CATALOG_COMPARE_LIST_EMPTY"));
endif;
?>
</div>

<?php
if ($isAjax)
{
	die();
}
else
{
	$frame->end();
}
?>

</div>
<script>var <?=$obName?> = new BX.Iblock.Catalog.CompareClass(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);</script>
