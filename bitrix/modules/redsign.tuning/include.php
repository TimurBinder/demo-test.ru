<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Redsign\Tuning;

/**
 * @var CMain $APPLICATION
 * @var CDatabase $DB
 * @var string $DBType
 */


Loc::loadMessages(__FILE__);

$arClasses = [
    '\Redsign\Tuning\Interfaces\OptionCoreInterface' => 'lib/interfaces/option_core_interface.php',
    '\Redsign\Tuning\Interfaces\OptionManagerInterface' => 'lib/interfaces/option_manager_interface.php',
    '\Redsign\Tuning\TuningCore' => 'lib/tuning_core.php',
    '\Redsign\Tuning\TabCore' => 'lib/tab_core.php',
    '\Redsign\Tuning\OptionCore' => 'lib/option_core.php',
    '\Redsign\Tuning\TuningOption' => 'lib/tuning_option.php',
    '\Redsign\Tuning\OptionManager' => 'lib/option_manager.php',
    '\Redsign\Tuning\OptionManagerBitrix' => 'lib/option_manager_bitrix.php',
    '\Redsign\Tuning\OptionManagerSession' => 'lib/option_manager_session.php',
    '\Redsign\Tuning\CssFileManager' => 'lib/css_file_manager.php',
    '\Redsign\Tuning\MacrosManager' => 'lib/macros_manager.php',
    '\Redsign\Tuning\WidgetPainting' => 'lib/widget_painting.php',
];

Loader::registerAutoLoadClasses('redsign.tuning', $arClasses);

if (!function_exists('rsTuningIsHideTuning')) {
    function rsTuningIsHideTuning(): bool
    {
        /** @var \Bitrix\Main\HttpRequest */
        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        $userAgent = $request->getUserAgent() ?: '';

        if (strpos(strtolower($userAgent), 'lighthouse') !== false)
            return true;

        return false;
    }
}

$arJSCoreConfig = [
    'rs_tuning' => [
        'js' => getLocalPath('js/redsign/tuning/tuning.js'),
        'rel' => ['rs_core', 'redsign.tuning'],
    ],
];

foreach ($arJSCoreConfig as $ext => $arExt) {
    CJSCore::RegisterExt($ext, $arExt);
}

$arDefaultSettings = [
    0 => [
        'KEY' => 'fromSession',
        'DEFAULT' => '',
    ],
    1 => [
        'KEY' => 'fileOptions',
        'DEFAULT' => '',
    ],
    2 => [
        'KEY' => 'fileOptionsExt',
        'DEFAULT' => '',
    ],
    3 => [
        'KEY' => 'fileColorMacros',
        'DEFAULT' => '',
    ],
    4 => [
        'KEY' => 'fileColorCompiled',
        'DEFAULT' => '',
    ],
    5 => [
        'KEY' => 'dirOptionsExt',
        'DEFAULT' => '',
    ],
];

$fromSession = Option::get('redsign.tuning', $arDefaultSettings[0]['KEY'], '', SITE_ID);
$fileOptions = Option::get('redsign.tuning', $arDefaultSettings[1]['KEY'], '', SITE_ID);
$fileOptionsExt = Option::get('redsign.tuning', $arDefaultSettings[2]['KEY'], '', SITE_ID);
$fileColorMacros = Option::get('redsign.tuning', $arDefaultSettings[3]['KEY'], '', SITE_ID);
$fileColorCompiled = Option::get('redsign.tuning', $arDefaultSettings[4]['KEY'], '', SITE_ID);
$dirOptionsExt = Option::get('redsign.tuning', $arDefaultSettings[5]['KEY'], '', SITE_ID);
$arErrors = [];

$documentRoot = \Bitrix\Main\Application::getDocumentRoot();
if (!file_exists($documentRoot . $fileOptions)) {
    $arErrors[] = 'Option file is not found.';
}

$arCurrentValues = [];
$arExcludeParams = [];
foreach ($arDefaultSettings as $arItem) {
    $arExcludeParams[] = $arItem['KEY'];
}

$temporary = include($documentRoot . $fileOptions);
if (isset($temporary['PARAMETERS'])) {
    $tabs = $temporary['TABS'];
    $options = $temporary['PARAMETERS'];
} else {
    $tabs = [];
    $options = $temporary;
}

if ($fileOptionsExt != '' && file_exists($documentRoot . $fileOptionsExt)) {
    $temporaryExt = include($documentRoot . $fileOptionsExt);

    if (isset($temporaryExt['PARAMETERS'])) {
        if (!empty($temporaryExt['TABS'])) {
            $tabs = array_merge($tabs, $temporaryExt['TABS']);
        }
        if (!empty($temporaryExt)) {
            $options = array_merge($options, $temporaryExt['PARAMETERS']);
        }
    } else {
        if (!empty($temporaryExt)) {
            $options = array_merge($options, $temporaryExt['PARAMETERS']);
        }
    }
}

if (empty($options)) {
    $arErrors[] = 'Options is empty.';
}

if (!empty($arErrors)) {
    $tuning = Tuning\TuningCore::getInstance([]);
    return;
}

if ('Y' == $fromSession) {
    $optionsInstance = new Tuning\OptionManagerSession($options);
} else {
    $optionsInstance = new Tuning\OptionManagerBitrix($options);
}

$optionCore = Tuning\TuningOption::getInstance();

if (
    !empty($dirOptionsExt)
    && file_exists($documentRoot . $dirOptionsExt)
    && is_dir($documentRoot . $dirOptionsExt)
) {
    $optionCore->addOptionPath($documentRoot . $dirOptionsExt);
}

$optionCore->defaultInit();

$params = [
    'tabs' => $tabs,
    'options' => $optionsInstance,
];
$tuning = Tuning\TuningCore::getInstance($params);

if ($tuning) {
    $instanceMacrosManager = Tuning\MacrosManager::getInstance($tuning);
    $instanceMacrosManager->initMacrosList();
}
