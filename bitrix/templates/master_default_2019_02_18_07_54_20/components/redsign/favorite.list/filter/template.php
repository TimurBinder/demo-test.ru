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
<span id="<?=$mainId?>">
    <?php
    $frame = $this->createFrame($mainId, false)->begin('');
        //echo $arResult['COUNT'];
        $arIDs = array();
        if (is_array($arResult['ITEMS']) && 0 < count($arResult['ITEMS'])) {
            foreach ($arResult['ITEMS'] as $arItem) {
                $arIDs[] = $arItem['ELEMENT_ID'];
            }
        }
    ?>
    <script>RS.Favorite.init(<?=\Bitrix\Main\Web\Json::encode($arIDs)?>)</script>
<?php $frame->end(); ?>
</span>
