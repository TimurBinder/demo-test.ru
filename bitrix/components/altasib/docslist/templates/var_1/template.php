<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
/**
 * @var $arResult array
 * @var $arParams array
 * @var $this CBitrixComponentTemplate
 */

$fVerComposite = (defined("SM_VERSION") && version_compare(SM_VERSION, "14.5.0") >= 0 ? true : false);
if($fVerComposite){
	$this->setFrameMode(true);
	$frame = $this->createFrame()->begin("");
}
?>
<style type="text/css">
.al-dl-docs-list .al-dl-docs-item{
	background:<?=$arParams["COLOR_BG_EVEN"]?>;
	border-bottom:1px solid <?=$arParams["COLOR_BORDER"]?>;
}
.al-dl-docs-list .al-dl-doc-section-name{
	border-bottom:2px solid <?=$arParams["COLOR_BORDER_TOP"]?>;
}
.al-dl-sect-list{
	border-top:2px solid <?=$arParams["COLOR_BORDER_TOP"]?> !important;
}
.al-dl-sect-list{
	border:1px solid <?=$arParams["COLOR_BORDER"]?>;
}
.al-dl-sect-list ul li{
	border-top:1px solid <?=$arParams["COLOR_BORDER"]?>;
}
.al-dl-sect-list ul li.al-dl-sect-sel{
	background-color:<?=$arParams["COLOR_BG_HOVER"]?>;
}
.al-dl-docs-list .al-dl-docs-item-bg, .al-dl-sect-list .al-dl-sec-title{
	background:<?=$arParams["COLOR_BG_ODD"]?>;
}
.al-dl-docs-list .al-dl-docs-item:hover{
	background:<?=$arParams["COLOR_BG_HOVER"]?>;
}
.al-dl-docs-list .al-dl-docs-item .docs-date-time, .al-dl-docs-list .al-dl-docs-item .al-dl-doc-info-bottom{
	color:<?=$arParams["COLOR_DATE"]?>;
}
</style>
<div class="al-dl-docs-list<?if($arParams["DISPLAY_LIST_SECTION"]):?> al-dl-docs-list-float<?endif;?>">
<?if(!$arParams["DISPLAY_DOCSSECTION"] && $arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br/>
<?endif;?>
<?$i_bg =0;?>
<?foreach($arResult["ITEMS"] as $arItems):
	if ($arParams["DISPLAY_DOCSSECTION"]):?>
		<div class="al-dl-doc-section-name"><?=$arItems["NAME"]?></div><?
	endif;?>
<?	foreach($arItems["ELEMENTS"] as $arItem):
		$arFile = $arItem["DOCFILE"];
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

		if(isset($arItem["PREVIEW_PICTURE"]))
		{
			if($arItem["PREVIEW_PICTURE"]["WIDTH"]>49 || $arItem["PREVIEW_PICTURE"]["HEIGHT"]>51)
			{
				$iconResizeIMG = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>49, 'height'=>51), BX_RESIZE_IMAGE_PROPORTIONAL, false);
				$iconPath = $iconResizeIMG["src"];
			}
			else
			{
				$iconPath = $arItem["PREVIEW_PICTURE"]["SRC"];
			}
			$iconTitle = $arItem["PREVIEW_PICTURE"]["TITLE"];
			$iconAlt = $arItem["PREVIEW_PICTURE"]["ALT"];
		}
		else
		{
			$iconPath = file_exists($_SERVER["DOCUMENT_ROOT"].$this->GetFolder().'/images/'.$arFile["FILE_TYPE"].'.png') ? $this->GetFolder().'/images/'.$arFile["FILE_TYPE"].'.png' : $this->GetFolder().'/images/icon.png';
		}
	?>
	<div class="al-dl-docs-item<?if($i_bg%2==0):?> al-dl-docs-item-bg<?endif?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<div class="al-dl-doc-icon"><img src="<?=$iconPath?>"<?echo (!empty($iconTitle) ? ' title="'.$iconTitle.'"' : "");?><?echo (!empty($iconAlt) ? ' alt="'.$iconAlt.'"' : "");?>></div>
<?		if($arParams["DISPLAY_DATE"]!="N" && ($arItem["CREATE_DATE"] || $arItem["UPDATE_DATE"])):?>
			<div class="docs-date-time"><?
				echo $arItem["CREATE_DATE"]? GetMessage("ALTASIB_SHOW_DATA_CREATE") : GetMessage("ALTASIB_SHOW_DATA_UPDATE");
				echo $arItem["CREATE_DATE"]? $arItem["CREATE_DATE"] : $arItem["UPDATE_DATE"];
			?></div>
<?			endif?>
			<div class="al-dl-doc-name"><a href="?EID=<?=$arItem["ID"]?>"><?=$arItem["NAME"]?></a></div>
<?			if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"] != ''):?>
			<div class="al-dl-discribe"><?=$arItem["PREVIEW_TEXT"];?></div>
<?			endif;?>
			<div class="al-dl-doc-info-bottom">
				<a href="?EID=<?=$arItem["ID"]?>" class="al-dl-load"><?=GetMessage("ALTASIB_DOWNLOAD_FILE")?></a> (<?=$arFile["FILE_TYPE"]?><?if($arParams["DISPLAY_SIZE"]!="N"):?>, <?=$arFile["FILE_SIZE"]?><?endif;?>)<?
				if($arParams["DOWNLOAD_COUNT"] != "N"):?> | <?=GetMessage("ALTASIB_SHOW_DATA_DOWNLOAD")?>: <?if($arItem["SHOW_COUNTER"]==''){ echo '0';} else{ echo $arItem["SHOW_COUNTER"];}?><?endif;
			?></div>
		<div class="al-dl-clear">&nbsp;</div>
	</div>
<?
	$i_bg++;
	endforeach;
endforeach;?>
<?if(!$arParams["DISPLAY_DOCSSECTION"] && $arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br/><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
<?if($arParams["DISPLAY_LIST_SECTION"] == "Y"):?>
	<div class="al-dl-sect-list">
		<div class="al-dl-sec-title"><?=GetMessage("ALTASIB_SECTIONS_DESC")?></div>
		<ul>
<?			if (count($arResult["SECTIONS"]) > 1):?>
			<li<?if (!$arParams["USER_SECTION"]):?> class="al-dl-sect-sel"<?endif?>>
				<a href="<?echo $APPLICATION->GetCurPageParam("", array("SID"));?>"><?=$arSection['NAME']?><?=GetMessage("ALTASIB_ALL_SECTIONS_DESC")?></a>
			</li>
<?			endif;?>
<?			foreach($arResult["SECTIONS"] as $arSection):?>
			<li<?if($arSection['ID'] == $arParams["USER_SECTION"]):?> class="al-dl-sect-sel"<?endif;?>>
				<a href="<?echo $APPLICATION->GetCurPageParam("SID=".$arSection['ID'], array("SID"));?>"><?=$arSection['NAME']?></a>
			</li>
<?			endforeach?>
		</ul>
	</div>
<?endif;
?>
<div class="al-dl-clear">&nbsp;</div>
<? if($fVerComposite) $frame->end(); ?>