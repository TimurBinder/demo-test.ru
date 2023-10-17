<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О компании");

?><p>
	 ООО «Master» начала свою работу в 2004 году. С момента основания наша компания занимается производством товаров различного назначения: строительные и сварные металлоконструкции, а также индивидуальные проекты домов для заказчика. Кроме того, отдельным направлением нашей деятельности является изготовление оборудования для различных отраслей промышленности.
</p>
<h3>Наша цель</h3>
<p>
	 Наша цель - заниматься производством товаров и услуг наивысшего качества, которые бы улучшали жизнь наших клиентов. В свою очередь, клиенты помогают нам расти и занимать лидирующие позиции по уровню продаж, а также обеспечивать рост нашего бизнеса.
</p>
 <br>
 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"reviews", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_CONTAINER" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
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
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "#SYSTEM_QUOTES_IBLOCK_ID#",
		"IBLOCK_TYPE" => "system",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"IS_AJAX_PAGER" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "1",
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
		"PROPERTY_CODE" => array(
			0 => "AUTHOR_NAME",
			1 => "AUTHOR_JOB",
			2 => "",
		),
		"RS_AUTHOR_JOB" => "AUTHOR_JOB",
		"RS_AUTHOR_NAME" => "AUTHOR_NAME",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_DESCRIPTION" => "N",
		"SHOW_FEEDBACK_BUTTON" => "N",
		"SHOW_PARENT_NAME" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"USE_OWL" => "N",
		"COMPONENT_TEMPLATE" => "reviews"
	),
	false
);?>
<h3>Почему мы:</h3>
<ul>
	<li>Качественные товары по доступным ценам. Мы делаем товары недорогими и качественными, чтобы они удовлетворяли потребности всех наших клиентов. Компания работает над дизайном товаров, учитывая потребности клиентов.</li>
	<li>Мы за экологичность. Развивая наш бизнес, мы стремимся оказывать положительное влияние на экологию нашей планеты.</li>
	<li>Компания развивается вместе с людьми. Наши сотрудники играют важную роль в развитии компании. Мы видим талант и стараемся раскрыть потенциал каждого сотрудника.</li>
</ul>
<h3>Реквизиты компании</h3>
<p>
	 ООО "Master" <br>
</p>
<p>
	 Адрес: 440013, Россия, г. Москва, ул. Егоркина, д. 125, кор. 2, офис 3145 <br>
	 Телефоны: 0 000 000-00-00, 00 000 0 000 00 00, 0 000 000-00-00 <br>
	 Факс: 0 000 000-00-00 <br>
	 Эл. почта: <a href="mailto:info@info.com">info@info.com</a><br>
	 Режим работы: Время работы: Пн-Вс 10-19&nbsp;
</p>
<p>
	 Реквизиты: ИНН 342700894865 ОГРН 315583800000139 <br>
	 р. сч. 40802810118080000456 ФАКБ "Российский капитал" (ОАО) Тарханы <br>
	 корр. счет 30101810000000000716 БИК 045655716&nbsp;<br>
</p>
<p>
	 Общество с ограниченной ответственностью «Master» Реестровый номер МA1 000000000000000
</p>
 <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"staff",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"ASK_LINK" => "#SITE_DIR#include/forms/ask_staff/?element_id=#ELEMENT_ID#",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "staff",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "#COMPANY_STAFF_IBLOCK_ID#",
		"IBLOCK_TYPE" => "company",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "4",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "otdel-prodazh",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"NAME",1=>"POSITION",2=>"DESCRIPTION",3=>"CONTACTS",4=>"SOCIALS",5=>"IS_ASK",6=>"",),
		"PROP_CONTACTS" => "CONTACTS",
		"PROP_DESCRIPTION" => "DESCRIPTION",
		"PROP_IS_ASK" => "IS_ASK",
		"PROP_NAME" => "NAME",
		"PROP_POSITION" => "POSITION",
		"PROP_SOCIAL" => "SOCIALS",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_DESCRIPTION" => "Y",
		"SHOW_TITLE" => "Y",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?> <?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"files",
	Array(
		"ACTIVE_DATE_FORMAT" => "",
		"ADD_CONTAINER" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_NOTES" => "",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COLS_IN_ROW" => "4",
		"COL_LG" => "4",
		"COL_MD" => "4",
		"COL_SM" => "6",
		"COL_XS" => "12",
		"COMPONENT_TEMPLATE" => "files",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"ID",1=>"NAME",2=>"SORT",3=>"PREVIEW_TEXT",4=>"PREVIEW_PICTURE",5=>"DETAIL_PICTURE",6=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "#SYSTEM_JURISTIC_DOCUMENTS_IBLOCK_ID#",
		"IBLOCK_TYPE" => "system",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"",1=>"FILE",2=>"",),
		"PROP_FILE" => "FILE",
		"RS_TITLE" => "Юридические документы",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ID",
		"SORT_BY2" => "ID",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"USE_GALLERY" => "Y"
	)
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>