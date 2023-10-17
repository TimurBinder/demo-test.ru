<?php
/**
 * Контейнер ниже подключает все необходимое для анимации дома
 * подключаемые файлы находятся в /bitrix/templates/house/*
 */
?>

<?$GLOBALS['arrFilter'] = array('=PROPERTY_SHOW_IN_MAIN_PAGE_VALUE' => 'Y');?>

<div class="container" id="catalog-top-main">
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"master", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_CONTAINER" => "Y",
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "/cart/",
		"BLOCK_NAME_IS_LINK" => "N",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPATIBLE_MODE" => "Y",
		"COMPONENT_TEMPLATE" => "master",
		"CURRENCY_PROP" => "CURRENCY",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "Y",
		"DISCOUNT_PROP" => "DISCOUNT",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_COMPARE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "rand",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "arrFilter",
		"IBLOCK_ID" => "3",
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"IS_USE_CART" => "Y",
		"LABEL_PROP" => array(
			0 => "NEWPRODUCT",
			1 => "SALELEADER",
			2 => "SPECIALOFFER",
		),
		"LABEL_PROP_MOBILE" => array(
		),
		"LABEL_PROP_POSITION" => "bottom-left",
		"LAZY_LOAD" => "N",
		"LINE_ELEMENT_COUNT" => "3",
		"LOAD_ON_SCROLL" => "N",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "Заказать",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_LIMIT" => "5",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "4",
		"PARENT_NAME" => "Популярные товары",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
		),
		"PRICE_DECIMALS_PROP" => "0",
		"PRICE_PROP" => "PRICE",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "preview,price,quantity,buttons",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false}]",
		"PROPERTY_CODE" => array(
			0 => "NEWPRODUCT",
			1 => "",
		),
		"PROPERTY_CODE_MOBILE" => array(
		),
		"PROP_CURRENCY" => "CURRENCY",
		"PROP_PRICE" => "PRICE",
		"PROP_PRICE_DECIMALS" => "0",
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
		"RCM_TYPE" => "personal",
		"SECTION_CODE" => "",
		"SECTION_ID" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_FROM_SECTION" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_PARENT_NAME" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"USE_ENHANCED_ECOMMERCE" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRICE_DECIMALS" => "0",
		"DATA_LAYER_NAME" => "dataLayer",
		"BRAND_PROPERTY" => "BRAND_REF",
		"ADD_TO_BASKET_ACTION" => "ADD"
	));?>
</div>


<div id="catalog-top-container" style="text-align:center">
	<div style="display:none; padding-right: 50px;" id="catalog-top-left">
		<div class="row" style="width: 270px;"></div>
	</div>
	<div class="container" id="catalog-top-center">
	    <link type="text/css" rel="stylesheet" href="/bitrix/templates/house/css/main.min.css">
	    <?$APPLICATION->IncludeComponent(
		"bitrix:main.include", 
		"", 
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => "/bitrix/templates/house/house.php",
		));?>
	    <script type="text/javascript" src="/bitrix/templates/house/js/scripts.min.js"></script>
	</div>
	<div style="display:none; padding-left: 50px;" id="catalog-top-right">
		<div class="row" style="width: 270px;"></div>
	</div>
</div>

<br>
<center>
<div class="container header-section-buttons">
    <div>
		<!--<a class="btn btn-lg btn-default header-section-button header-section-button-inactive" title="Грунт" href="/catalog/grunt/">
	    	<span>Грунт</span>
	    </a>
	    <a class="btn btn-lg btn-default header-section-button header-section-button-inactive" title="Песок" href="/catalog/pesok/">
	    	<span>Песок</span>
	    </a>-->
	    <a class="btn btn-lg btn-default header-section-button header-section-button-inactive" title="Бетоны и растворы" href="/catalog/beton/">
	    	<span>Бетоны и растворы</span>
	    </a>
	    <a class="btn btn-lg btn-default header-section-button header-section-button-inactive" title="Шлакоблок" href="/catalog/shlakoblok/">
	    	<span>Шлакоблок</span>
	    </a>
	    <a class="btn btn-lg btn-default header-section-button header-section-button-inactive" title="Тепловизионное обследование" href="/services/teplovizionnoe-obsledovanie/">
	    	<span>Тепловизионное обследование</span>
	    </a>
    </div>
    <div>
	    <a class="btn btn-lg btn-default header-section-button header-section-button-inactive" title="Кевларобетон" href="/catalog/kevlar/">
	    	<span>Кевларобетон</span>
	    </a>
	    <a class="btn btn-lg btn-default header-section-button header-section-button-inactive" title="Бордюры" href="/catalog/bordur/">
	    	<span>Бордюры</span>
	    </a>
	    <a class="btn btn-lg btn-default header-section-button header-section-button-inactive" title="Тротуарная плитка" href="/catalog/trotuar/">
	    	<span>Тротуарная плитка</span>
	    </a>
	    <a class="btn btn-lg btn-default header-section-button header-section-button-inactive" title="Декоративные изделия" href="/catalog/dekorativnye-izdeliya/">
	    	<span>Декоративные изделия</span>
	    </a>
    </div>
