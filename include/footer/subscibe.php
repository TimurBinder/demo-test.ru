<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>
<?php if(IsModuleInstalled('subscribe')):?>
<?$APPLICATION->IncludeComponent(
    "bitrix:subscribe.form",
    "footer",
    array(
        "USE_PERSONALIZATION" => "Y",
        "PAGE" => "/company/subscribe/",
        "SHOW_HIDDEN" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "RS_TITLE_TEXT" => "Подписка",
        "RS_NOTE_TEXT" => "Подпишитесь на свежие новости и акции компании"
    )
);?>
<?php endif;?>
