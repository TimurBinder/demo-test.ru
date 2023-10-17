<div class="container">
    <div class="landing__title landing__title__padding">
        <?php if ($name != ""):?>
            <h3><?=$name?></h3>
        <?php endif;?>
    </div>
    <?php if (is_array($arResult["ELEMENT"]["PROP_GROUP"])):?>        
        <div class="col-sm-6 col-xs-12">
            <dl class="product-item-detail-properties">
                <?php $countPropTable = round(count($arResult["ELEMENT"]["PROP_GROUP"])/2); ?>
                <?php foreach ($arResult["ELEMENT"]["PROP_GROUP"] as $keyProp=>$prop):?>
                    <?php
                        if ($prop["VALUE"] == "")
                            continue;
                    ?>
                    <?php if ($countPropTable == $keyProp):?>
                        </dl>
                        </div>
                        <div class="col-sm-6 col-xs-12">
                        <dl class="product-item-detail-properties">
                    <?php endif; ?>

                    <dt><?=$prop["NAME"]?></dt>
                    <dd><?=$prop["VALUE"]?></dd>
                <?php endforeach;?>
            </dl>
        </div>        
    <?php endif;?>
</div>