</div>
</center>
<style>
	.header-section-buttons a{
		margin: 5px;
	}
</style>

<div class="container" id="catalog-bottom-main">
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"master", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_CONTAINER" => "Y",
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "/cart/",
		"BLOCK_NAME_IS_LINK" => "N",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPATIBLE_MODE" => "Y",
		"COMPONENT_TEMPLATE" => "master",
		"CURRENCY_PROP" => "CURRENCY",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "Y",
		"DISCOUNT_PROP" => "DISCOUNT",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_COMPARE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "rand",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_ORDER2" => "asc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "arrFilter",
		"IBLOCK_ID" => "3",
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"IS_USE_CART" => "Y",
		"LABEL_PROP" => array(
			0 => "NEWPRODUCT",
			1 => "SALELEADER",
			2 => "SPECIALOFFER",
		),
		"LABEL_PROP_MOBILE" => array(
		),
		"LABEL_PROP_POSITION" => "bottom-left",
		"LAZY_LOAD" => "N",
		"LINE_ELEMENT_COUNT" => "3",
		"LOAD_ON_SCROLL" => "N",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "Заказать",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_LIMIT" => "5",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "4",
		"PARENT_NAME" => "Популярные товары",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
		),
		"PRICE_DECIMALS_PROP" => "0",
		"PRICE_PROP" => "PRICE",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "preview,price,quantity,buttons",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false}]",
		"PROPERTY_CODE" => array(
			0 => "NEWPRODUCT",
			1 => "",
		),
		"PROPERTY_CODE_MOBILE" => array(
		),
		"PROP_CURRENCY" => "CURRENCY",
		"PROP_PRICE" => "PRICE",
		"PROP_PRICE_DECIMALS" => "0",
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
		"RCM_TYPE" => "personal",
		"SECTION_CODE" => "",
		"SECTION_ID" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_FROM_SECTION" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_PARENT_NAME" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"USE_ENHANCED_ECOMMERCE" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRICE_DECIMALS" => "0",
		"DATA_LAYER_NAME" => "dataLayer",
		"BRAND_PROPERTY" => "BRAND_REF",
		"ADD_TO_BASKET_ACTION" => "ADD"
	));?>
</div>

<script>
	jQuery(function(){
		showCatElemsIndexPage();
		$(window).resize(function() {
			showCatElemsIndexPage();
		});
	});
	
