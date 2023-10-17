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
	<div class="section_<?=$i?>_img" id="image"> </div>
	<?$i++;?>
<?endforeach;?>

	<div class="mob_slider_wrap">
		<div class="mobile_slider">
<?foreach($arResult["ITEMS"] as $arItem):?>
			<div class="mob_slider_item">
				<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>">
			</div>
<?endforeach;?>
		</div>
	</div>

	<div class="img_on_mob_slider">
		<img src="/upload/img/bg.png" alt="1">
	</div>

<script>
	/*
$('.slider').slick({
	dots: false,
	infinite: true,
	slidesToShow: 1,
	slidesToScroll: 0.64,
	responsive: [
		{
			breakpoint: 421,
			settings: {
				slidesToScroll: 0.74
			}
		},
		{
			breakpoint: 341,
			settings: {
				slidesToScroll: .83
			}
		}
	]
});

$(function () {
	var slickOpts = {
		dots: true,
		infinite: true
	};
	// Init the slick    
	$('.mobile_slider').slick(slickOpts);
});
*/
</script>

<style>
<?
$i = 1;
foreach($arResult["ITEMS"] as $arItem):
	$zoomspeed = $arItem["PROPERTIES"]["ZOOM_SPEED"]["VALUE"];
	$zoomprc = $arItem["PROPERTIES"]["ZOOM_PRC"]["VALUE"]/100+1;
?>

.section_<?=$i?>_img {
	-webkit-animation: img_<?=$i?> <?=$zoomspeed?>s infinite;
	animation: img_<?=$i?> <?=$zoomspeed?>s infinite;
	background: url("<?=$arItem['PREVIEW_PICTURE']['SRC']?>") no-repeat center center/cover;
	position: absolute;
	z-index: -1100;
	height: 100vh;
	width: 100vw;
		<?if ($i < 2) {?>
	overflow: visible;
			<?} else {?>
	overflow: hidden;
			<?}?>
}
@media screen and (max-width: 1000px) and (min-width: 640px) and (max-height: 599px) {
		.section_<?=$i?>_img {display: none;}
}
@media screen and (max-width: 640px) {
	.section_<?=$i?>_img {display: none;}
}
@media screen and (max-width: 900px) and (max-height: 435px) {
<?if ($i < 2) {?>
	.section_<?=$i?>_img img {
    -webkit-animation: none;
            animation: none; }
<?} else {?>
  .section_<?=$i?>_img img:last-of-type {
    -webkit-animation: none;
            animation: none;
    display: none; }
<?}?>
}

<?
	if ($i<2) {

$str = "
	0% {-webkit-transform: scale(1, 1); transform: scale(1, 1);}
	47% {opacity: 1;}
	50% {-webkit-transform: scale(".$zoomprc.", ".$zoomprc."); transform: scale(".$zoomprc.", ".$zoomprc."); opacity: 0; display: none;}
	98% {-webkit-transform: scale(1, 1); transform: scale(1, 1); opacity: 0;}
	100% {-webkit-transform: scale(1, 1); transform: scale(1, 1); opacity: 1; }
";
?>
@-webkit-keyframes img_<?=$i?> {<?=$str?>}
@keyframes img_<?=$i?> {<?=$str?>}

<?
	} else {

$str = "
	0% {opacity: 0;}
	47% {opacity: 0; -webkit-transform: scale(1, 1); transform: scale(1, 1);}
	50% {opacity: 1;}
	98% {opacity: 1;}
		100% {-webkit-transform: scale(".$zoomprc.", ".$zoomprc."); transform: scale(".$zoomprc.", ".$zoomprc."); opacity: 0.4;}
";
?>
@-webkit-keyframes img_<?=$i?> {<?=$str?>}
@keyframes img_<?=$i?> {<?=$str?>}

<?}?>


@media screen and (min-width: 1921px) {
  .section_<?=$i?>_img img {width: 100%;}
}
	<?$i++;?>
<?endforeach;?>
</style>