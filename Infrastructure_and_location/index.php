<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Инфраструктура и расположение");
?><h5>ЖК расположен в зеленой зоне между ТРК Сити Молл и Аквапарком, в шаговой доступности планируется строительство учебных заведений.</h5>
 <img width="1920" src="/Infrastructure_and_location/фото%201.jpg" height="1080"><br>
<h5><img width="1920" src="/Infrastructure_and_location/фото%202.jpg" height="1080"><br>
</h5>
<h5>Расположение на карте</h5>
 <?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	".default",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"CONTROLS" => array(0=>"ZOOM",1=>"MINIMAP",2=>"TYPECONTROL",3=>"SCALELINE",),
		"INIT_MAP_TYPE" => "HYBRID",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:46.90387138431599;s:10:\"yandex_lon\";d:142.7420117767137;s:12:\"yandex_scale\";i:15;s:9:\"POLYLINES\";a:1:{i:0;a:3:{s:6:\"POINTS\";a:5:{i:0;a:2:{s:3:\"LAT\";d:46.90644388733583;s:3:\"LON\";d:142.74551233903898;}i:1;a:2:{s:3:\"LAT\";d:46.90657623952394;s:3:\"LON\";d:142.73871025697724;}i:2;a:2:{s:3:\"LAT\";d:46.90219373670263;s:3:\"LON\";d:142.7400620903207;}i:3;a:2:{s:3:\"LAT\";d:46.90192901022126;s:3:\"LON\";d:142.74349531785978;}i:4;a:2:{s:3:\"LAT\";d:46.90634094651773;s:3:\"LON\";d:142.7455552543832;}}s:5:\"TITLE\";s:0:\"\";s:5:\"STYLE\";a:2:{s:11:\"strokeColor\";s:8:\"FFFFFFFF\";s:11:\"strokeWidth\";i:3;}}}}",
		"MAP_HEIGHT" => "500",
		"MAP_ID" => "",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(0=>"ENABLE_SCROLL_ZOOM",1=>"ENABLE_DBLCLICK_ZOOM",2=>"ENABLE_DRAGGING",)
	)
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>