function showCatElemsIndexPage() {
	if (jQuery(window).width() >= 1600) {
		jQuery('#catalog-top-main').hide();
		jQuery('#catalog-top-left').show().css('display', 'inline-block');
		jQuery('#catalog-top-right').show().css('display', 'inline-block');
		jQuery('#catalog-top-center').css('display', 'inline-block');
// 		jQuery('#catalog-top-left .row').html('');
// 		jQuery('#catalog-top-right .row').html('');
		jQuery('#catalog-top-left .row').append(jQuery('#catalog-top-main .col-xs-6:eq(0) .product-item-container'));
		jQuery('#catalog-top-left .row').append(jQuery('#catalog-top-main .col-xs-6:eq(1) .product-item-container'));
		jQuery('#catalog-top-right .row').append(jQuery('#catalog-top-main .col-xs-6:eq(2) .product-item-container'));
		jQuery('#catalog-top-right .row').append(jQuery('#catalog-top-main .col-xs-6:eq(3) .product-item-container'));
		jQuery('#catalog-bottom-main').hide();
		jQuery('#catalog-bottom-left').show();
		jQuery('#catalog-bottom-right').show();
// 		jQuery('#catalog-bottom-left .row').html('');
// 		jQuery('#catalog-bottom-right .row').html('');
		jQuery('#catalog-bottom-left .row').append(jQuery('#catalog-bottom-main .col-xs-6:eq(0) .product-item-container'));
		jQuery('#catalog-bottom-left .row').append(jQuery('#catalog-bottom-main .col-xs-6:eq(1) .product-item-container'));
		jQuery('#catalog-bottom-right .row').append(jQuery('#catalog-bottom-main .col-xs-6:eq(2) .product-item-container'));
		jQuery('#catalog-bottom-right .row').append(jQuery('#catalog-bottom-main .col-xs-6:eq(3) .product-item-container'));

		if (jQuery(window).width() >= 1600 && jQuery(window).width() < 1800) {
			jQuery('#catalog-top-left .row').width(180);
			jQuery('#catalog-top-right .row').width(180);
			jQuery('#catalog-top-left').css('padding-right', '30px');
			jQuery('#catalog-top-right').css('padding-left', '30px');
			jQuery('#catalog-bottom-left .row').width(360);
			jQuery('#catalog-bottom-right .row').width(360);
			jQuery('#catalog-bottom-left').css('padding-right', '20px');
			jQuery('#catalog-bottom-right').css('padding-left', '20px');
		}
		else if (jQuery(window).width() >= 1800 && jQuery(window).width() < 1900) {
			jQuery('#catalog-top-left .row').width(225);
			jQuery('#catalog-top-right .row').width(225);
			jQuery('#catalog-top-left').css('padding-right', '40px');
			jQuery('#catalog-top-right').css('padding-left', '40px');
			jQuery('#catalog-bottom-left .row').width(450);
			jQuery('#catalog-bottom-right .row').width(450);
			jQuery('#catalog-bottom-left').css('padding-right', '25px');
			jQuery('#catalog-bottom-right').css('padding-left', '25px');
		}
		else {
			jQuery('#catalog-top-left .row').width(270);
			jQuery('#catalog-top-right .row').width(270);
			jQuery('#catalog-top-left').css('padding-right', '50px');
			jQuery('#catalog-top-right').css('padding-left', '50px');
			jQuery('#catalog-bottom-left .row').width(540);
			jQuery('#catalog-bottom-right .row').width(540);
			jQuery('#catalog-bottom-left').css('padding-right', '30px');
			jQuery('#catalog-bottom-right').css('padding-left', '30px');
		}
	}
	else {
// 		jQuery('#catalog-top-main .col-xs-6:eq(0)').html('');
// 		jQuery('#catalog-top-main .col-xs-6:eq(1)').html('');
// 		jQuery('#catalog-top-main .col-xs-6:eq(2)').html('');
// 		jQuery('#catalog-top-main .col-xs-6:eq(3)').html('');
	    jQuery('#catalog-top-main .col-xs-6:eq(0)').append(jQuery('#catalog-top-left .product-item-container:eq(0)'));
	    jQuery('#catalog-top-main .col-xs-6:eq(1)').append(jQuery('#catalog-top-left .product-item-container:eq(1)'));
	    jQuery('#catalog-top-main .col-xs-6:eq(2)').append(jQuery('#catalog-top-right .product-item-container:eq(0)'));
	    jQuery('#catalog-top-main .col-xs-6:eq(3)').append(jQuery('#catalog-top-right .product-item-container:eq(1)'));
		jQuery('#catalog-top-main').show();
		jQuery('#catalog-top-left').hide();	
		jQuery('#catalog-top-right').hide();
		jQuery('#catalog-top-center').css('display', 'block');
// 		jQuery('#catalog-bottom-main .col-xs-6:eq(0)').html('');
// 		jQuery('#catalog-bottom-main .col-xs-6:eq(1)').html('');
// 		jQuery('#catalog-bottom-main .col-xs-6:eq(2)').html('');
// 		jQuery('#catalog-bottom-main .col-xs-6:eq(3)').html('');
	    jQuery('#catalog-bottom-main .col-xs-6:eq(0)').append(jQuery('#catalog-bottom-left .product-item-container:eq(0)'));
	    jQuery('#catalog-bottom-main .col-xs-6:eq(1)').append(jQuery('#catalog-bottom-left .product-item-container:eq(1)'));
	    jQuery('#catalog-bottom-main .col-xs-6:eq(2)').append(jQuery('#catalog-bottom-right .product-item-container:eq(0)'));
	    jQuery('#catalog-bottom-main .col-xs-6:eq(3)').append(jQuery('#catalog-bottom-right .product-item-container:eq(1)'));
		jQuery('#catalog-bottom-main').show();
		jQuery('#catalog-bottom-left').hide();	
		jQuery('#catalog-bottom-right').hide();
	}
}
</script>
