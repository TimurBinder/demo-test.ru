<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Главная | Роза Town");
?><?
if ($APPLICATION->GetShowIncludeAreas()) echo '<style>body {overflow: visible!Important;}</style>';
?> <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"slider",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "38",
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"ZOOM_PRC",1=>"ZOOM_SPEED",2=>"SPEED",3=>"BTN_TEXT",4=>"BTN_URL"),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?> <nav>
<div class="nav_wrapper">
	<div class="nav_btn_close">
 <button class="burger_btn_close"><img alt="close" src="/upload/img/X.png"></button>
		<p>
			Закрыть
		</p>
	</div>
	 <?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"home",
	Array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(0=>"",),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "home",
		"USE_EXT" => "N"
	)
);?>
	<div class="nav_icon">
		 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => "/include/home_menu_icon.php"
	)
);?>
	</div>
	 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => "/include/home_menu_text.php"
	)
);?>
	<div id="list_active" class="nav_a_act">
 <img alt="1" src="/upload/img/arrow_right.png">
	</div>
</div>
<div class="nav_img">
 <img alt="1" src="/upload/img/rose-big.png">
</div>
 </nav> <section class="section_1">
<div>
	<div class="container">
		<div class="header_img_rose">
 <img alt="rose" src="/upload/img/rose-big.png">
		</div>
		<div class="nav_btn">
 <button class="burger_btn"><img alt="burger" src="/upload/img/burger.png"></button>
			<p>
				Меню
			</p>
		</div>
		<div class="header_info">
			<div class="phone_img">
 <img alt="phone" src="/upload/img/phone.png">
			</div>
			<div class="phone_img_2">
 <img alt="phone" src="/upload/img/phone_black.png">
			</div>
			 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => "/include/home_phone.php"
	)
);?>
		</div>
	</div>
	 <!-- GETSALE CODE START --> <script type="text/javascript">
    (function(d, w, c) {
      w[c] = {
        projectId: 7283
      };

      var n = d.getElementsByTagName("script")[0],
      s = d.createElement("script"),
      f = function () { n.parentNode.insertBefore(s, n); };
      s.type = "text/javascript";
      s.async = true;
      s.src = "//rt.getsale.io/loader.js";

      if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
      } else { f(); }

    })(document, window, "getSaleInit");
  </script> <!-- GETSALE CODE END -->
</div>
 <!-- <div class="container"> --> <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"slider_text",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "38",
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"ZOOM_PRC",1=>"ZOOM_SPEED",2=>"SPEED",3=>"BTN_TEXT",4=>"BTN_URL"),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?>
<div class="container">
	<div class="non_active">
	</div>
	<div class="non_active_2">
	</div>
	<div class="non_active_3">
	</div>
	<div class="non_active_4">
	</div>
	<div class="non_active_5">
	</div>
	<div class="non_active_6">
	</div>
	<div class="section_1_bottom">
		 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"home_tabs",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "N",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "home_tabs",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"PREVIEW_PICTURE",1=>"DETAIL_PICTURE",2=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "39",
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"TAB_URL",1=>"TAB_TEXT",2=>"",),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?>
	</div>
