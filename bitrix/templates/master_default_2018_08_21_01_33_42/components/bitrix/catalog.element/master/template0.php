<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;

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

function getTemplatePart($sPath) {
    if (empty($sPath)) {
        return false;
    }
    
    $paths = array($sPath);
    
    $sFileExt = end(explode('.', $sPath));
    array_unshift($paths, str_replace($sFileExt, '', $sPath).'custom.'.$sFileExt);

    foreach ($paths as $path) {
        $filePath = Application::getDocumentRoot().$path;
        
        if (file_exists($filePath)) {
            return $filePath;
        }
    }

    return false;
}

$this->setFrameMode(true);

$templateLibrary = array(/*'popup', 'fx'*/);
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList,
	'ITEM' => array(
		'ID' => $arResult['ID'],
		'IBLOCK_ID' => $arResult['IBLOCK_ID'],
		'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
		'JS_OFFERS' => $arResult['JS_OFFERS']
	)
);
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
	'ID' => $mainId,
	'DISCOUNT_PERCENT_ID' => $mainId.'_dsc_pict',
	'STICKER_ID' => $mainId.'_sticker',
	'BIG_SLIDER_ID' => $mainId.'_big_slider',
	'BIG_IMG_CONT_ID' => $mainId.'_bigimg_cont',
	'SLIDER_CONT_ID' => $mainId.'_slider_cont',
	'OLD_PRICE_ID' => $mainId.'_old_price',
	'PRICE_ID' => $mainId.'_price',
	'DISCOUNT_PRICE_ID' => $mainId.'_price_discount',
	'PRICE_TOTAL' => $mainId.'_price_total',
	'SLIDER_CONT_OF_ID' => $mainId.'_slider_cont_',
	'QUANTITY_MENU' => $mainId.'_quiantity_menu',
	'QUANTITY_ID' => $mainId.'_quantity',
	'QUANTITY_DOWN_ID' => $mainId.'_quant_down',
	'QUANTITY_UP_ID' => $mainId.'_quant_up',
	'QUANTITY_MEASURE' => $mainId.'_quant_measure',
	'QUANTITY_LIMIT' => $mainId.'_quant_limit',
	'BUY_LINK' => $mainId.'_buy_link',
	'ADD_BASKET_LINK' => $mainId.'_add_basket_link',
	'BASKET_ACTIONS_ID' => $mainId.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $mainId.'_not_avail',
	'COMPARE_LINK' => $mainId.'_compare_link',
	'TREE_ID' => $mainId.'_skudiv',
	'DISPLAY_PROP_DIV' => $mainId.'_sku_prop',
	'DISPLAY_MAIN_PROP_DIV' => $mainId.'_main_sku_prop',
	'OFFER_GROUP' => $mainId.'_set_group_',
	'BASKET_PROP_DIV' => $mainId.'_basket_prop',
	'SUBSCRIBE_LINK' => $mainId.'_subscribe',
	'TABS_ID' => $mainId.'_tabs',
	'TAB_CONTAINERS_ID' => $mainId.'_tab_containers',
	'SMALL_CARD_PANEL_ID' => $mainId.'_small_card_panel',
	'TABS_PANEL_ID' => $mainId.'_tabs_panel',
	'FAVORITE_LINK_ID' => $mainId.'_favorite',
);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
	: $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
	: $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
	: $arResult['NAME'];

$arResult['ITEM_MEASURE']['TITLE'] = Loc::getMessage('RS.MASTER.BCE_MASTER.ITEM_MEASURE');
$arResult['ITEM_MEASURE_RATIO_SELECTED'] = 0;
$arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'] = 1;

$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers)
{
	$actualItem = isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']])
		? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]
		: reset($arResult['OFFERS']);
	$showSliderControls = false;

	foreach ($arResult['OFFERS'] as $offer)
	{
		if ($offer['MORE_PHOTO_COUNT'] > 1)
		{
			$showSliderControls = true;
			break;
		}
	}
}
else
{
	$actualItem = $arResult;
	$showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$skuProps = array();
//$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];

$price = $arResult['RS_PRICES'];
$price['MIN_QUANTITY'] = 1;

$arResult['ITEM_PRICES'][0] = $price;
$arResult['ITEM_PRICE_SELECTED'] = 0;

$actualItem['CAN_BUY'] = $price['RATIO_PRICE'] > 0 && $price['RATIO_BASE_PRICE'] > 0;
$arResult['CAN_BUY'] = $actualItem['CAN_BUY'];

$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];

$showDiscount = $price['PERCENT'] > 0;

