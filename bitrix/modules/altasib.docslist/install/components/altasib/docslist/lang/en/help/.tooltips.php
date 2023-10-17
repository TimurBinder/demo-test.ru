<?
$MESS["IBLOCK_TYPE_TIP"]                       = "Select one of existing information block types in the list and click <b>OK</b>. This will load information blocks of the selected type. However, in this component this parameter is optional.";
$MESS["IBLOCK_ID_TIP"]                         = "Select here one of the existing information blocks. If you select <b><i>(other)</i></b>, you will have to specify the information block ID in the field beside. For example: <b>={\$_REQUEST[\"IBLOCK_ID\"]}</b>";
$MESS["DOCS_COUNT_TIP"]                        = "Specifies the number of elements per page. If the field is empty and the output is enabled by section, all items will be displayed.";
$MESS["PROPERTY_CODE_TIP"]                     = "Select information block properties that you want to display in the filter. You can also add your own fields in the fields below.";
$MESS["PREVIEW_TRUNCATE_LEN_TIP"]              = "If the information block preview media is text, you can specify the maximum count of symbols here. Any text beyond this limit will be truncated.";
$MESS["ACTIVE_DATE_FORMAT_TIP"]                = "Select here the required date format. If you select <i><b>other</b></i>, you can create your own format using the <i><b>date</b></i> PHP function.";
$MESS["SET_TITLE_TIP"]                         = "Checking this option will set the title to the name of a current information block.";
$MESS["INCLUDE_INTO_CHAIN_TIP"]                = "If checked, the information block name will be added to the navigation chain. ";
$MESS["DISPLAY_TOP_PAGER_TIP"]                 = "If checked, the element navigation links will be displayed on top of the page.";
$MESS["DISPLAY_BOTTOM_PAGER_TIP"]              = "If checked, the element navigation links will be displayed at the bottom top of the page.";
$MESS["PAGER_TITLE_TIP"]                       = "The name of an item unit for navigation. For example: page, chapter etc.";
$MESS["PAGER_SHOW_ALWAYS_TIP"]                 = "If unchecked, the breadcrumb navigation links will not be present if all items fit in a single page. Otherwise, the navigation links will be always shown.";
$MESS["PAGER_TEMPLATE_TIP"]                    = "The name of the breadcrumb navigation template. You can leave the field empty to use the default template (<b><i>.default</i></b>). (The system provides an alternative template: <i>orange</i>.)";
$MESS["PAGER_DESC_NUMBERING_TIP"]              = "Use this option if you want new elements to be placed on top. Thus, only the last page in breadcrumb navigation is modified. All other pages can be cached for a considerably long time.";
$MESS["PAGER_DESC_NUMBERING_CACHE_TIME_TIP"]   = "Specifies the cache time for pages (in seconds) when using the backward navigation.";
$MESS["SECTIONS_SELECT_TIP"]                   = "Information block sections listed items, among which you can choose those which will be possible to direct";
$MESS["DISPLAY_DOCSSECTION_TIP"]               = "When this option will display a list of documents by sections, according to the specified sorting. Page navigation is not available.";
$MESS["DISPLAY_SIZE_TIP"]                      = "When this option the page will check the size of documents.";
$MESS["DOWNLOAD_COUNT_TIP"]                    = "When this option if the document is downloaded one or more times, will show the number of downloads of the document.";
$MESS["CACHE_GROUPS_TIP"]                      = "When this option will take account of what the permissions set on the information block/section/document.";
$MESS["DISPLAY_DATE_TIP"]                      = "When this option the page will check the date of placement of the document or the date of last modification, if the documents has been updated.";
$MESS["DOWNLOAD_DOC_TIP"]                      = 'This field contains a code that is transmitted ID document. By default, the field contains ={$_REQUEST["EID"]}.';
$MESS["USER_SECTION_TIP"]                      = 'This field contains a code that is transmitted ID section. By default, the field contains  ={$_REQUEST["SID"]}.';
?>