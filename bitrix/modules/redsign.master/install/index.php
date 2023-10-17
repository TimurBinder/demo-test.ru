<?
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\EventManager;
use \Bitrix\Main\Localization\Loc;

global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class redsign_master extends CModule
{
	var $MODULE_ID = "redsign.master";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

	function redsign_master()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("REDSIGN.MASTER.INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("REDSIGN.MASTER.INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = GetMessage("REDSIGN.MASTER.SPER_PARTNER");
		$this->PARTNER_URI = GetMessage("REDSIGN.MASTER.PARTNER_URI");
	}

	function InstallDB($install_wizard = true)
	{
		global $DB, $DBType, $APPLICATION;

		COption::SetOptionString("redsign.master", "wizard_version", "1");
		ModuleManager::registerModule($this->MODULE_ID);

		return true;
	}

	function UnInstallDB($arParams = Array())
	{
		global $DB, $DBType, $APPLICATION;

		ModuleManager::unregisterModule($this->MODULE_ID);

		unset($_SESSION['redsign.tuning']);

		return true;
	}

	function InstallEvents()
	{
		EventManager::getInstance()->registerEventHandler(
			'redsign.tuning',
			'onBeforeGetReadyMacros',
			$this->MODULE_ID,
			'Redsign\\Master\\MyTemplate',
			'rsTuningOnBeforeGetReadyMacros'
		);

		EventManager::getInstance()->registerEventHandler(
			'sale',
			'OnSaleComponentOrderProperties',
			$this->MODULE_ID,
			'Redsign\\Master\\Location',
			'OnSaleComponentOrderSetLocation'
		);

		return true;
	}

	function UnInstallEvents()
	{
		EventManager::getInstance()->unRegisterEventHandler(
			'redsign.tuning',
			'onBeforeGetReadyMacros',
			$this->MODULE_ID,
			'Redsign\\Master\\MyTemplate',
			'rsTuningOnBeforeGetReadyMacros'
		);
		EventManager::getInstance()->unRegisterEventHandler(
			'sale',
			'OnSaleComponentOrderProperties',
			$this->MODULE_ID,
			'Redsign\\Master\\Location',
			'OnSaleComponentOrderSetLocation'
		);

		return true;
	}

	function InstallFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.master/install/modules", $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.master/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		//CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.master/install/wizards/bitrix/eshop.mobile", $_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/bitrix/eshop.mobile", true, true);
		//CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/redsign.master/install/images",  $_SERVER["DOCUMENT_ROOT"]."/bitrix/images/redsign.master", true, true);

		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/redsign.master/install/js', $_SERVER['DOCUMENT_ROOT'].'/bitrix/js', true, true);

		return true;
	}

	function UnInstallFiles()
	{
		//DeleteDirFilesEx("/bitrix/images/redsign.master/");//images
		DeleteDirFilesEx("/bitrix/js/redsign.master/");
		DeleteDirFilesEx("/bitrix/components/rsmaster/");
		DeleteDirFilesEx("/bitrix/wizards/redsign/master/");

		return true;
	}

	function InstallPublic()
	{
		return true;
	}

	function UnInstallPublic()
	{
		return true;
	}

	function InstallOptions()
	{
		return true;
	}

	function UnInstallOptions()
	{
		COption::RemoveOption('redsign.master');
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION, $step;

		$this->InstallFiles();
		$this->InstallDB(false);
		$this->InstallEvents();
		$this->InstallPublic();

		return true;
	}

	function DoUninstall()
	{
		global $APPLICATION, $step;

		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();

		return true;
	}
}
?>