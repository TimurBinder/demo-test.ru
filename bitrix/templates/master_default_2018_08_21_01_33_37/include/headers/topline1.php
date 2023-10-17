<?php
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\Location;

use \Bitrix\Main\Config\Option;

$isShowLocation = ModuleManager::isModuleInstalled('sale') && Option::get('redsign.master', 'head_settings_topline_location') == 'Y';
$isShowShedule = Option::get('redsign.master', 'head_settings_topline_shedule') == 'Y';
$isShowRecall = Option::get('redsign.master', 'head_settings_topline_recall') == 'Y';
$isShowAuth = Option::get('redsign.master', 'head_settings_topline_auth') == 'Y';
$isShowFavorite = Option::get('redsign.master', 'head_settings_topline_favorite') == 'Y';
$isShowCompare = Option::get('redsign.master', 'head_settings_topline_compare') == 'Y';
$isShowCart = Option::get('redsign.master', 'head_settings_topline_cart') == 'Y';
?>

<div class="b-topline">
    <div class="b-topline__container">
        <div class="b-topline__left">
            <?php if ($isShowLocation): ?>
            <?php
            $APPLICATION->IncludeComponent(
                'rsmaster:location.main',
                '',
                array()
            );
            ?>
            <?php endif; ?>

            <?php if ($isShowShedule): ?>
            <span class="b-topline-shedule"><?php $APPLICATION->IncludeFile(SITE_DIR.'include/header/topline_shedule.php', array(), array('NAME' => Loc::getMessage('SHEDULE_FILE_NAME'))); ?></span>
            <?php endif; ?>

            <?php if ($isShowRecall): ?>
            <a href="<?=SITE_DIR?>include/forms/recall/" class="b-topline-recall" data-type="ajax"><?=Loc::getMessage('RS.MASTER.RECALL');?></a>
            <?php endif; ?>
        </div>
        <div class="b-topline__right">
            <?php if ($isShowAuth): ?>
            <?$APPLICATION->IncludeComponent(
                "bitrix:system.auth.form",
                "topline1",
                array(
                    "AUTH_URL" => "/auth/",
                    "PROFILE_URL" => "/personal/",
                    "FORGOT_PASSWORD_URL" => "",
                    "SHOW_ERRORS" => "N"
                ),
                false
            );?>
            <?php endif; ?>

            <?php
            
            if ($isShowCompare) {
                $APPLICATION->IncludeFile(
                    SITE_DIR."include/footer/compare.php",
                    Array(),
                    Array("MODE"=>"html")
                );
            }
            
            if ($isShowFavorite) {
                $APPLICATION->IncludeFile(
                    SITE_DIR."include/header/favorite.php",
                    Array(),
                    Array("MODE"=>"html")
                );
            }

            if ($isShowCart) {
                $APPLICATION->ShowViewContent('rs_topline_cart'); // <-- bitrix:sale.basket.basket.line
            }
            ?>
        </div>
    </div>
</div>
