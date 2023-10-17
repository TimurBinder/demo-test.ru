<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контактная информация");
?><?$APPLICATION->IncludeComponent(
	"bitrix:map.google.view",
	"map",
	Array(
		"API_KEY" => "",
		"COMPONENT_TEMPLATE" => "map",
		"CONTROLS" => array(0=>"SMALL_ZOOM_CONTROL",1=>"TYPECONTROL",2=>"SCALELINE",),
		"HIDDEN_ADAPTIVE" => "N",
		"INIT_MAP_TYPE" => "ROADMAP",
		"MAP_DATA" => "a:4:{s:10:\"google_lat\";d:40.81770957767166;s:10:\"google_lon\";d:-73.91311141052209;s:12:\"google_scale\";i:14;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:4:\"TEXT\";s:5:\"Точка\";s:3:\"LON\";d:-73.942623138428;s:3:\"LAT\";d:40.816927231915;}}}",
		"MAP_HEIGHT" => "350",
		"MAP_ID" => "",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(0=>"ENABLE_SCROLL_ZOOM",1=>"ENABLE_DBLCLICK_ZOOM",2=>"ENABLE_DRAGGING",3=>"ENABLE_KEYBOARD",),
		"RSAUTOCITY_ADD_CONTAINER" => "Y",
		"RSAUTOCITY_BLOCK_MARGIN" => ""
	)
);?>
<h2><span style="font-size: 22px;">ООО «Master»</span></h2>
<table cellpadding="0" cellspacing="0" height="228">
<tbody>
<tr>
	<td valign="top" width="170">
 <span>&nbsp;Адрес: </span>
	</td>
	<td valign="top">
 <span>440013, Россия, г. Москва, пр-кт. Мира, д. 1а, кор. 2, офис 3145 </span>
	</td>
</tr>
<tr>
	<td valign="top" width="170">
 <span>Телефоны: </span>
	</td>
	<td valign="top">
 <span>8 (800) 100-20-22</span><br>
 <span>8 (800) 200-30-33</span>
	</td>
</tr>
<tr>
	<td valign="top" width="170">
 <span>Факс: </span>
	</td>
	<td valign="top">
 <span>8 (800) 300-33-33</span><br>
	</td>
</tr>
<tr>
	<td colspan="1" valign="top" width="170">
 <span>Эл. почта: </span>
	</td>
	<td colspan="1" valign="top">
 <a href="mailto:info@info.com"><span>info@info.com</span></a>
	</td>
</tr>
<tr>
	<td colspan="1" valign="top" width="170">
 <span>Режим работы: </span>
	</td>
	<td colspan="1" valign="top">
 <span>Понедельник–пятница, с 8:00 до 18:00</span><br>
 <span>
		Суббота, с 8:00 до 15:00</span><br>
 <span>
		Воскресенье — выходной </span>
	</td>
</tr>
</tbody>
</table>
<h2 style="font-size: 22px;">
Отдел продаж</h2>
<table style="margin-bottom:25px;" cellpadding="4" cellspacing="0" height="228">
<tbody>
<tr>
	<td valign="top" width="170" style="padding-bottom:7px;">
 <span>
		Адрес: </span>
	</td>
	<td valign="top" style="padding-bottom:7px;">
 <span>440013, Россия, г. Москва, ул. Егоркина, д. 125, кор. 2, офис 3100</span>
	</td>
</tr>
<tr>
	<td colspan="1" valign="top" style="padding-bottom:7px;">
 <span>Руководитель:</span><br>
	</td>
	<td colspan="1" valign="top" style="padding-bottom:7px;">
 <span>Дмитрий Матвеев</span>
	</td>
</tr>
<tr>
	<td colspan="1" valign="top" style="padding-bottom:7px;">
 <span>Специалисты:</span>
	</td>
	<td colspan="1" style="padding-bottom:7px;" valign="top">
 <span>Борисов Станислав</span><br>
 <span>Ерунова Валентина</span><br>
 <span>Филипин Евгений</span><br>
	</td>
