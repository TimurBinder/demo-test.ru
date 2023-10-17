<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<ul class="nav_list">

<?
$i=1;
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;
?>
	<li class="nav_item<?echo ($i>1) ? '_'.$i : '';?>"><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
<?$i++;?>
<?endforeach?>

</ul>
<?endif?>
