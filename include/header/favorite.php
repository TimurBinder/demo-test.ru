<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>
<?$APPLICATION->IncludeComponent(
	"redsign:favorite.list", 
	"top", 
	array(
		"ACTION_VARIABLE" => "favaction",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "N",
		"FAVORITE_URL" => "/wishlist/",
		"PRODUCT_ID_VARIABLE" => "id",
		"COMPONENT_TEMPLATE" => "top"
	),
	false
);?>