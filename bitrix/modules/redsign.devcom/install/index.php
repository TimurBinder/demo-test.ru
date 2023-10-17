<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);


class redsign_devcom extends CModule
{
    /** @var string */
    public $MODULE_ID = 'redsign.devcom';
    /** @var string */
    public $MODULE_VERSION;
    /** @var string */
    public $MODULE_VERSION_DATE;
    /** @var ?string */
    public $MODULE_NAME;
    /** @var ?string */
    public $MODULE_DESCRIPTION;
    /** @var ?string */
    public $MODULE_CSS;
    /** @var ?string */
    public $MODULE_GROUP_RIGHTS = 'N';
    /** @var ?string */
    public $documentRoot = '';

    public function __construct()
    {
        $arModuleVersion = [];

        $this->documentRoot = \Bitrix\Main\Application::getDocumentRoot();

        include __DIR__ . '/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = Loc::getMessage('RS_DEVCOM_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('RS_DEVCOM_INSTALL_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('RS_DEVCOM_SPER_PARTNER');
        $this->PARTNER_URI = 'https://www.redsign.ru/';
    }

    public function InstallDB(bool $install_wizard = true): bool
    {
        ModuleManager::registerModule($this->MODULE_ID);

        return true;
    }

    public function UnInstallDB(array $arParams = []): bool
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
        Option::delete($this->MODULE_ID);

        return true;
    }

    public function InstallEvents(): bool
    {
        return true;
    }

    public function UnInstallEvents(): bool
    {
        return true;
    }

    public function InstallFiles(): bool
    {
        CopyDirFiles(
            __DIR__ . '/components',
            $this->documentRoot . '/bitrix/components',
            true,
            true
        );

        return true;
    }

    public function UnInstallFiles(): bool
    {
        DeleteDirFiles(__DIR__ . '/components', $this->documentRoot . '/bitrix/components');

        return true;
    }

    public function InstallRegister(): bool
    {
        if (!Loader::includeModule($this->MODULE_ID))
            return false;

        $arData = [
            'mp_code' => [$this->MODULE_ID],
            'devfunc-action' => 'module-install',
        ];

        Redsign\Devcom\Core::registerInstallation($arData);

        return true;
    }

    public function UnInstallRegister(): bool
    {
        if (!Loader::includeModule($this->MODULE_ID))
            return false;

        $arData = [
            'mp_code' => [$this->MODULE_ID],
            'devfunc-action' => 'module-uninstall',
        ];

        Redsign\Devcom\Core::registerInstallation($arData);

        return true;
    }

    public function DoInstall(bool $display = true): bool
    {
        /** @var CMain $APPLICATION */
        global $APPLICATION;

        $this->InstallFiles();
        $this->InstallDB(false);
        $this->InstallEvents();
        $this->InstallRegister();

        if ($display) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('RS_DEVCOM_INSTALL_TITLE'),
                __DIR__ . '/install.php'
            );
        }

        return true;
    }

    public function DoUninstall(bool $display = true): bool
    {
        /** @var CMain $APPLICATION */
        global $APPLICATION;

        $this->UnInstallRegister();
        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallEvents();

        if ($display) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('RS_DEVCOM_UNINSTALL_TITLE'),
                __DIR__ . '/uninstall.php'
            );
        }

        return true;
    }
}
