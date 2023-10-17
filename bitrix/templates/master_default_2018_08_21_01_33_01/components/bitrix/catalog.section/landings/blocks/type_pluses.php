<?php $arProp = $arItem["DISPLAY_PROPERTIES"]; ?>

<div class="container">
    <div class="landing__title">
        <?php if ($name != ""):?>
            <h3><?=$name?></h3>
        <?php endif;?>
    </div>    
    <div class="row landing__center">
        <?php
        if ($arItem["PROPERTIES"]["PROP_PLUS_1"]["VALUE_XML_ID"] == "pluses_in_row"):
            $class = "";
            $count = count($arProp["PROP_PLUS_2"]["FILE_VALUE"]);
            switch ($count):

                case 1:
                    $class .= " col-sm-6 col-xs-12";
                    break;
                case 2:
                    $class .= " col-sm-6 col-xs-12";
                    break;
                case 3:
                    $class .= " col-sm-4 col-xs-12";
                    break;
                case 4:
                    $class .= " col-md-3 col-sm-6 col-xs-12";
                    break;
                case 5:
                    $class .= " col-md-1-5 col-sm-6 col-xs-12";
                    break;
                default:
                    $class .= " col-md-4 col-sm-6 col-xs-12";
                    break;

            endswitch;
            ?>

            
            <?php foreach ($arProp["PROP_PLUS_2"]["FILE_VALUE"] as $key=>$file):?>
                <div class="<?=$class?>">
                    <div class="landing_pluses__row animate">
                        <?$descr = $file["DESCRIPTION"] != "" ? $file["DESCRIPTION"] : $arProp["PROP_PLUS_3"]["VALUE"][$key];?>
                        <img class="landing_pluses__row__img" alt="<?=$descr?>" title="<?=$descr?>" src='<?=$file["SRC"]?>' />
                        <div class="landing_pluses__row__title">
                            <?=$arProp["PROP_PLUS_3"]["VALUE"][$key];?>
                        </div>
                        <div class="landing_pluses__row__descr">
                            <?php if (is_array($arProp["PROP_PLUS_4"]["DISPLAY_VALUE"])):?>
                                <?=$arProp["PROP_PLUS_4"]["DISPLAY_VALUE"][$key];?>
                            <?php elseif ($key == 0):?>
                                <?=$arProp["PROP_PLUS_4"]["DISPLAY_VALUE"]?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
                
        <?php else: ?>

            <?php
                $sidePlus = "";
                $firstPlus = "";
                $side = false;
                $bckgColor = false;

                if ($arItem["PROPERTIES"]["PROP_PLUS_5"]["VALUE"] != "")
                    $bckgColor = 'background-color:#'.$arItem["PROPERTIES"]["PROP_PLUS_5"]["VALUE"].';';

                switch ($arItem["PROPERTIES"]["PROP_PLUS_1"]["VALUE_XML_ID"]):
                    case "pluses_pic_left":
                        $sidePlus .= " col-sm-6 col-xs-12";
                        $firstPlus = "pic";
                        $side = true;
                        break;
                    case "pluses_pic_right":
                        $sidePlus .= " col-sm-6 col-xs-12";
                        $firstPlus = "text";
                        $side = true;
                        break;
                    case "pluses_pic_up":
                        $sidePlus .= " col-lg-12 col-md-12 col-sm-12 col-xs-12";
                        $firstPlus = "pic";
                        break;
                    case "pluses_pic_down":
                        $sidePlus .= " col-lg-12 col-md-12 col-sm-12 col-xs-12";
                        $firstPlus = "text";
                        break;
                    case "pluses_pic_none":
                        $sidePlus .= " col-sm-6 col-xs-12";
                        $firstPlus = "text-text";
                        break;                    
                    default:
                        $sidePlus .= " col-lg-12 col-md-12 col-sm-12 col-xs-12";
                        $firstPlus = "pic";
                        break;
                endswitch;
            ?>
            <div class="<?=($side ? 'landing_pluses__side' : '');?>">

                <?php if (!isset($arProp["PROP_PLUS_2"]["FILE_VALUE"]["SRC"])
                        && isset($arProp["PROP_PLUS_2"]["FILE_VALUE"])
                        && $firstPlus != "text-text"):?>
                    <?php foreach ($arProp["PROP_PLUS_2"]["FILE_VALUE"] as $key=>$file):?>
                        <div class="landing_pluses__many animate">
                            <?$descr = $file["DESCRIPTION"] != "" ? $file["DESCRIPTION"] : $arProp["PROP_PLUS_3"]["VALUE"][$key];?>
                            <?php if ($firstPlus == "text"):?>
                                <div class="<?=$sidePlus?>">
                                    <div class="landing_pluses__side_text" <?=($bckgColor ? 'style="'.$bckgColor.'"' : '');?>>
                                        <h3 class="landing_pluses__title">
                                            <?=$arProp["PROP_PLUS_3"]["VALUE"][$key];?>
                                        </h3>
                                        <div class="landing_pluses__description">
                                            <?php if (is_array($arProp["PROP_PLUS_4"]["DISPLAY_VALUE"])):?>
                                                <?=$arProp["PROP_PLUS_4"]["DISPLAY_VALUE"][$key];?>
                                            <?php else:?>
                                                <?=$arProp["PROP_PLUS_4"]["DISPLAY_VALUE"];?>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="landing_pluses__side_img <?=$sidePlus?>">
                                    <img src="<?=$file?>" alt="<?=$descr?>" title="<?=$descr?>" />
                                </div>
                            <?php else:?>
                                <div class="landing_pluses__side_img <?=$sidePlus?>">
                                    <img src="<?=$file?>" alt="<?=$descr?>" title="<?=$descr?>" />
                                </div>
                                <div class="<?=$sidePlus?>">
                                    <div class="landing_pluses__side_text" <?=($bckgColor ? 'style="'.$bckgColor.'"' : '');?>>
                                        <h3 class="landing_pluses__title">
                                            <?=$arProp["PROP_PLUS_3"]["VALUE"][$key];?>
                                        </h3>
                                        <div class="landing_pluses__description">
                                            <?php if (is_array($arProp["PROP_PLUS_4"]["DISPLAY_VALUE"])):?>
                                                <?=$arProp["PROP_PLUS_4"]["DISPLAY_VALUE"][$key];?>
                                            <?php else:?>
                                                <?=$arProp["PROP_PLUS_4"]["DISPLAY_VALUE"];?>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;?> 
                        </div>
                    <?php endforeach;?>
                <?php else: ?>
                    <?$descr = $arProp["PROP_PLUS_2"]["FILE_VALUE"]["DESCRIPTION"] != "" ? $arProp["PROP_PLUS_2"]["FILE_VALUE"]["DESCRIPTION"] : $arProp["PROP_PLUS_3"]["VALUE"]["0"];?>
                    <?php if ($firstPlus == "text"):?>

                        <div class="<?=$sidePlus?>">
                            <div class="<?=($side ? 'landing_pluses__side_text' : 'landing_pluses__full_text');?>" <?=($bckgColor ? 'style="'.$bckgColor.'"' : '');?>>
                                <h3 class="landing_pluses__title">
                                    <?=$arProp["PROP_PLUS_3"]["VALUE"]["0"];?>
                                </h3>
                                <div class="landing_pluses__description">
                                    <?php if (is_array($arProp["PROP_PLUS_4"]["DISPLAY_VALUE"])):?>
                                        <?=$arProp["PROP_PLUS_4"]["DISPLAY_VALUE"][0];?>
                                    <?php else:?>
                                        <?=$arProp["PROP_PLUS_4"]["DISPLAY_VALUE"];?>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                        <div class="<?=($side ? 'landing_pluses__side_img' : 'landing_pluses__full_img');?> <?=$sidePlus?> animate">
                            <img src="<?=$arProp["PROP_PLUS_2"]["FILE_VALUE"]["SRC"]?>" alt="<?=$descr?>" title="<?=$descr?>" />
                        </div>

                    <?php elseif ($firstPlus == "text-text"):?>

                        <?php if (is_array($arProp["PROP_PLUS_4"]["DISPLAY_VALUE"])):?>
                            <?php foreach ($arProp["PROP_PLUS_4"]["DISPLAY_VALUE"] as $val):?>
                                <div class="<?=$sidePlus?>">
                                    <div class="landing_pluses__side_text" <?=($bckgColor ? 'style="'.$bckgColor.'"' : '');?>>
                                        <div class="landing_pluses__description">
                                           <?=$val;?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        <?php endif;?>

                    <?php else:?>

                        <div class="<?=$sidePlus?> <?=($side ? 'landing_pluses__side_img' : 'landing_pluses__full_img');?> animate">
                            <img src="<?=$arProp["PROP_PLUS_2"]["FILE_VALUE"]["SRC"]?>" alt="<?=$descr?>" title="<?=$descr?>" />
                        </div>
                        <div class="<?=$sidePlus?>">
                            <div class="<?=($side ? 'landing_pluses__side_text' : 'landing_pluses__full_text');?>" <?=($bckgColor ? 'style="'.$bckgColor.'"' : '');?>>
                                <h3 class="landing_pluses__title">
                                    <?=$arProp["PROP_PLUS_3"]["VALUE"]["0"];?>
                                </h3>
                                <div class="landing_pluses__description">
                                    <?php if (is_array($arProp["PROP_PLUS_4"]["DISPLAY_VALUE"])):?>
                                        <?=$arProp["PROP_PLUS_4"]["DISPLAY_VALUE"][0];?>
                                    <?php else:?>
                                        <?=$arProp["PROP_PLUS_4"]["DISPLAY_VALUE"];?>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>

                    <?php endif;?> 
                <?php endif; ?>

            </div>
        <?php endif; ?>
    </div>
</div>