$showDescription = (!empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']));
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-default';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-default';
$showAskBtn = in_array('ASK', $arParams['ADD_TO_BASKET_ACTION']);
$askButtonClassName = in_array('ASK', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-primary' : 'btn-default';

$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['CATALOG_SUBSCRIBE'] === 'Y' || $haveOffers);

$arDisplayProperties = array();
if (is_array($arResult['DISPLAY_PROPERTIES']) && count($arResult['DISPLAY_PROPERTIES']) > 0) {

    $arDisplayProperties = array_diff_key(
        $arResult['DISPLAY_PROPERTIES'],
        is_array($arParams['TAB_PROPERTIES']) ? array_fill_keys($arParams['TAB_PROPERTIES'], 0) : array(),
        is_array($arParams['BLOCK_LINES_PROPERTIES']) ? array_fill_keys($arParams['BLOCK_LINES_PROPERTIES'], 0) : array()
    );
}

$showDisplayProperties = count($arDisplayProperties) > 0;

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');
$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');

$positionClassMap = array(
	'left' => 'product-item-label-left',
	'center' => 'product-item-label-center',
	'right' => 'product-item-label-right',
	'bottom' => 'product-item-label-bottom',
	'middle' => 'product-item-label-middle',
	'top' => 'product-item-label-top'
);

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}
?>
<article class="bx-catalog-element" id="<?=$itemIds['ID']?>"
	itemscope itemtype="http://schema.org/Product">
	<div class="<?//container-fluid?>">
		<div class="row">
			<div class="col-md-6 col-sm-12">
                <?php include(getTemplatePart($templateFolder.'/include/slider.php')); ?>
			</div>
			<div class="col-md-6 col-sm-12">
<?/*
				<div class="row">
					<div class="col-sm-6">
*/?>
						<div class="product-item-detail-info-section">

                            <?php include(getTemplatePart($templateFolder.'/include/info/top.php')); ?>

                            <?
							foreach ($arParams['PRODUCT_INFO_BLOCK_ORDER'] as $blockName)
							{
								switch ($blockName)
								{

/*
									case 'sku':
                                        include(getTemplatePart($templateFolder.'/include/info/sku.php'));
										break;
*/
									case 'props':
                                        include(getTemplatePart($templateFolder.'/include/info/props.php'));
                                        break;

/*
                                    case 'rating':
                                        include(getTemplatePart($templateFolder.'/include/info/rating.php'));
                                        break;
*/
                                    case 'price':
                                        include(getTemplatePart($templateFolder.'/include/info/price.php'));
                                        break;
/*
                                    case 'priceRanges':
                                        include(getTemplatePart($templateFolder.'/include/info/price-ranges.php'));
                                        break;

                                    case 'quantityLimit':
                                        include(getTemplatePart($templateFolder.'/include/info/quantity-limit.php'));
                                        break;

                                    case 'quantity':
                                        include(getTemplatePart($templateFolder.'/include/info/quantity.php'));
                                        break;
*/
                                    case 'buttons':

                                        if ($arParams['USE_PRODUCT_QUANTITY'] && ($showBuyBtn || $showAddBtn) || $showAskBtn) {
                                        ?>
                                            <div class="product-item-detail-info-container product-item-detail-info-container-buttons" data-entity="main-button-container">
                                                <?php
                                                if ($arParams['USE_PRODUCT_QUANTITY'])
                                                {
                                                    include(getTemplatePart($templateFolder.'/include/info/quantity.php'));
                                                }

                                                if ($showBuyBtn || $showAddBtn || $showAskBtn)
                                                {
                                                    include(getTemplatePart($templateFolder.'/include/info/buttons.php'));
                                                }
                                                ?>
                                            </div>
                                        <?php
                                        }
                                        break;

                                    case 'preview':
                                        include(getTemplatePart($templateFolder.'/include/info/preview.php'));
                                        break;

								}
							}

                            if ($arParams['USE_SHARE'] == 'Y') {
                                include(getTemplatePart($templateFolder.'/include/info/share.php'));
                            }
/*
							if ($arParams['DISPLAY_COMPARE'])
							{
                                include(getTemplatePart($templateFolder.'/include/info/compare.php'));
							}
*/
							?>
						</div>
