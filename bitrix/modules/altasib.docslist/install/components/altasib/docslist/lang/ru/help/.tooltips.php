<?
$MESS["IBLOCK_TYPE_TIP"]					= "Из выпадающего списка можно выбрать один из созданных в системе типов инфоблоков. Инфоблоки, созданные для выбранного типа, будут подгружены после нажатия кнопки <b><i>ок</i></b>. Тип здесь можно не выбирать.";
$MESS["IBLOCK_ID_TIP"]						= "В списке представлены все инфоблоки, созданные для установленного типа либо все инфоблоки системы, если тип не выбран. Можно выбрать один из инфоблоков либо пункт <i>другое</i>, тогда в поле рядом необходимо указать код инфоблока, например, ={\$_REQUEST[\"IBLOCK_ID\"]}.";
$MESS["DOCS_COUNT_TIP"]						= "Указывается количество элементов, которые будут выведены на одной странице. Если поле пусто и включен вывод по разделам, будут показаны все элементы.";
$MESS["PROPERTY_CODE_TIP"]					= "Перечислены свойства элементов инфоблока, среди которых можно выбрать те, по которым будет возможна фильтрация. Также можно добавить другие поля в виде символьного кода (в полях ниже).";
$MESS["SECTIONS_SELECT_TIP"]				= "Перечислены разделы элементов инфоблока, среди которых можно выбрать те, по которым будет возможна фильтрация";
$MESS["DISPLAY_DOCSSECTION_TIP"]			= "При установленной опции список документов будет выведен по разделам, согласно указанной сортировке. Постраничная навигация не доступна.";
$MESS["SET_TITLE_TIP"]						= "При установленной опции в качестве заголовка страницы будет установлено имя текущего инфоблока.";
$MESS["INCLUDE_INTO_CHAIN_TIP"]				= "При установленной опции в цепочку навигации будет добавлено имя инфоблока.";
$MESS["DISPLAY_SIZE_TIP"]					= "При установленной опции на странице будут отображатся размеры документов.";
$MESS["DOWNLOAD_COUNT_TIP"]					= "При установленной опции, если документ скачан один и более раз, будет отображено количество скачиваний документа.";
$MESS["ACTIVE_DATE_FORMAT_TIP"]				= "В списке перечислены все возможные варианты показа даты, формируемые внутри компонента. Выбрав пункт <i>другое</i>, можно сформировать свой вариант на основании php-функции <i>date</i>.";
$MESS["CACHE_GROUPS_TIP"]					= "При установленной опции будут учитыватся права доступа, установленные на инфоблок/раздел/документ.";
$MESS["PREVIEW_TRUNCATE_LEN_TIP"]			= "Если тип анонса у элемента инфоблока - текст, то можно указать максимальную длину, после которой анонс будет отсечен.";
$MESS["DISPLAY_DATE_TIP"]					= "При установленной опции на странице будут отображатся дата размещения документа или дата последнего изменения, если кумент был обновлен.";
$MESS["DISPLAY_TOP_PAGER_TIP"]				= "При отмеченной опции навигация по элементам будет выведена вверху страницы, над списком.";
$MESS["DISPLAY_BOTTOM_PAGER_TIP"]			= "При отмеченной опции навигация по элементам будет выведена внизу страницы, под списком.";
$MESS["PAGER_SHOW_ALWAYS_TIP"]				= "Если флаг не отмечен, постраничная навигация не будет выводиться, если все элементы помещаются на одной странице. Если отмечен, то будет выводиться всегда.";
$MESS["PAGER_TEMPLATE_TIP"]					= "Указывается имя шаблона постраничной навигации. Если поле пусто, то выбирается шаблон по умолчанию (<i>.default</i>). Также в системе задан шаблон <i>orange</i>.";
$MESS["PAGER_TITLE_TIP"]					= "В данном поле указывается название категорий, по которым происходит перемещение по списку (например, новости, статьи и др.)";
$MESS["PAGER_DESC_NUMBERING_TIP"]			= "Механизм используют, если при добавлении элемента инфоблока он попадает наверх списка. Таким образом меняется лишь последняя страница. Все предыдущие можно надолго закешировать.";
$MESS["PAGER_DESC_NUMBERING_CACHE_TIME_TIP"] = "Указывается время кеширования страниц для обратной навигации в секундах.";
$MESS["DOWNLOAD_DOC_TIP"]					= 'Поле содержит код, в котором передается ID документа. По умолчанию поле содержит ={$_REQUEST["EID"]}.';
$MESS["USER_SECTION_TIP"]					= 'Поле содержит код, в котором передается ID раздела. По умолчанию поле содержит ={$_REQUEST["SID"]}.';
?>