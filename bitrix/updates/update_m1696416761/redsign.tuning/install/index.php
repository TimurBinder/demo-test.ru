<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 */

Loc::loadMessages(__FILE__);


class redsign_tuning extends CModule
{
	var $MODULE_ID = 'redsign.tuning';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = 'N';

	function __construct()
	{
		$arModuleVersion = [];

		include __DIR__.'/version.php';

		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

		$this->MODULE_NAME = Loc::getMessage('RS_TUNING_INSTALL_NAME');
		$this->MODULE_DESCRIPTION = Loc::getMessage('RS_TUNING_INSTALL_DESCRIPTION');
		$this->PARTNER_NAME = Loc::getMessage('RS_TUNING_SPER_PARTNER');
		$this->PARTNER_URI = Loc::getMessage('RS_TUNING_PARTNER_URI');
	}

	function InstallDB($install_wizard = true)
	{
		ModuleManager::registerModule($this->MODULE_ID);

		return true;
	}

	function UnInstallDB($arParams = [])
	{
		ModuleManager::unRegisterModule($this->MODULE_ID);
		Option::delete($this->MODULE_ID);

		unset($_SESSION[$this->MODULE_ID]);

		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles()
	{
		$documentRoot = Application::getDocumentRoot();

		CopyDirFiles(
			__DIR__.'/components',
			$documentRoot.'/bitrix/components',
			true,
			true
		);

		CopyDirFiles(
			__DIR__.'/js',
			$documentRoot.'/bitrix/js',
			true,
			true
		);

		return true;
	}

	function UnInstallFiles()
	{
		$documentRoot = Application::getDocumentRoot();

		DeleteDirFiles(__DIR__.'/components', $documentRoot.'/bitrix/components');
		DeleteDirFiles(__DIR__.'/js', $documentRoot.'/bitrix/js');

		return true;
	}

	function InstallRegister()
	{
		if (!Loader::includeModule($this->MODULE_ID))
			return;

		$arData = [
			'mp_code' => [$this->MODULE_ID],
			'devfunc-action' => 'module-install',
		];

		Redsign\Tuning\Core::registerInstallation($arData);

		return true;
	}

	function UnInstallRegister()
	{
		if (!Loader::includeModule($this->MODULE_ID))
			return;

		$arData = [
			'mp_code' => [$this->MODULE_ID],
			'devfunc-action' => 'module-uninstall',
		];

		Redsign\Tuning\Core::registerInstallation($arData);

		return true;
	}

	function DoInstall($arParams = [])
	{
		$this->InstallFiles();
		$this->InstallDB(false);
		$this->InstallEvents();
		$this->InstallRegister();

		return true;
	}

	function DoUninstall($arParams = [])
	{
		$this->UnInstallRegister();
		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();

		return true;
	}
}
