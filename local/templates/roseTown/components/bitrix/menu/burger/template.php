<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<nav class="mt-5 position-relative">
<?
foreach($arResult as $arItem):?>

	<?if ($arItem["DEPTH_LEVEL"] === 1):?>
		<a href="<?=$arItem['LINK']?>" class="row mt-2"><?=$arItem['TEXT']?></a>
	<?endif?>

<?endforeach?>
<div class="nav-active nav-active-1">
	<img src="<?=SITE_TEMPLATE_PATH?>/src/media/icons/Path 21.png">
</div>
</nav>
<?endif?>