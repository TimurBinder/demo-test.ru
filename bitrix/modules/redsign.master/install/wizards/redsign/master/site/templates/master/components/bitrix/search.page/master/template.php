<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;
?>
<div class="l-search-page">
    <form class="b-search-page-form" action="" method="get">
        <div class="b-search-page-form__col-input">
        <?php
        if($arParams["USE_SUGGEST"] === "Y"):
            if(strlen($arResult["REQUEST"]["~QUERY"]) && is_object($arResult["NAV_RESULT"])) {
                $arResult["FILTER_MD5"] = $arResult["NAV_RESULT"]->GetFilterMD5();
                $obSearchSuggest = new CSearchSuggest($arResult["FILTER_MD5"], $arResult["REQUEST"]["~QUERY"]);
                $obSearchSuggest->SetResultCount($arResult["NAV_RESULT"]->NavRecordCount);
            }
        ?>
            <?$APPLICATION->IncludeComponent(
              "bitrix:search.suggest.input",
              "search_page",
              array(
                "NAME" => "q",
                "VALUE" => $arResult["REQUEST"]["~QUERY"],
                "INPUT_SIZE" => 40,
                "DROPDOWN_SIZE" => 10,
                "FILTER_MD5" => $arResult["FILTER_MD5"],
              ),
              $component, array("HIDE_ICONS" => "Y")
            );?>
        <?php else: ?>
            <input class="form-control" type="text" name="q" value="<?=$arResult["REQUEST"]["QUERY"]?>" size="40">
        <?php endif; ?>
        </div>
        <?php if($arParams["SHOW_WHERE"]): ?>
        <div class="b-search-page-form__col-where">
            <select class="form-control" name="where">
                <option value=""><?=Loc::getMessage("SEARCH_ALL")?></option>
                <?php foreach($arResult["DROPDOWN"] as $key=>$value): ?>
                    <option value="<?=$key?>"<?php if($arResult["REQUEST"]["WHERE"]==$key) echo " selected"?>><?=$value?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        <div class="b-search-page-form__btn-where">
            <input type="submit" class="btn btn-primary" value="<?=Loc::getMessage("SEARCH_GO")?>">
        </div>
    </form>

    <?php if(isset($arResult["REQUEST"]["ORIGINAL_QUERY"])): ?>
        <div class="l-search-page__language-guess"><?=Loc::getMessage("CT_BSP_KEYBOARD_WARNING", array("#query#"=>'<a href="'.$arResult["ORIGINAL_QUERY_URL"].'">'.$arResult["REQUEST"]["ORIGINAL_QUERY"].'</a>'))?></div>
    <?php endif; ?>

    <?php if($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false || $arParams['ONLY_FORM'] == 'Y'): ?>
    <?php elseif ($arResult["ERROR_CODE"] != 0): ?>
    <div class="l-search-page__error">
        <b><?=Loc::getMessage('SEARCH_ERROR');?></b>
        <p><?=$arResult["ERROR_TEXT"]?></p>
        <p><?=Loc::getMessage('SEARCH_CORRECT_AND_CONTINUE');?></p>
        <p><?=Loc::getMessage("SEARCH_SINTAX")?><br /><b><?=Loc::getMessage("SEARCH_LOGIC")?></b></p>
        <table class="table table-stripped">
        		<tr>
          			<td><?=Loc::getMessage("SEARCH_OPERATOR")?></td><td><?=Loc::getMessage("SEARCH_SYNONIM")?></td>
          			<td><?=Loc::getMessage("SEARCH_DESCRIPTION")?></td>
        		</tr>
      		<tr>
        			<td ><?=Loc::getMessage("SEARCH_AND")?></td><td>and, &amp;, +</td>
        			<td><?=Loc::getMessage("SEARCH_AND_ALT")?></td>
      		</tr>
      		<tr>
        			<td><?=Loc::getMessage("SEARCH_OR")?></td><td>or, |</td>
        			<td><?=Loc::getMessage("SEARCH_OR_ALT")?></td>
      		</tr>
      		<tr>
        			<td><?=Loc::getMessage("SEARCH_NOT")?></td><td>not, ~</td>
        			<td><?=Loc::getMessage("SEARCH_NOT_ALT")?></td>
      		</tr>
      		<tr>
        			<td>( )</td>
        			<td>&nbsp;</td>
        			<td><?=Loc::getMessage("SEARCH_BRACKETS_ALT")?></td>
      		</tr>
      	</table>
    </div>
    <?php elseif (isset($arResult['SEARCH_EXT'])): ?>
      <?php if($arParams["DISPLAY_TOP_PAGER"] != "N"):?>
      <div class="b-search-page-pager"><?=$arResult["NAV_STRING"]?></div>
      <?php endif; ?>
        <?php foreach ($arResult['SEARCH_EXT']['IBLOCKS'] as $arIblock): ?>
        <section class="b-search-page-block">
            <h2 class="b-search-page-block__title"><?=$arIblock['NAME']?></h2>
            <div class="b-search-page-block__result">
                <?php if ($arIblock['ID'] == $arParams['CATALOG_IBLOCK_ID']): ?>
                    <?php include 'catalog_items.php'; ?>
                <?php elseif(isset($arIblock['ITEMS'])):   ?>
                    <?php foreach ($arIblock['ITEMS'] as $arItem): ?>
                    <div class="b-search-page-item">
                        <a href="<?=$arItem["URL"]?>" class="b-search-page-item__title"><?=$arItem["TITLE_FORMATED"]?></a>
                        <div class="b-search-page-item__body"><?=$arItem['BODY_FORMATED']?></div>
                        <div class="b-search-page-item__updated">
                            <small><?=Loc::getMessage("SEARCH_MODIFIED")?> <?=$arItem["DATE_CHANGE"]?></small>
                        </div>
                        <?php if ($arItem["CHAIN_PATH"]): ?>
                            <div class="b-search-page-item__chain"><small><?=Loc::getMessage("SEARCH_PATH")?>&nbsp;<?=$arItem["CHAIN_PATH"]?></small></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        <?php endforeach; ?>

        <?php if (count($arResult['SEARCH_EXT']['OTHER']['ITEMS']) > 0):?>
        <section class="b-search-page-block">
            <h2 class="b-search-page-block__title"><?=Loc::getMessage('SEARCH_OTHER');?></h2>
            <div class="b-search-page-block__result">
                <?php foreach ($arResult['SEARCH_EXT']['OTHER']['ITEMS'] as $arItem): ?>
                <div class="b-search-page-item">
                    <a href="<?=$arItem["URL"]?>" class="b-search-page-item__title"><?=$arItem["TITLE_FORMATED"]?></a>
                    <div class="b-search-page-item__body"><?=$arItem['BODY_FORMATED']?></div>
                    <div class="b-search-page-item__updated">
                        <small><?=Loc::getMessage("SEARCH_MODIFIED")?> <?=$arItem["DATE_CHANGE"]?></small>
                    </div>
                    <?php if ($arItem["CHAIN_PATH"]): ?>
                        <div class="b-search-page-item__chain"><small><?=Loc::getMessage("SEARCH_PATH")?>&nbsp;<?=$arItem["CHAIN_PATH"]?></small></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if($arParams["DISPLAY_BOTTOM_PAGER"] != "N"):?>
        <div class="b-search-page-pager"><?=$arResult["NAV_STRING"]?></div>
        <?php endif; ?>
        <?php if($arResult["REQUEST"]["HOW"]=="d"):?>
      		  <a href="<?=$arResult["URL"]?>&amp;how=r<?=$arResult["REQUEST"]["FROM"]? '&amp;from='.$arResult["REQUEST"]["FROM"]: ''?><?echo $arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: ''?>"><?=Loc::getMessage("SEARCH_SORT_BY_RANK")?></a>&nbsp;|&nbsp;<b><?=Loc::getMessage("SEARCH_SORTED_BY_DATE")?></b>
      	<?php else:?>
      		  <b><?=Loc::getMessage("SEARCH_SORTED_BY_RANK")?></b>&nbsp;|&nbsp;<a href="<?=$arResult["URL"]?>&amp;how=d<?echo $arResult["REQUEST"]["FROM"]? '&amp;from='.$arResult["REQUEST"]["FROM"]: ''?><?=$arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: ''?>"><?=Loc::getMessage("SEARCH_SORT_BY_DATE")?></a>
      	<?php endif;?>

    <?php else: ?>
    <div class="l-search-page__error"><?=Loc::getMessage('SEARCH_NOTHING_TO_FOUND');?></div>
    <?php endif; ?>
</div>
