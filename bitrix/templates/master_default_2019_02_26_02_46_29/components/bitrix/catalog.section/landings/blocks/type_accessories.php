<?php $arProp = $arItem["DISPLAY_PROPERTIES"];?>
<div class="container">
    <div class="landing__title landing__title__padding">
        <?php if ($name != ""):?>
            <h3><?=$name?></h3>
        <?php endif;?>
    </div>
    <?php include($_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/template/catalog/landing_section.php"); ?>    
</div>
