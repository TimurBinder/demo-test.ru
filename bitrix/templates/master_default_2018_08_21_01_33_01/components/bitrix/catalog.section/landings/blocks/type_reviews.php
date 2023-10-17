<?php $arProp = $arItem["DISPLAY_PROPERTIES"];?>
<div class="container">
    <div class="landing__title">
        <?php if ($name != ""):?>
            <h3><?=$name?></h3>
        <?php endif;?>
    </div>
    <div>
        <?php include($_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/template/catalog/landing_reviews.php"); ?>
    </div>
</div>
