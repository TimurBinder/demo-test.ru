<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\ModuleManager;

$module_id = 'redsign.lightbasket';

if($obModule = \CModule::CreateModuleObject($module_id)){
	if(!$obModule->IsInstalled()){
		// $obModule->DoInstall();
        
        ModuleManager::registerModule($module_id);
        
        $obModule->InstallFiles();
        $obModule->InstallDB(false);
        $obModule->InstallEvents();
	}
}