</div>
 <?/*
	<div class="bottom_item" id="bott2">
	<div class="bottom_content" id="bc2">
	<div id="cont_wrap_2" class="bottom_cont_wrap">
	<div class="bottom_content_img">
	<img class="cont_img_2" src="img/Group%206.png" alt="logo">
	</div>
	<div class="bottom_content_title">
	<h3 class="content_bottom_title_margin_1 cont_title_2">Ввод в эксплуатацию</h3>
	</div>
	<div class="bottom_content_text">
	<p><span class="paragraph_1">Роза Хаус 1 – 
	введен в эксплуатацию 
	(общая площать 20 000 м²)</span>

	<span class="paragraph_2">Роза Хаус 2 – Находится на стадии ввода в эксплуатацию</span></p>
	</div>

	<a href="#" class="bottom_content_btn">Узнать больше <span><img src="img/right-arrow-2.png" alt=""></span></a>
	</div>


	</div>
	<div class="bottom_item_before"><img src="img/form_1.png" alt="arrow"></div>
	<div class="bottom_img">
	<img src="img/Group%206.png" alt="group">
	</div>
	<div class="bottom_text">
	<p>Ввод в эксплуатацию</p>
	</div>
	<div class="bot_item_img">
	<img src="img/block-6.png" alt="1">
	</div>
	</div>

	<div class="bottom_item" id="bott3">
	<div class="bottom_content" id="bc3">
	<div id="cont_wrap_3" class="bottom_cont_wrap">
	<div class="bottom_content_img">
	<img class="cont_img_3" src="img/piggybank.png" alt="logo">
	</div>
	<div class="bottom_content_title">
	<h3 class="cont_title_3">Стоимость</h3>
	</div>
	<div class="bottom_content_text">
	<p></p>
	</div>
	<a href="#" class="bottom_content_btn">Узнать больше <span><img src="img/right-arrow-2.png" alt=""></span></a>

	</div>
	</div>
	<div class="bottom_item_before"><img src="img/form_1.png" alt="arrow"></div>
	<div class="bottom_img">
	<img src="img/piggybank.png" alt="logo">
	</div>
	<div class="bottom_text">
	<p>Стоимость</p>
	</div>
	<div class="bot_item_img">
	<img src="img/SSD%20Copy%206.png" alt="1">
	</div>
	</div>
	<div class="bottom_item" id="bott4">
	<div class="bottom_content" id="bc4">
	<div id="cont_wrap_4" class="bottom_cont_wrap">
	<div class="bottom_content_img">
	<img class="cont_img_4" src="img/sberbank-1.png" alt="logo">
	</div>
	<div class="bottom_content_title">
	<h3 class="content_bottom_title_margin_2 cont_title_4">Ипотека</h3>
	</div>
	<div class="bottom_content_text">
	<p class="text_ipo">Можно оформить через банки:</p>
	</div>
	<div class="bottom_content_icons">
	<div><img src="img/sberbank-big.png" alt="1"></div>
	<div> <img src="img/vtb-bank.png" alt="1"></div>

	</div>
	<a href="#" class="bottom_content_btn">Узнать больше <span><img src="img/right-arrow-2.png" alt=""></span></a>

	</div>
	</div>
	<div class="bottom_item_before"><img src="img/form_1.png" alt="arrow"></div>
	<div class="bottom_img">
	<img src="img/sberbank-1.png" alt="sberbank">
	</div>
	<div class="bottom_text">
	<p>Ипотека</p>
	</div>
	<div class="bot_item_img">
	<img src="img/SSD%20Copy%207.png" alt="1">
	</div>
	</div>
	<div class="bottom_item" id="bott5">
	<div class="bottom_content" id="bc5">
	<div class="bottom_cont_wrap">
	<div class="bottom_content_img">
	<img class="cont_img_5" src="img/placeholder.png" alt="logo">
	</div>
	<div class="bottom_content_title">
	<h3 class="content_bottom_title_margin_3 cont_title_5">Инфраструктура
	и расположение</h3>
	</div>
	<div class="bottom_content_text">
	<p>ЖК расположен в зеленой 
	зоне между ТРК Сити Молл 
	и Аква-Парком, в шаговой доступности планируются учебные и спортивные заведения.</p>
	</div>
	<a href="#" class="bottom_content_btn">Узнать больше <span><img src="img/right-arrow-2.png" alt=""></span></a>

	</div>
	</div>
	<div class="bottom_item_before"><img src="img/form_1.png" alt="arrow"></div>
	<div class="bottom_img">
	<img class="fifth_icon" src="img/placeholder.png" alt="locate">
	</div>
	<div class="bottom_text">
	<p>Инфраструктура
	и расположение</p>
	</div>
	<div class="bot_item_img">
	<img src="img/SSD%20Copy%208.png" alt="1">
	</div>
	</div>
	<div class="bottom_item" id="bott6">
	<div class="bottom_content" id="bc6">
	<div id="cont_wrap_6" class="bottom_cont_wrap">
	<div class="bottom_content_img">
	<img class="cont_img_6" src="img/sketching.png" alt="logo">
	</div>
	<div class="bottom_content_title">
	<h3 class="content_bottom_title_margin_4 cont_title_6">Планировки</h3>
	</div>
	<div class="bottom_content_text">
	<p>РОУЗ-ТАУН предлагает просторные квартиры 
	от 33 кв.м (не апартаменты!)
	Кликнув по кнопке ниже, вы сможете выбрать планировку, которая вам больше нравится.</p>
	</div>
	<a href="#" class="bottom_content_btn">Узнать больше <span><img src="img/right-arrow-2.png" alt=""></span></a>
	</div>

	</div>
	<div class="bottom_item_before"><img src="img/form_1.png" alt="arrow"></div>
	<div class="bottom_img">
	<img src="img/sketching.png" alt="logo">
	</div>
	<div class="bottom_text">
	<p>Планировки</p>
	</div>
	<div class="bot_item_img">
	<img src="img/SSD%20Copy%209.png" alt="1">
	</div>
	</div>




	</div>






	</section>
	<section class="section_2">
	<div class="section_2_title">
	<div class="container">
	<div class="title_2_h1"><h1>ЖК Роза Town</h1></div>
	<div class="title_2_p"><p>Квартиры от 33 кв.м. <br>
	с возможностью рассрочки 
	и в ипотеку</p></div>
	<div class="title_btn btn_2"><p>Выбрать квартиру</p></div>

	</div>
	</div>
	<div class="slider">
	<div class="slider_item">
	<div class="slider_wrap">
	<div class="slider-img">
	<img class="cont_1" src="img/logo-about-big.png" alt="1">
	</div>
	<div class="slider_title">
	<h3>Застройщик</h3>
	</div>
	<div class="slider_text slid_txt_1">
	<p>Строительный холдинг <br>
	«SSD Group» — динамично развивающаяся многопрофильная группа компаний, осуществляющая полный комплекс работ 
	от проектирования до строительства «под ключ» жилья по Сахалинской области</p>
	</div>
	<a class="slider_button">Узнать больше <span><img src="img/right-arrow-2.png" alt=""></span></a>
	</div>
	</div>
	<div class="slider_item">
	<div class="slider_wrap">
	<div class="slider-img">
	<img src="img/Group%206.png" alt="1">
	</div>
	<div class="slider_title">
	<h3>Ввод в эксплуатацию</h3>
	</div>
	<div class="slider_text slid_txt_2">
	<p><span class="paragraph_1">Роза Хаус 1 – 
	введен в эксплуатацию 
	(общая площать 20 000 м²)</span>

	<span class="paragraph_2">Роза Хаус 2 – Находится на стадии ввода в эксплуатацию</span></p>
	</div>
	<a class="slider_button">Узнать больше <span><img src="img/right-arrow-2.png" alt=""></span></a>
	</div>
	</div>
	<div class="slider_item">
	<div class="slider_wrap">
	<div class="slider-img">
	<img src="img/piggybank.png" alt="1">
	</div>
	<div class="slider_title">
	<h3>Стоимость</h3>
	</div>
	<a class="slider_button">Узнать больше <span><img src="img/right-arrow-2.png" alt=""></span></a>
	</div>
	</div>
	<div class="slider_item">
	<div class="slider_wrap">
	<div class="slider-img">
	<img src="img/sberbank-1.png" alt="1">
	</div>
	<div class="slider_title">
	<h3>Ипотека</h3>
	</div>
	<div class="slider_text slid_txt_3">
	<p>Можно оформить через банки:</p>
	</div>
	<div class="slider_icons">
	<div><img src="img/sberbank-big.png" alt="1"></div>
	<div> <img src="img/vtb-bank.png" alt="1"></div>

	</div>
	<a class="slider_button">Узнать больше <span><img src="img/right-arrow-2.png" alt=""></span></a>
	</div>
	</div>
	<div class="slider_item">
	<div class="slider_wrap">
	<div class="slider-img">
	<img class="cont_5" src="img/placeholder.png" alt="1">
	</div>
	<div class="slider_title">
	<h3>Инфраструктура
	и расположение</h3>
	</div>
	<div class="slider_text slid_txt_4">
	<p>ЖК расположен в зеленой 
	зоне между ТРК Сити Молл 
	и Аква-Парком, в шаговой доступности планируются учебные и спортивные заведения.</p>
	</div>
	<a class="slider_button">Узнать больше <span><img src="img/right-arrow-2.png" alt=""></span></a>
	</div>
	</div>
	<div class="slider_item">
	<div class="slider_wrap">
	<div class="slider-img">
	<img src="img/sketching.png" alt="1">
	</div>
	<div class="slider_title">
	<h3>Планировки</h3>
	</div>
	<div class="slider_text slid_txt_5">
	<p>РОУЗ-ТАУН предлагает просторные квартиры 
	от 33 кв.м (не апартаменты!)
	Кликнув по кнопке ниже, вы сможете выбрать планировку, которая вам больше нравится.</p>
	</div>
	<a class="slider_button">Узнать больше <span><img src="img/right-arrow-2.png" alt=""></span></a>
	</div>
	</div>
	</div>
	</div>
*/?> </section> <section class="section_2">
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"home_tabs_mob",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "N",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"PREVIEW_PICTURE",1=>"DETAIL_PICTURE",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "39",
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"TAB_TEXT",1=>"TAB_URL",),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?> 
</section><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>