</tr>
<tr>
	<td valign="top" width="170" style="padding-bottom:7px;">
 <span>Телефоны: </span>
	</td>
	<td valign="top" style="padding-bottom:7px;">
 <span>8 (800) 123-10-01&nbsp;</span><br>
 <span>8 (800) 123-20-02</span><br>
 <span>8 (800) 123-30-03</span><br>
 <span>8 (800) 123-40-04</span><br>
 <span>8 (800) 123-50-05</span><br>
	</td>
</tr>
<tr>
	<td valign="top" width="170" style="padding-bottom:7px;">
 <span>Факс: </span>
	</td>
	<td valign="top" style="padding-bottom:7px;">
 <span>8 (800) 101-20-10</span><br>
	</td>
</tr>
<tr>
	<td colspan="1" valign="top" width="170" style="padding-bottom:7px;">
 <span>Эл. почта: </span>
	</td>
	<td colspan="1" valign="top" style="padding-bottom:7px;">
 <a href="mailto:info@info.com"><span>info@info.com</span></a>
	</td>
</tr>
<tr>
	<td colspan="1" valign="top" width="170" style="padding-bottom:7px;">
 <span>Режим работы: </span>
	</td>
	<td colspan="1" valign="top" style="padding-bottom:7px;">
 <span>Понедельник–пятница, с 8:00 до 18:00</span><br>
 <span>
		Суббота, с 8:00 до 15:00</span><br>
 <span>
		Воскресенье — выходной </span>
	</td>
</tr>
</tbody>
</table>
<h2 style="font-size: 22px;">Юридический отдел</h2>
 <span style="margin-bottom:30px;">
<table cellpadding="0" cellspacing="0" height="228">
<tbody>
<tr>
	<td valign="top" width="170">
 <span>
		&nbsp;Адрес: </span>
	</td>
	<td valign="top">
 <span>440013, Россия, г. Москва, ул. Егоркина, д. 125, кор. 2, офис 3250 </span>
	</td>
</tr>
<tr>
	<td colspan="1">
 <span>Руководитель:</span>
	</td>
	<td colspan="1">
 <span>Елена&nbsp;Смирнова</span>
	</td>
</tr>
<tr>
	<td colspan="1">
 <span>Специалисты:</span>
	</td>
	<td colspan="1">
 <span>Анна Завьялова</span>
	</td>
</tr>
<tr>
	<td valign="top" width="170">
 <span>Телефоны: </span>
	</td>
	<td valign="top">
 <span>8 800 100-10-00</span><br>
 <span>8 800 101-10 01</span><br>
 <span> </span>
	</td>
</tr>
<tr>
	<td valign="top" width="170">
 <span>Факс: </span>
	</td>
	<td valign="top">
 <span>8 800 120-10-02</span><br>
	</td>
</tr>
<tr>
	<td colspan="1" valign="top" width="170">
 <span>Эл. почта: </span>
	</td>
	<td colspan="1" valign="top">
 <a href="mailto:info@info.com"><span>info@info.com</span></a>
	</td>
</tr>
<tr>
	<td colspan="1" valign="top" width="170">
 <span>Режим работы: </span>
	</td>
	<td colspan="1" valign="top">
 <span>Понедельник–пятница, с 8:00 до 18:00</span><br>
 <span>
		Суббота, воскресенье — выходной</span>
	</td>
</tr>
</tbody>
</table>
 </span>
<h2><span style="font-size: 22px;">Реквизиты компании</span></h2>
<div style="line-height:19px;">
	<p style="margin-bottom:20px;">
		 Свидетельство: серия 59 № 888888888 от 20.07.17 года<br>
		 выдано ИМНС РФ по Новому району г. Москвы
	</p>
	<p style="margin-bottom:20px;">
		 ОГРН 404088888800666<br>
		 ИНН 888800000099<br>
		 р/с 88888800000069696988<br>
	</p>
	<p style="margin-bottom:20px;">
		 Московское ОСБ № 8960<br>
		 БИК 088656565<br>
		 к/с 88888810000000000999<br>
		 ОКОНХ 88000<br>
		 ОКВЭД 54.58.32.; 60.22.9.<br>
		 ОКПО 088809099
	</p>
</div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>