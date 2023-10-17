<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

class progress365_realtyfeed extends CModule
{
    public function __construct()
    {

        Loc::loadMessages(__FILE__);

        if (file_exists(__DIR__ . "/version.php")) {

            $arModuleVersion = array();

            include_once(__DIR__ . "/version.php");

            $this->MODULE_ID = str_replace("_", ".", get_class($this));
            $this->MODULE_NAME = Loc::getMessage("PROGRESS365_REALTYFEED_NAME");
            $this->MODULE_DESCRIPTION = Loc::getMessage("PROGRESS365_REALTYFEED_DESCRIPTION");
            $this->PARTNER_NAME = Loc::getMessage("PROGRESS365_REALTYFEED_PARTNER_NAME");
            $this->PARTNER_URI = Loc::getMessage("PROGRESS365_REALTYFEED_PARTNER_URI");

            if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
                $this->MODULE_VERSION = $arModuleVersion["VERSION"];
                $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
            } else {
                $this->MODULE_VERSION = '0.0.1';
                $this->MODULE_VERSION_DATE = '01.01.2023';
            }
        }
    }

    public function DoInstall()
    {
        global $APPLICATION;

        if (CheckVersion(ModuleManager::getVersion("main"), "14.00.00")) {

            $this->InstallFiles();
            $this->InstallDB();

            ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallEvents();
        } else {

            $APPLICATION->ThrowException(
                Loc::getMessage("PROGRESS365_REALTYFEED_INSTALL_ERROR_VERSION")
            );
        }

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("PROGRESS365_REALTYFEED_INSTALL_TITLE") . " \"" . Loc::getMessage("PROGRESS365_REALTYFEED_NAME") . "\"",
            __DIR__ . "/step.php"
        );
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . "/components",
            Application::getDocumentRoot() . "/bitrix/components",
            true,
            true
        );

        // CopyDirFiles(
        //     __DIR__ . "/assets/styles",
        //     Application::getDocumentRoot() . "/bitrix/css/" . $this->MODULE_ID . "/",
        //     true,
        //     true
        // );
    }

    public function InstallDB()
    {

    }

    public function InstallEvents()
    {

        // EventManager::getInstance()->registerEventHandler(
        //     "main",
        //     "OnBeforeEndBufferContent",
        //     $this->MODULE_ID,
        //     "Progress365\RealtyFeed\Main",
        //     "appendScriptsToPage"
        // );
    }

    public function DoUninstall()
    {

        global $APPLICATION;

        $this->UnInstallFiles();
        $this->UnInstallDB();
        $this->UnInstallEvents();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("PROGRESS365_REALTYFEED_UNINSTALL_TITLE") . " \"" . Loc::getMessage("PROGRESS365_REALTYFEED_NAME") . "\"",
            __DIR__ . "/unstep.php"
        );
    }

    public function UnInstallFiles()
    {

        Directory::deleteDirectory(
            Application::getDocumentRoot() . "/bitrix/components/progress365"
        );

        // Directory::deleteDirectory(
        //     Application::getDocumentRoot() . "/bitrix/js/" . $this->MODULE_ID
        // );

        // Directory::deleteDirectory(
        //     Application::getDocumentRoot() . "/bitrix/css/" . $this->MODULE_ID
        // );
    }

    public function UnInstallDB()
    {

        Option::delete($this->MODULE_ID);
    }

    public function UnInstallEvents()
    {

        // EventManager::getInstance()->unRegisterEventHandler(
        //     "main",
        //     "OnBeforeEndBufferContent",
        //     $this->MODULE_ID,
        //     "Progress365\RealtyFeed\Main",
        //     "appendScriptsToPage"
        // );
    }
}