<?php

use Bitrix\Main\ModuleManager;
//use Bitrix\Main\Config\Option;
//use Bitrix\Main\ArgumentException;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if (class_exists('mst_copyeller')) return;

Class mst_copyeller extends CModule
{

	var $MODULE_ID = 'mst.copyeller';
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $PARTNER_NAME;
	public $PARTNER_URI;
	public $MODULE_GROUP_RIGHTS = 'N';
	public $NEED_MODULES = array('iblock', 'asd.iblock');

	public function __construct()
	{
		$arModuleVersion = array();

		$path = str_replace('\\', '/', __FILE__);
		$path = substr($path, 0, strlen($path) - strlen('/index.php'));
		include($path.'/version.php');

		if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}

		$this->PARTNER_NAME = Loc::getMessage("MST_PARTNER_NAME");
		$this->PARTNER_URI = 'http://www.mediasystem.ru/';

		$this->MODULE_NAME = Loc::getMessage('MST_IBLOCK_MODULE_NAME');
		$this->MODULE_DESCRIPTION = Loc::getMessage('MST_IBLOCK_MODULE_DESCRIPTION');
	}

	public function DoInstall()
	{
		if (is_array($this->NEED_MODULES) && !empty($this->NEED_MODULES))
		{
			foreach ($this->NEED_MODULES as $module)
			{
				if (!ModuleManager::isModuleInstalled($module))
				{
					//throw new ArgumentException(sprintf('Module `%s` is not installed', $module));

					/*try
					{
						throw new \Bitrix\Main\ArgumentException(sprintf('Module `%s` is not installed', "main"));
					}
					catch(Exception $e)
					{
						echo $e->getMessage();
					}*/

					global $APPLICATION;
					$APPLICATION->ThrowException(sprintf('Module `%s` is not installed', $module));
					return false;
				}
			}
		}

		$eventManager = Bitrix\Main\EventManager::getInstance(); 
		$eventManager->registerEventHandlerCompatible("iblock", "OnBeforeIBlockElementAdd", $this->MODULE_ID, "\Mst\Copyeller\EventHandler", "OnBeforeIBlockElementAddHandler");
		$eventManager->registerEventHandlerCompatible("iblock", "OnBeforeIBlockPropertyAdd", $this->MODULE_ID, "\Mst\Copyeller\EventHandler", "OnBeforeIBlockPropertyAddHandler");
		$eventManager->registerEventHandlerCompatible("iblock", "OnBeforeIBlockPropertyUpdate", $this->MODULE_ID, "\Mst\Copyeller\EventHandler", "OnBeforeIBlockPropertyAddHandler");

		//$this->InstallFiles();

		ModuleManager::registerModule($this->MODULE_ID);
		echo CAdminMessage::ShowNote("MST_IBLOCK_MODULE_INSTALL");
	}

	public function DoUninstall()
	{
		$eventManager = Bitrix\Main\EventManager::getInstance(); 
		$eventManager->unregisterEventHandler("iblock", "OnBeforeIBlockElementAdd", $this->MODULE_ID, "\Mst\Copyeller\EventHandler", "OnBeforeIBlockElementAddHandler");
		$eventManager->unregisterEventHandler("iblock", "OnBeforeIBlockPropertyAdd", $this->MODULE_ID, "\Mst\Copyeller\EventHandler", "OnBeforeIBlockPropertyAddHandler");
		$eventManager->unregisterEventHandler("iblock", "OnBeforeIBlockPropertyUpdate", $this->MODULE_ID, "\Mst\Copyeller\EventHandler", "OnBeforeIBlockPropertyAddHandler");
		COption::RemoveOption($this->MODULE_ID);

		//$this->UnInstallFiles();

		ModuleManager::unRegisterModule($this->MODULE_ID);
		echo CAdminMessage::ShowNote("MST_IBLOCK_MODULE_UNINSTALL");
	}
}