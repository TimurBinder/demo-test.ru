<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Localization\Loc;

$template = $this->GetTemplate();
$path = $template->GetFolder();

$arMessages = Loc::loadLanguageFile(__DIR__.'/user_consent.php');
?>
<script>
BX.message(<?=CUtil::PhpToJSObject($arMessages);?>);
</script>
<script>
if (!BX.UserConsent) {
    BX.loadScript('<?=$path?>/user_consent.js');
}
</script>
