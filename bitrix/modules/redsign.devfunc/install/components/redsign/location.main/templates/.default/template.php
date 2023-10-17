<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var CMain $APPLICATION
 * @var CBitrixComponentTemplate $this
 * @var RedsignLocationMain $component
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$jsParams = [
    'ajaxUrl' => $componentPath . '/ajax.php',
    'siteId' => SITE_ID
];

$blockId = "topline-location-" . $this->randString();
?>
<span class="b-topline-location" id="topline-location">
    <svg class="icon-svg">
        <path d="M17.27 6.73l-4.24 10.13-1.32-3.42-.32-.83-.82-.32-3.43-1.33 10.13-4.23M21 3L3 10.53v.98l6.84 2.65L12.48 21h.98L21 3z">
    </svg>
    <?=Loc::getMessage('RS_RLM_DEFAULT_YOUR_CITY');?>:
    <a class="b-topline-location__link" id="<?=$blockId?>" href="<?=(isset($arParams['POPUP_URL']) ? $arParams['POPUP_URL'] : SITE_DIR . 'mycity/')?>" title="<?=Loc::getMessage('RS_RLM_DEFAULT_SELECT');?>" data-type="ajax">
        <?php
        $frame = $this->createFrame($blockId, false)->begin();
            $frame->setBrowserStorage(true);
            echo (!empty($arResult['NAME']) ? $arResult['NAME'] : Loc::getMessage('RS_RLM_DEFAULT_NOT_SELECT'));
        $frame->beginStub();
            echo Loc::getMessage('RS_RLM_DEFAULT_NOT_SELECT');
        $frame->end();
        ?>
    </a>
</span>
<script>RS.Location = new RSLocation(<?=CUtil::PhpToJSObject($arResult)?>, <?=CUtil::PhpToJSObject($jsParams)?>);</script>
