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
?>
<?$i = 1;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
<?$zoomspeed = $arItem["PROPERTIES"]["ZOOM_SPEED"]["VALUE"];?>
			<div class="section_<?=$i?>_content">
				<div class="container">
					<div class="title">
						<h1 class="title_h1"><?=$arItem["NAME"]?></h1>
						<h2 class="title_h2"><?=$arItem["PREVIEW_TEXT"]?></h2>
						<a href="<?=$arItem["PROPERTIES"]["BTN_URL"]["VALUE"]?>" style="text-decoration: none;"><div class="title_btn"><p><?=$arItem["PROPERTIES"]["BTN_TEXT"]["VALUE"]?></p></div></a>
					</div>
				</div>
			</div>
<style>
.section_<?=$i?>_content {
  -webkit-animation: text<?if ($i>1) echo '_'.$i;?> <?=$zoomspeed?>s infinite;
          animation: text<?if ($i>1) echo '_'.$i;?> <?=$zoomspeed?>s infinite;
  position: absolute; }
@media screen and (max-width: 900px) and (max-height: 435px) {
		<?if ($i < 2) {?>
  .section_<?=$i?>_content {
    -webkit-animation: none;
            animation: none; }
			<?} else {?>
  .section_<?=$i?>_content {
    display: none; }
			<?}?>
}
</style>
	<?$i++;?>
<?endforeach;?>
