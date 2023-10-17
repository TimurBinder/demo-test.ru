<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\SystemException;
use Bitrix\Main\Web\Json;
use Redsign\Tuning;

define('NO_AGENT_CHECK', true);

if (isset($_REQUEST['site_id']) && !empty($_REQUEST['site_id'])) {
    if (!is_string($_REQUEST['site_id']))
        die();

    if (preg_match('/^[a-z0-9_]{2}$/i', $_REQUEST['site_id']) === 1)
        define('SITE_ID', htmlspecialchars($_REQUEST['site_id']));
}

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

/** @var \Bitrix\Main\HttpRequest */
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$response = [
    'STATUS' => 'OK',
    'TEXT' => '',
];

try {
    if ($request->getPost('rstuning_ajax') == 'Y') {
        if (!Loader::includeModule('redsign.tuning'))
            throw new SystemException('Bad Can not include modules redsign.tuning');

        $tuning = Tuning\TuningCore::getInstance();

        if (!$tuning)
            throw new SystemException('Tuning options error');

        $instanceCssFileManager = $tuning->getInstanceCssFileManager();
        $instanceMacrosManager = $tuning->getInstanceMacrosManager();

        $optionList = $tuning->getOptions();

        if (empty($optionList))
            throw new SystemException('Options list is empty');

        $fromSession = Option::get('redsign.tuning', 'fromSession', '', SITE_ID);

        if ($request->getPost('rstuning_action') == 'restoredefaultsettings') {
            $tuning->restoreDefaultOptions();
            $response['TEXT'] = 'Settings restored';
        } else {
            $tuning->saveOptions();
            $macrosList = $instanceMacrosManager->getReadyMacros();
            $response['MACROS_LIST'] = $macrosList;

            if ($fromSession != 'Y') {
                $instanceCssFileManager->generateCss($macrosList);
                $response['TEXT'] = 'Settings saved. Css file regenerated';
            } else {
                $response['TEXT'] = 'Settings saved';
            }
        }
    } else {
        throw new SystemException('Bad request');
    }
} catch (\Throwable $ex) {
    $response['STATUS'] = 'ERROR';
    $response['TEXT'] = $ex->getMessage();
}

\CMain::FinalActions(Json::encode($response));
