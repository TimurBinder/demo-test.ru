<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контактная информация");
?><span style="font-size: 22px;"><?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	".default",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"CONTROLS" => array(0=>"ZOOM",1=>"MINIMAP",2=>"TYPECONTROL",3=>"SCALELINE",),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:46.93428351099627;s:10:\"yandex_lon\";d:142.75312866851456;s:12:\"yandex_scale\";i:16;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:142.75470580741535;s:3:\"LAT\";d:46.93361106729492;s:4:\"TEXT\";s:10:\"Rose-House\";}}}",
		"MAP_HEIGHT" => "500",
		"MAP_ID" => "",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(0=>"ENABLE_SCROLL_ZOOM",1=>"ENABLE_DBLCLICK_ZOOM",2=>"ENABLE_DRAGGING",)
	)
);?></span><br>
<h3>Месторасположение офиса:</h3>
<p>
	 г. Южно-Сахалинск, ул. Комсомольская 271 А, корпус 1 "Rose-House".
</p>
<p>
	 Режим работы: понедельник - пятница, с 9:00 до 18:00
</p>
<p>
	 Телефоны: +7 (4242) 505-333 (отдел продаж)
</p>
<p>
	 +7 (4242) 73-33-33 (отдел продаж)
</p>
 Факс: +7 (4242) 50-50-99<br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>