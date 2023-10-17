<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$mainId = $this->GetEditAreaId('favorite');
?>
<script>
BX.message({
"RS_FAVORITE_ADD":"<?=getMessageJs('RS.MASTER.RFL_FILTER.FAVORITE_ADD')?>",
"RS_FAVORITE_DEL":"<?=getMessageJs('RS.MASTER.RFL_FILTER.FAVORITE_DEL')?>"
});
</script>
<a href="<?=$arParams['FAVORITE_URL']?>" class="b-topline-favorite" id="<?=$mainId?>">
    <?php
    $frame = $this->createFrame($mainId, false)->begin();
    // $frame->setBrowserStorage(true);
        //echo $arResult['COUNT'];
        $arIDs = array();
        if (is_array($arResult['ITEMS']) && 0 < count($arResult['ITEMS'])) {
            foreach ($arResult['ITEMS'] as $arItem) {
                $arIDs[] = $arItem['ELEMENT_ID'];
            }
        }
    ?>
        <svg class="icon-svg"><use xlink:href="#svg-favorite-main"></use></svg><span class="b-topline-favorite__count js-favorite-count"><?=$arResult['COUNT']?></span>
        <script>RS.Favorite.init(<?=\Bitrix\Main\Web\Json::encode($arIDs)?>)</script>
    <?php $frame->beginStub(); ?>
        <svg class="icon-svg"><svg class="icon-svg"><use xlink:href="#svg-favorite-main"></use></svg><span class="b-topline-favorite__count js-favorite-count"><?=$arResult['COUNT']?></span>
    <?php $frame->end(); ?>
</a>
