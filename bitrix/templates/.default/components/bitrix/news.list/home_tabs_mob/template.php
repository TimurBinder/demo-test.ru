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
<div class="section_2_title">
            <div class="container">
            <div class="title_2_h1"><h1>ЖК Роза Town</h1></div>
            <div class="title_2_p"><p>Квартиры от 33 кв.м. <br>
с возможностью рассрочки 
и в ипотеку</p></div>
        <a href="/catalog/" style="display: block;" class="title_btn btn_2"><p>Выбрать квартиру</p></a>
       
        </div>
	   </div>
	   <div class="slider">	   
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
<?//print_r($arItem)?>
<?/*<p class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">*/?>
		

				<div class="slider_item">
              <div class="slider_wrap">
               <div class="slider-img">
                   <img class="cont_<?=$i?>" src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="1">
               </div>
               <div class="slider_title">
                   <h3><?=$arItem["NAME"]?></h3>
               </div>
               <div class="slider_text slid_txt_<?=$i?>">
			   <?//=$arItem["PROPERTIES"]['MOB_TEXT']['~VALUE']['TEXT']?>
			   </div>
			   <? if($i == 4) { ?>
				   <div class="slider_icons">
				   <div><img src="img/sberbank-big.png" alt="1"></div>
					<div> <img src="img/vtb-bank.png" alt="1"></div>
				   
				</div>
			   <? } ?>
                <a href="<?=$arItem["PROPERTIES"]["TAB_URL"]["VALUE"]?>" class="slider_button">Узнать больше <span><img src="img/right-arrow-2.png" alt=""></span></a>
           </div>
           </div>
<?
$first = '';
$i++;
?>
<?endforeach;?>
</div>
