<?php

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Application;

defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'redsign.lightbasket');

if (!$USER->IsAdmin()) {
    $APPLICATION->authForm('Nope');
}

Loader::includeModule(ADMIN_MODULE_NAME);

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

Loc::loadMessages(__FILE__);
Loc::loadMessages($context->getServer()->getDocumentRoot().'/bitrix/modules/main/options.php');

if ((!empty($save) || !empty($restore)) && $request->isPost() && check_bitrix_sessid()) {
    if (!empty($restore)) {
        Option::delete(ADMIN_MODULE_NAME);
        CAdminMessage::showMessage(array(
            'MESSAGE' => Loc::getMessage('RS_LIGHTBASKET_OPTIONS_RESTORED'),
            'TYPE' => 'OK',
        ));
    } else {
        $sBasketProvider = $request->getPost('BASKET_PROVIDER');
        Option::set(ADMIN_MODULE_NAME, 'basket_provider', $sBasketProvider);
        $sPropertyCodePrice = $request->getPost('PROPERTY_PRICE');
        Option::set(ADMIN_MODULE_NAME, 'property_code_price', $sPropertyCodePrice);
        $sPropertyCodeDiscount = $request->getPost('PROPERTY_DISCOUNT');
        Option::set(ADMIN_MODULE_NAME, 'property_code_discount', $sPropertyCodeDiscount);
        $sPropertyCodeCurrency = $request->getPost('PROPERTY_CURRENCY');
        Option::set(ADMIN_MODULE_NAME, 'property_code_currency', $sPropertyCodeCurrency);
    }
}

$sBasketProvider = Option::get(ADMIN_MODULE_NAME, 'basket_provider');
$sPropertyCodePrice = Option::get(ADMIN_MODULE_NAME, 'property_code_price');
$sPropertyCodeDiscount = Option::get(ADMIN_MODULE_NAME, 'property_code_discount');
$sPropertyCodeCurrency = Option::get(ADMIN_MODULE_NAME, 'property_code_currency');

$tabControl = new CAdminTabControl('tabControl', array(
    array(
        'DIV' => 'edit1',
        'TAB' => Loc::getMessage('MAIN_TAB_SET'),
        'TITLE' => Loc::getMessage('MAIN_TAB_TITLE_SET'),
    ),
));
$tabControl->begin();
?>
<form method="post" action="<?=sprintf('%s?mid=%s&lang=%s', $request->getRequestedPage(), urlencode($mid), LANGUAGE_ID)?>" class="rs-telegrambot-form">
<?php echo bitrix_sessid_post(); ?>
<?php $tabControl->beginNextTab(); ?>
    <tbody>
        <tr class="heading">
            <td colspan="2"><b><?=Loc::getMessage('RS_LIGHTBASKET_OPTIONS_SYSTEM_HEADING'); ?></b></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l"><?=Loc::getMessage('RS_LIGHTBASKET_OPTIONS_SYSTEM_PROVIDER')?></td>
            <td class="adm-detail-content-cell-r">
                <select name="BASKET_PROVIDER">
                    <option value="session"<?php if ($sBasketProvider == 'session'): ?>selected<?php endif; ?>><?=Loc::getMessage('RS_LIGHTBASKET_OPTIONS_SYSTEM_PROVIDER_SESSION'); ?></option>
                    <option value="database" <?php if ($sBasketProvider == 'database'): ?>selected<?php endif; ?>><?=Loc::getMessage('RS_LIGHTBASKET_OPTIONS_SYSTEM_PROVIDER_DATABASE')?></option>
                </select>
            </td>
        </tr>
        <tr class="heading">
            <td colspan="2"><b><?=Loc::getMessage('RS_LIGHTBASKET_OPTIONS_PROPERTIES_HEADING'); ?></b></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l"><?=Loc::getMessage('RS_LIGHTBASKET_OPTIONS_PROPERTIES_PRICE'); ?></td>
            <td class="adm-detail-content-cell-r">
                <input type="text" size="30" maxlength="255" value="<?=$sPropertyCodePrice?>" name="PROPERTY_PRICE">
            </td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l"><?=Loc::getMessage('RS_LIGHTBASKET_OPTIONS_PROPERTIES_DISCOUNT'); ?></td>
            <td class="adm-detail-content-cell-r">
                <input type="text" size="30" maxlength="255" value="<?=$sPropertyCodeDiscount?>" name="PROPERTY_DISCOUNT">
            </td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l"><?=Loc::getMessage('RS_LIGHTBASKET_OPTIONS_PROPERTIES_CURRENCY'); ?></td>
            <td class="adm-detail-content-cell-r">
                <input type="text" size="30" maxlength="255" value="<?=$sPropertyCodeCurrency?>" name="PROPERTY_CURRENCY">
            </td>
        </tr>
    </tbody>
<?php $tabControl->buttons(); ?>
    <input type="submit" name="save" value="<?=Loc::getMessage('MAIN_SAVE') ?>" title="<?=Loc::getMessage('MAIN_OPT_SAVE_TITLE') ?>"class="adm-btn-save">
    <input type="submit" name="restore" title="<?=Loc::getMessage('MAIN_HINT_RESTORE_DEFAULTS') ?>" onclick="return confirm('<?= addslashes(Loc::getMessage('MAIN_HINT_RESTORE_DEFAULTS_WARNING')) ?>')" value="<?=Loc::getMessage('MAIN_RESTORE_DEFAULTS') ?>">
<?php $tabControl->end(); ?>
</form>
