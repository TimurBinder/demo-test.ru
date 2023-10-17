<?php
use Bitrix\Main\Localization\Loc;
if (count($buttons) > 0):?>
    <div>
        <?php foreach ($buttons as $button):?>
            <?php if ($button['BUY'] == 'Y'):?>
                <span class="product-item-detail-actions js-buy" data-productid="<?=$arResult['ELEMENT']['ID']?>">
                    <a class="btn btn-lg <?=$button['CLASS']?> product-item-detail-buy-button js-buy__add2cart" title="<?=$button['TEXT']?>">
                        <span><?=$button['TEXT']?></span>
                    </a>
                    <a href="<?=$arParams['PATH_TO_CART']?>" class="btn btn-lg <?=$button['CLASS']?> js-buy__incart"><?=Loc::getMessage('RS.MASTER.BCE_MASTER.GOTO_CART')?></a>
                </span>
            <?php elseif ($button['ANCHOR'] == 'Y'):?>
                <a class="btn btn-lg <?=$button['CLASS']?> product-item-detail-buy-button"
                    href="<?=$button['ACTION']?>"                            
                    title="<?=$button['TEXT']?>"
                >
                    <span><?=$button['TEXT']?></span>
                </a>
            <?php elseif ($button['FORM'] == 'Y'):?>
                <?php if (strpos($button['ACTION'], "#ELEMENT_ID#") !== false) {
                    $button['ACTION'] = str_replace("#ELEMENT_ID#", $arResult['ELEMENT']['ID'], $button['ACTION']);
                } ?>
                <a class="btn btn-lg <?=$button['CLASS']?> product-item-detail-buy-button"
                    data-type="ajax"
                    href="<?=$button['ACTION']?>"                            
                    title="<?=$button['TEXT']?>"
                >
                    <span><?=$button['TEXT']?></span>
                </a>
            <?php endif;?>
        <?php endforeach;?>
    </div>
<?php endif;?>
