<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;

/**
 * @var \CUpdater $updater
 * @var string $updaterName
 * @var string $updaterPath
*/

Loc::loadLanguageFile(__DIR__.'/updater.php');

$moduleId = 'redsign.tuning';

$documentRoot = Application::getDocumentRoot();
$modulePath = $documentRoot.'/bitrix/modules/'.$moduleId;
$includeModule = Loader::includeModule($updater->moduleID);

/**
 * @var string[] $arDeleteTemplate Files to remove
 */
$arDelete = [
];

foreach ($arDelete as $path)
{
	if (File::isFileExists($path))
	{
		File::deleteFile($path);
	}
	elseif (Directory::isDirectoryExists($path))
	{
		Directory::deleteDirectory($path);
	}
}

if ($includeModule)
{
	$updater->CopyFiles('install/components', 'components');
	$updater->CopyFiles('install/js', 'js');
}

if (Loader::includeModule('redsign.devfunc'))
{
	if (File::isFileExists(Application::getDocumentRoot().$updaterPath.'/install/version.php'))
	{
		include(Application::getDocumentRoot().$updaterPath.'/install/version.php');
	}
	
	$sServerName = Option::get('main', 'server_name');

	$arData = [
		'devfunc-action' => 'update',
		'mp_code' => [$updater->moduleID],
		'site_url' => $sServerName,
		'module_version' => $updaterVersion
	];

	if (is_array($arSites) && count($arSites) > 0)
	{
		foreach ($arSites as $arSite)
		{
			$arSite['DOMAINS'] = explode("\n", $arSite['DOMAINS']);

			foreach ($arSite['DOMAINS'] as $key => $domain)
			{
				$domain = trim($domain);
				if ($sServerName != $domain)
				{
					$arData['site_url'] .= "\n".$domain;
				}
			}
		}
	}

	$arData['site_url'] = $APPLICATION->ConvertCharset($arData['site_url'], SITE_CHARSET, 'windows-1251');

	$ret = \Redsign\DevFunc\Module::registerInstallation($arData);
}
