<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$first = ' class="first_icon"';
$i = 1;
?>

<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
<?//print_r($arItem["PROPERTIES"])?>
<?/*<p class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">*/?>
				<div class="bottom_item" id="bott<?=$i?>">
					<div class="bottom_content" id="bc<?=$i?>">
						<div id="cont_wrap" class="bottom_cont_wrap">
							<div class="bottom_content_img"><img class="cont_img_<?=$i?>" src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="logo"></div>
							<div class="bottom_content_title"><h3 class="content_bottom_title_margin cont_title_<?=$i?>"><?=$arItem["NAME"]?></h3></div>
							<div class="bottom_content_text"><?=$arItem["PREVIEW_TEXT"]?></div>
							<a href="<?=$arItem["PROPERTIES"]["TAB_URL"]["VALUE"]?>" class="bottom_content_btn"><?=$arItem["PROPERTIES"]["TAB_TEXT"]["VALUE"]?> <span><img src="/upload/img/right-arrow-2.png" alt=""></span></a>
						</div>
					</div>

					<div class="bottom_item_before"><img src="/upload/img/form_1.png" alt="arrow"></div>
					<div class="bottom_img"><img<?=$first?> src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="logo"></div>
					<div class="bottom_text"><p><?=$arItem["NAME"]?></p></div>
					<div class="bot_item_img"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="1"></div>
				</div>
<?
$first = '';
$i++;
?>
<?endforeach;?>

