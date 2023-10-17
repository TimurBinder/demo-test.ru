<div class="container <?=($arProp["PROP_FORM_4"]["VALUE"] == 'Y' ? 'landing__shadow-form' : '')?>">
    <div class="landing__title">
        <?php if ($name != ""):?>
            <h3><?=$name?></h3>
        <?php endif;?>
    </div>
    <div class="landing__center">
        <?php include($_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/template/catalog/landing_form.php"); ?>
    </div>
</div>
