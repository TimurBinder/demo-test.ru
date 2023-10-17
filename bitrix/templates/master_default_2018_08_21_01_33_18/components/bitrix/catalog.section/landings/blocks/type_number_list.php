<?php
if (count($arProp["PROP_NUMBER_LIST_1"]["LINK_ELEMENT_VALUE"]) > 0):
    $iblock = current($arProp["PROP_NUMBER_LIST_1"]["LINK_ELEMENT_VALUE"]);
    $havePic = false;
    foreach ($arProp["PROP_NUMBER_LIST_1"]["LINK_ELEMENT_VALUE"] as &$prop):
        $ids[] = $prop["ID"];
        if ($prop["PREVIEW_PICTURE"] != "") {
            $havePic = true;
            $prop["PREVIEW_PICTURE"] = CFile::GetPath($prop["PREVIEW_PICTURE"]);
        }                
    endforeach;

    $elems = array();

    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT","PREVIEW_TEXT_TYPE");
    $arFilter = Array("IBLOCK_ID"=>IntVal($iblock["IBLOCK_ID"]), "ID"=>$ids, "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
    while ($ob = $res->GetNextElement()) { 
        $arFields = $ob->GetFields();
        $elems[$arFields['ID']] = $arFields; 
    }
endif;

?>
<div class="container">
    <div class="landing__title">
        <?php if ($name != ""):?>
            <h3><?=$name?></h3>
        <?php endif;?>
    </div>
    <?php if (count($arProp["PROP_NUMBER_LIST_1"]["LINK_ELEMENT_VALUE"]) > 0):?>
        <div class="landing__nlist">
            <?php if ($havePic): ?>
                <div class="landing__nlist__pics col-sm-5 col-xs-12">
                    <?$x = 1;?>
                    <?php foreach ($arProp["PROP_NUMBER_LIST_1"]["LINK_ELEMENT_VALUE"] as $item):?>
                        <?php if ($item["PREVIEW_PICTURE"] != ""):?>
                            <div class="landing__nlist__pic <?=($x==1?'landing__nlist__cur' : '');?> landing__nlist__<?=$x?>">
                                <img src="<?=$item["PREVIEW_PICTURE"]?>">
                            </div>
                        <?php endif;?>
                        <?$x++;?>
                    <?php endforeach;?>
                </div>
            <?php endif;?>
            <div class="landing__nlist__numbers col-sm-2 col-xs-12">
                <?php for ($i = 1; $i < count($arProp["PROP_NUMBER_LIST_1"]["LINK_ELEMENT_VALUE"])+1; $i++):?>
                    <div class="js-landing__nlist__number landing__nlist__number <?=($i==1?'landing__nlist__cur' : '');?> landing__nlist__<?=$i?>" data-num="<?=$i?>">
                        <span><?=$i?></span>
                    </div>
                    <?php if ($i < count($arProp["PROP_NUMBER_LIST_1"]["LINK_ELEMENT_VALUE"])):?>
                        <div class="landing__nlist__line"></div>
                    <?php endif;?>
                <?php endfor;?>
            </div>
            <div class="landing__nlist__blocks col-sm-5 col-xs-12">
                <?$x = 1;?>
                <?php foreach ($arProp["PROP_NUMBER_LIST_1"]["LINK_ELEMENT_VALUE"] as $item):?>
                    <div class="landing__nlist__block <?=($x==1?'landing__nlist__cur' : '');?> landing__nlist__<?=$x?>">
                        <div class="landing__nlist__name">
                            <?=$item["NAME"]?>
                        </div>
                        <div class="landing__nlist__description">
                            <?=$elems[$item["ID"]]["PREVIEW_TEXT"]?>
                        </div>
                    </div>
                    <?$x++;?>
                <?php endforeach;?>
            </div>
        </div>
    <?php elseif(count($arProp["PROP_NUMBER_LIST_2"]["VALUE"]) > 0):?>
        <?$x=1;?>
        <div class="landing__nlist__items-outer">
            <div class="landing__nlist__items">
                <?php foreach ($arProp["PROP_NUMBER_LIST_2"]["VALUE"] as $item):?>
                    <div class="landing__nlist__item">
                        <div class="landing__nlist__number <?=($x == 1 ? 'landing__nlist__cur' : '')?>">
                            <span>
                                <?=$x?>
                            </span>
                        </div>
                        <div class="landing__nlist__name <?=($x != 1 ? 'landing__nlist__name-small' : '')?>">
                            <?=$item;?>
                        </div>
                    </div>
                    <?php if ($x < count($arProp["PROP_NUMBER_LIST_2"]["VALUE"])):?>
                        <div class="landing__nlist__line"></div>
                    <?php endif;?>
                    <?$x++;?>
                <?php endforeach;?>
            </div>
        </div>
    <?php endif;?>    
</div>
