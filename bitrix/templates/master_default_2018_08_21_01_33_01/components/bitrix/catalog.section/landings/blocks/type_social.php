<?php $arProp = $arItem["DISPLAY_PROPERTIES"];?>

<div class="ya-share2 landing__social"
    <?php if (is_array($arProp['PROP_SOC_SERV']['VALUE_XML_ID'])): ?>
        data-services="<?=implode(',', $arProp['PROP_SOC_SERV']['VALUE_XML_ID']);?>"        
    <?php else: ?>
        data-services="<?=implode(',', $arParams['SOCIAL_SERVICES']);?>"
    <?php endif;?>
    data-lang="<?=LANGUAGE_ID?>"
></div>