<?/*
					</div>
				</div>
*/?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<?
/*
				include(getTemplatePart($templateFolder.'/include/sets.php'));
*/
				?>
			</div>
		</div>
        <?php if (is_array($arResult['TABS']) && count($arResult['TABS']) > 0): ?>
            <div class="row">
                <div class="col-xs-12">
                    <div id="<?=$itemIds['TABS_ID']?>" class="product-item-detail-tabs">
                        <div class="nav-wrap">
                            <ul class="nav nav-tabs" role="tablist">
                                <?php
                                $bActiveTab = false;
                                foreach ($arParams['TABS_ORDER'] as $blockName) {

                                    //fix arParams saving
                                    if (!in_array($blockName, $arResult['TABS'])) {
                                        continue;
                                    }

                                    switch ($blockName) {

                                        case 'detail':

                                            if ($arResult['DETAIL_TEXT'] != '')
                                            {
                                                ?>
                                                 <li <?php if (!$bActiveTab): ?>class="active"<?php endif; ?>>
                                                    <a rel="nofollow" href="#<?=$blockName?>" id="tab_<?=$blockName?>" data-toggle="tab">
                                                        <?=$arParams['MESS_DESCRIPTION_TAB']?>
                                                    </a>
                                                </li>
                                                <?php
                                                $bActiveTab = true;
                                            }
                                            break;

                                        case 'props':
                                            if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
                                            {
                                                ?>
                                                 <li <?php if (!$bActiveTab): ?>class="active"<?php endif; ?>>
                                                    <a rel="nofollow" href="#<?=$blockName?>" id="tab_<?=$blockName?>" data-toggle="tab">
                                                        <?=$arParams['MESS_PROPERTIES_TAB']?>
                                                    </a>
                                                </li>
                                                <?php
                                                $bActiveTab = true;
                                            }
                                            break;

                                        case 'comments':

                                            if ($arParams['USE_COMMENTS'] === 'Y')
                                            {
                                                $tabsId = 'soc_comments_'.$arResult['ID'];
                                                if ($arParams['BLOG_USE'] == 'Y' && Loader::includeModule('blog')) {
                                                    ?>
                                                     <li <?php if (!$bActiveTab): ?>class="active"<?php endif; ?>>
                                                        <a rel="nofollow" href="#<?=$tabsId?>BLOG_cont" data-toggle="tab">
                                                            <?=$arParams['MESS_COMMENTS_TAB']?>
                                                        </a>
                                                    </li>
                                                    <?php
                                                    $bActiveTab = true;
                                                }
                                                if ($arParams['FB_USE'] == 'Y') {
                                                    ?>
                                                     <li <?php if (!$bActiveTab): ?>class="active"<?php endif; ?>>
                                                        <a rel="nofollow" href="#<?=$tabsId?>FB_cont" data-toggle="tab">
                                                            <?=isset($arParams["FB_TITLE"]) && trim($arParams["FB_TITLE"]) != "" ? $arParams["FB_TITLE"] : "Facebook"?>
                                                        </a>
                                                    </li>
                                                    <?php
                                                    $bActiveTab = true;
                                                }
                                                if ($arParams['VK_USE'] == 'Y') {
                                                    ?>
                                                     <li <?php if (!$bActiveTab): ?>class="active"<?php endif; ?>>
                                                        <a rel="nofollow" href="#<?=$tabsId?>VK_cont" data-toggle="tab">
                                                            <?=isset($arParams["VK_TITLE"]) && trim($arParams["VK_TITLE"]) != "" ? $arParams["VK_TITLE"] : Loc::getMessage("IBLOCK_CSC_TAB_VK")?>
                                                        </a>
                                                    </li>
                                                    <?php
                                                    $bActiveTab = true;
                                                }
                                            }
                                            break;

                                        default:
                                            if (substr($blockName, 0, 5) == 'prop_') {
                                                $sPropCode = substr($blockName, 5);
                                                if (!empty($arResult['PROPERTIES'][$sPropCode]['VALUE'])) {
                                                    ?>
                                                     <li <?php if (!$bActiveTab): ?>class="active"<?php endif; ?>>
                                                        <a rel="nofollow" href="#<?=$blockName?>" data-toggle="tab">
                                                            <?=$arResult['PROPERTIES'][$sPropCode]['NAME']?>
                                                        </a>
                                                    </li>
                                                    <?php
                                                    $bActiveTab = true;
                                                }
                                            }
                                            break;
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <?php
                            $bActiveTab = false;
                            foreach ($arParams['TABS_ORDER'] as $blockName) {

                                //fix arParams saving
                                if (!in_array($blockName, $arResult['TABS'])) {
                                    continue;
                                }

                                switch ($blockName) {

                                    case 'detail':
                                        if ($arResult['DETAIL_TEXT'] != ''):
                                            ?>
                                                <div class="tab-pane fade<?php if (!$bActiveTab): ?> in active<?php endif; ?>"
                                                    id="<?=$blockName?>"
                                                    data-value="description"
                                                    itemprop="description"
                                                >
                                                    <?php include(getTemplatePart($templateFolder.'/include/detail.php')); ?>
                                                </div>
                                            <?php
                                            $bActiveTab = true;
                                        endif;
                                        break;

                                    case 'props':
                                        if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS']):
                                            ?>
                                                <div class="tab-pane fade<?php if (!$bActiveTab): ?> in active<?php endif; ?>"
                                                    id="<?=$blockName?>"
                                                    data-value="properties"
                                                >
                                                    <?php
                                                    if ($showDisplayProperties)
                                                    {
                                                        include(getTemplatePart($templateFolder.'/include/props/display.php'));
                                                    }
                                                    ?>
                                                </div>
                                            <?php
                                            $bActiveTab = true;
                                        endif;
                                        break;

                                    case 'comments':
                                        if ($arParams['USE_COMMENTS'] === 'Y')
                                        {
                                            include(getTemplatePart($templateFolder.'/include/comments/tab.php'));
                                            $bActiveTab = true;
                                        }
                                        break;

                                    default:
                                        if (substr($blockName, 0, 5) == 'prop_') {
                                            $sPropCode = substr($blockName, 5);
                                            if (!empty($arResult['PROPERTIES'][$sPropCode]['VALUE'])) {
                                                ?>
                                                <div class="tab-pane fade<?php if (!$bActiveTab): ?> in active<?php endif; ?>"
                                                    id="<?=$blockName?>"
                                                    data-value="<?=$blockName?>"
                                                >
                                                <?php
                                                if ($arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == 'F') {

                                                    include(getTemplatePart($templateFolder.'/include/props/files.php'));
/*
                                                } elseif (
                                                    $arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == 'E' &&
                                                    count($arResult['PROPERTIES'][$sPropCode]['VALUE']) > 0
                                                ) {

                                                    include(getTemplatePart($templateFolder.'/include/props/catalog.php'));
*/
                                                } elseif ($arResult['PROPERTIES'][$sPropCode]['MULTIPLE'] == 'Y') {

                                                    include(getTemplatePart($templateFolder.'/include/props/list.php'));

                                                } else {

                                                    include(getTemplatePart($templateFolder.'/include/props/default.php'));

                                                }
                                                ?>
                                                </div>

                                                <?php
                                                $bActiveTab = true;
                                            }
                                        }
                                        break;
                                }
                            }
                            unset($blockName);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (is_array($arResult['BLOCK_LINES']) && count($arResult['BLOCK_LINES']) > 0): ?>
            <?php
            foreach ($arParams['BLOCK_LINES_ORDER'] as $blockName) {

                //fix arParams saving
                if (!in_array($blockName, $arResult['BLOCK_LINES'])) {
                    continue;
                }

                switch ($blockName) {

                    case 'detail':

                        if ($arResult['DETAIL_TEXT'] != ''):
                        ?>
                            <div class="row">
                                <div class="col-xs-12" id="<?=$blockName?>">

                                    <?php if (strlen($arParams['MESS_DESCRIPTION_TAB']) > 0): ?>
                                        <h3><?=$arParams['MESS_DESCRIPTION_TAB']?></h3>
                                    <?php endif; ?>

                                    <div data-value="description" itemprop="description">
                                        <?php include(getTemplatePart($templateFolder.'/include/detail.php')); ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endif;

                        break;

                    case 'props':

                        if ($showDisplayProperties):
                        ?>
                            <div class="row">
                                <div class="col-xs-12" id="<?=$blockName?>">

                                    <?php if (strlen($arParams['MESS_PROPERTIES_TAB']) > 0): ?>
                                        <h3><?=$arParams['MESS_PROPERTIES_TAB']?></h3>
                                    <?php endif; ?>

                                    <div data-value="properties">
                                        <?php include(getTemplatePart($templateFolder.'/include/props/display.php')); ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endif;

                        break;

                    case 'comments':
                        if ($arParams['USE_COMMENTS'] === 'Y'):
                            ?>
                            <div class="row">
                                <div class="col-xs-12" id="<?=$blockName?>">

                                    <?php if (strlen($arParams['MESS_COMMENTS_TAB']) > 0): ?>
                                        <h3><?=$arParams['MESS_COMMENTS_TAB']?></h3>
                                    <?php endif; ?>

                                    <div data-value="comments">
                                        <?php include(getTemplatePart($templateFolder.'/include/comments/line.php')); ?>
                                   </div>
                                </div>
                            </div>
                            <?php
                        endif;

                        break;

                    default:
                        if (substr($blockName, 0, 5) == 'prop_') {
                            $sPropCode = substr($blockName, 5);
                            if (!empty($arResult['PROPERTIES'][$sPropCode]['VALUE'])) {
                                ?>
                                <div class="row">
                                    <div class="col-xs-12" id="<?=$blockName?>">

                                        <?php if (strlen($arResult['PROPERTIES'][$sPropCode]['NAME']) > 0): ?>
                                            <h3><?=$arResult['PROPERTIES'][$sPropCode]['NAME']?></h3>
                                        <?php endif; ?>

                                        <div>
                                        <?php
                                        if ($arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == 'F') {

                                            include(getTemplatePart($templateFolder.'/include/props/files.php'));
/*
                                        } elseif (
                                            $arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == 'E' &&
                                            count($arResult['PROPERTIES'][$sPropCode]['VALUE']) > 0
                                        ) {

                                            include(getTemplatePart($templateFolder.'/include/props/catalog.php'));
*/
                                        } elseif ($arResult['PROPERTIES'][$sPropCode]['MULTIPLE'] == 'Y') {

                                            include(getTemplatePart($templateFolder.'/include/prop/list.php'));

                                        } else {

                                            include(getTemplatePart($templateFolder.'/include/prop/default.php'));

                                        }
                                        ?>
                                       </div>
                                    </div>
                                </div>

                                <?php
                                $bActiveTab = true;
                            }
                        }
                        break;
                }
            }
            unset($blockName);
            ?>

        <?php endif; ?>

		<div class="row">
<?/*
			<div class="col-sm-4 col-md-3">
				<div>
					<?
					if ($arParams['BRAND_USE'] === 'Y')
					{
						$APPLICATION->IncludeComponent(
							'bitrix:catalog.brandblock',
							'.default',
							array(
								'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
								'IBLOCK_ID' => $arParams['IBLOCK_ID'],
								'ELEMENT_ID' => $arResult['ID'],
								'ELEMENT_CODE' => '',
								'PROP_CODE' => $arParams['BRAND_PROP_CODE'],
								'CACHE_TYPE' => $arParams['CACHE_TYPE'],
								'CACHE_TIME' => $arParams['CACHE_TIME'],
								'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
								'WIDTH' => '',
								'HEIGHT' => ''
							),
							$component,
							array('HIDE_ICONS' => 'Y')
						);
					}
					?>
				</div>
			</div>
*/?>
		</div>
	</div>
<?/*
	<!--Small Card-->
	<div class="product-item-detail-short-card-fixed hidden-xs" id="<?=$itemIds['SMALL_CARD_PANEL_ID']?>">
		<div class="product-item-detail-short-card-content-container">
			<table>
				<tr>
					<td rowspan="2" class="product-item-detail-short-card-image">
						<img src="" style="height: 65px;" data-entity="panel-picture">
					</td>
					<td class="product-item-detail-short-title-container" data-entity="panel-title">
						<span class="product-item-detail-short-title-text"><?=$name?></span>
					</td>
					<td rowspan="2" class="product-item-detail-short-card-price">
						<?
						if ($arParams['SHOW_OLD_PRICE'] === 'Y')
						{
							?>
							<div class="product-item-detail-price-old" style="display: <?=($showDiscount ? '' : 'none')?>;"
								data-entity="panel-old-price">
								<?=($showDiscount ? $price['PRINT_RATIO_BASE_PRICE'] : '')?>
							</div>
							<?
						}
						?>
						<div class="product-item-detail-price-current" data-entity="panel-price">
							<?=$price['PRINT_RATIO_PRICE']?>
						</div>
					</td>
					<?
					if ($showAddBtn)
					{
						?>
						<td rowspan="2" class="product-item-detail-short-card-btn"
							style="display: <?=($actualItem['CAN_BUY'] ? '' : 'none')?>;"
							data-entity="panel-add-button">
							<a class="btn <?=$showButtonClassName?> product-item-detail-buy-button"
								id="<?=$itemIds['ADD_BASKET_LINK']?>"
								href="javascript:void(0);">
								<span><?=$arParams['MESS_BTN_ADD_TO_BASKET']?></span>
							</a>
						</td>
						<?
					}

					if ($showBuyBtn)
					{
						?>
						<td rowspan="2" class="product-item-detail-short-card-btn"
							style="display: <?=($actualItem['CAN_BUY'] ? '' : 'none')?>;"
							data-entity="panel-buy-button">
							<a class="btn <?=$buyButtonClassName?> product-item-detail-buy-button" id="<?=$itemIds['BUY_LINK']?>"
								href="javascript:void(0);">
								<span><?=$arParams['MESS_BTN_BUY']?></span>
							</a>
						</td>
						<?
					}
					?>
					<td rowspan="2" class="product-item-detail-short-card-btn"
						style="display: <?=(!$actualItem['CAN_BUY'] ? '' : 'none')?>;"
						data-entity="panel-not-available-button">
						<a class="btn btn-link product-item-detail-buy-button" href="javascript:void(0)"
							rel="nofollow">
							<?=$arParams['MESS_NOT_AVAILABLE']?>
						</a>
					</td>
				</tr>
				<?
				if ($haveOffers)
				{
					?>
					<tr>
						<td>
							<div class="product-item-selected-scu-container" data-entity="panel-sku-container">
								<?
								$i = 0;

								foreach ($arResult['SKU_PROPS'] as $skuProperty)
								{
									if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']]))
									{
										continue;
									}

									$propertyId = $skuProperty['ID'];

									foreach ($skuProperty['VALUES'] as $value)
									{
										$value['NAME'] = htmlspecialcharsbx($value['NAME']);
										if ($skuProperty['SHOW_MODE'] === 'PICT')
										{
											?>
											<div class="product-item-selected-scu product-item-selected-scu-color selected"
												title="<?=$value['NAME']?>"
												style="background-image: url(<?=$value['PICT']['SRC']?>); display: none;"
												data-sku-line="<?=$i?>"
												data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
												data-onevalue="<?=$value['ID']?>">
											</div>
											<?
										}
										else
										{
											?>
											<div class="product-item-selected-scu product-item-selected-scu-text selected"
												title="<?=$value['NAME']?>"
												style="display: none;"
												data-sku-line="<?=$i?>"
												data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
												data-onevalue="<?=$value['ID']?>">
												<?=$value['NAME']?>
											</div>
											<?
										}
									}

									$i++;
								}
								?>
							</div>
						</td>
					</tr>
					<?
				}
				?>
			</table>
		</div>
	</div>
*/?>
<?/*
	<!--Top tabs-->
	<div class="product-item-detail-tabs-container-fixed hidden-xs" id="<?=$itemIds['TABS_PANEL_ID']?>">
		<ul class="product-item-detail-tabs-list">
			<?
			if ($showDescription)
			{
				?>
				<li class="product-item-detail-tab active" data-entity="tab" data-value="description">
					<a href="javascript:void(0);" class="product-item-detail-tab-link">
						<span><?=$arParams['MESS_DESCRIPTION_TAB']?>1</span>
					</a>
				</li>
				<?
			}

			if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
			{
				?>
				<li class="product-item-detail-tab" data-entity="tab" data-value="properties">
					<a href="javascript:void(0);" class="product-item-detail-tab-link">
						<span><?=$arParams['MESS_PROPERTIES_TAB']?>2</span>
					</a>
				</li>
				<?
			}

			if ($arParams['USE_COMMENTS'] === 'Y')
			{
				?>
				<li class="product-item-detail-tab" data-entity="tab" data-value="comments">
					<a href="javascript:void(0);" class="product-item-detail-tab-link">
						<span><?=$arParams['MESS_COMMENTS_TAB']?>3</span>
					</a>
				</li>
				<?
			}
			?>
		</ul>
	</div>
*/?>
	<meta itemprop="name" content="<?=$name?>" />
	<meta itemprop="category" content="<?=$arResult['CATEGORY_PATH']?>" />
	<?
/*
	if ($haveOffers)
	{
		foreach ($arResult['JS_OFFERS'] as $offer)
		{
			$currentOffersList = array();

			if (!empty($offer['TREE']) && is_array($offer['TREE']))
			{
				foreach ($offer['TREE'] as $propName => $skuId)
				{
					$propId = (int)substr($propName, 5);

					foreach ($skuProps as $prop)
					{
						if ($prop['ID'] == $propId)
						{
							foreach ($prop['VALUES'] as $propId => $propValue)
							{
								if ($propId == $skuId)
								{
									$currentOffersList[] = $propValue['NAME'];
									break;
								}
							}
						}
					}
				}
			}

			$offerPrice = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']];
			?>
			<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="sku" content="<?=htmlspecialcharsbx(implode('/', $currentOffersList))?>" />
				<meta itemprop="price" content="<?=$offerPrice['RATIO_PRICE']?>" />
				<meta itemprop="priceCurrency" content="<?=$offerPrice['CURRENCY']?>" />
				<link itemprop="availability" href="http://schema.org/<?=($offer['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
			</span>
			<?
		}

		unset($offerPrice, $currentOffersList);
	}
	else
	{
*/
		?>
		<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<meta itemprop="price" content="<?=$price['RATIO_PRICE']?>" />
			<meta itemprop="priceCurrency" content="<?=$price['CURRENCY']?>" />
			<link itemprop="availability" href="http://schema.org/<?=($actualItem['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
		</span>
		<?
/*
	}
*/
	?>
</article>

<?
/*
if ($haveOffers)
{
	$offerIds = array();
	$offerCodes = array();

	$useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

	foreach ($arResult['JS_OFFERS'] as $ind => &$jsOffer)
	{
		$offerIds[] = (int)$jsOffer['ID'];
		$offerCodes[] = $jsOffer['CODE'];

		$fullOffer = $arResult['OFFERS'][$ind];
		$measureName = $fullOffer['ITEM_MEASURE']['TITLE'];

		$strAllProps = '';
		$strMainProps = '';
		$strPriceRangesRatio = '';
		$strPriceRanges = '';

		if ($arResult['SHOW_OFFERS_PROPS'])
		{
			if (!empty($jsOffer['DISPLAY_PROPERTIES']))
			{
				foreach ($jsOffer['DISPLAY_PROPERTIES'] as $property)
				{
					$current = '<dt>'.$property['NAME'].'</dt><dd>'.(
						is_array($property['VALUE'])
							? implode(' / ', $property['VALUE'])
							: $property['VALUE']
						).'</dd>';
					$strAllProps .= $current;

					if (isset($arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][$property['CODE']]))
					{
						$strMainProps .= $current;
					}
				}

				unset($current);
			}
		}

		if ($arParams['USE_PRICE_COUNT'] && count($jsOffer['ITEM_QUANTITY_RANGES']) > 1)
		{
			$strPriceRangesRatio = '('.Loc::getMessage(
					'CT_BCE_CATALOG_RATIO_PRICE',
					array('#RATIO#' => ($useRatio
							? $fullOffer['ITEM_MEASURE_RATIOS'][$fullOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']
							: '1'
						).' '.$measureName)
				).')';

			foreach ($jsOffer['ITEM_QUANTITY_RANGES'] as $range)
			{
				if ($range['HASH'] !== 'ZERO-INF')
				{
					$itemPrice = false;

					foreach ($jsOffer['ITEM_PRICES'] as $itemPrice)
					{
						if ($itemPrice['QUANTITY_HASH'] === $range['HASH'])
						{
							break;
						}
					}

					if ($itemPrice)
					{
						$strPriceRanges .= '<dt>'.Loc::getMessage(
								'CT_BCE_CATALOG_RANGE_FROM',
								array('#FROM#' => $range['SORT_FROM'].' '.$measureName)
							).' ';

						if (is_infinite($range['SORT_TO']))
						{
							$strPriceRanges .= Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
						}
						else
						{
							$strPriceRanges .= Loc::getMessage(
								'CT_BCE_CATALOG_RANGE_TO',
								array('#TO#' => $range['SORT_TO'].' '.$measureName)
							);
						}

						$strPriceRanges .= '</dt><dd>'.($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE']).'</dd>';
					}
				}
			}

			unset($range, $itemPrice);
		}

		$jsOffer['DISPLAY_PROPERTIES'] = $strAllProps;
		$jsOffer['DISPLAY_PROPERTIES_MAIN_BLOCK'] = $strMainProps;
		$jsOffer['PRICE_RANGES_RATIO_HTML'] = $strPriceRangesRatio;
		$jsOffer['PRICE_RANGES_HTML'] = $strPriceRanges;
	}

	$templateData['OFFER_IDS'] = $offerIds;
	$templateData['OFFER_CODES'] = $offerCodes;
	unset($jsOffer, $strAllProps, $strMainProps, $strPriceRanges, $strPriceRangesRatio, $useRatio);

	$jsParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => true,
			'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
			'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
			'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP' => $arResult['OFFER_GROUP'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
			'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
			'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
			'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
			'USE_STICKERS' => true,
			'USE_SUBSCRIBE' => $showSubscribe,
			'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
			'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
			'ALT' => $alt,
			'TITLE' => $title,
			'MAGNIFIER_ZOOM_PERCENT' => 200,
			'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
			'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
			'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
				? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
				: null
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'VISUAL' => $itemIds,
		'DEFAULT_PICTURE' => array(
			'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
			'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
		),
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'ACTIVE' => $arResult['ACTIVE'],
			'NAME' => $arResult['~NAME'],
			'CATEGORY' => $arResult['CATEGORY_PATH']
		),
		'BASKET' => array(
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		),
		'OFFERS' => $arResult['JS_OFFERS'],
		'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
		'TREE_PROPS' => $skuProps
	);
}
else
{
*/
	$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
	if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !$emptyProductProperties)
	{
		?>
		<div id="<?=$itemIds['BASKET_PROP_DIV']?>" style="display: none;">
			<?
			if (!empty($arResult['PRODUCT_PROPERTIES_FILL']))
			{
				foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo)
				{
					?>
					<input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]" value="<?=htmlspecialcharsbx($propInfo['ID'])?>">
					<?
					unset($arResult['PRODUCT_PROPERTIES'][$propId]);
				}
			}

			$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
			if (!$emptyProductProperties)
			{
				?>
				<table>
					<?
					foreach ($arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo)
					{
						?>
						<tr>
							<td><?=$arResult['PROPERTIES'][$propId]['NAME']?></td>
							<td>
								<?
								if (
									$arResult['PROPERTIES'][$propId]['PROPERTY_TYPE'] === 'L'
									&& $arResult['PROPERTIES'][$propId]['LIST_TYPE'] === 'C'
								)
								{
									foreach ($propInfo['VALUES'] as $valueId => $value)
									{
										?>
										<label>
											<input type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]"
												value="<?=$valueId?>" <?=($valueId == $propInfo['SELECTED'] ? '"checked"' : '')?>>
											<?=$value?>
										</label>
										<br>
										<?
									}
								}
								else
								{
									?>
									<select name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]">
										<?
										foreach ($propInfo['VALUES'] as $valueId => $value)
										{
											?>
											<option value="<?=$valueId?>" <?=($valueId == $propInfo['SELECTED'] ? '"selected"' : '')?>>
												<?=$value?>
											</option>
											<?
										}
										?>
									</select>
									<?
								}
								?>
							</td>
						</tr>
						<?
					}
					?>
				</table>
				<?
			}
			?>
		</div>
		<?
	}

	$jsParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => !empty($arResult['ITEM_PRICES']),
			'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
			'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
			'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
			'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
			'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
			'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
			'USE_STICKERS' => true,
			'USE_SUBSCRIBE' => $showSubscribe,
			'ALT' => $alt,
			'TITLE' => $title,
			'MAGNIFIER_ZOOM_PERCENT' => 200,
			'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
			'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
			'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
				? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
				: null,
            'USE_FAVORITE' => $arParams['USE_FAVORITE'] === 'Y',
		),
		'VISUAL' => $itemIds,
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'ACTIVE' => $arResult['ACTIVE'],
			'PICT' => reset($arResult['MORE_PHOTO']),
			'NAME' => $arResult['~NAME'],
			'SUBSCRIPTION' => true,
			'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
			'ITEM_PRICES' => $arResult['ITEM_PRICES'],
			'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
			'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
			'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
			'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
			'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
			'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
			'SLIDER' => $arResult['MORE_PHOTO'],
			'CAN_BUY' => $arResult['CAN_BUY'],
			'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
			'QUANTITY_FLOAT' => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
			'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
			'CATEGORY' => $arResult['CATEGORY_PATH']
		),
		'BASKET' => array(
			'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'EMPTY_PROPS' => $emptyProductProperties,
			'BASKET_URL' => $arParams['BASKET_URL'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		)
	);
	unset($emptyProductProperties);
//}

if ($arParams['DISPLAY_COMPARE'])
{
	$jsParams['COMPARE'] = array(
		'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
		'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
		'COMPARE_PATH' => $arParams['COMPARE_PATH']
	);
}
?>
<script>
	BX.message({
		ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
		TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
		TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
		BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
		BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
		BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
		BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
		TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
		COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
		COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
		COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
		PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
		PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
		SITE_ID: '<?=SITE_ID?>',
        RS_FAVORITE_ADD: '<?=GetMessageJS('RS.MASTER.BCE_MASTER.FAVORITE_ADD')?>',
        RS_FAVORITE_DEL: '<?=GetMessageJS('RS.MASTER.BCE_MASTER.FAVORITE_DEL')?>',
	});

	var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
</script>
<?
unset($actualItem, $itemIds, $jsParams);