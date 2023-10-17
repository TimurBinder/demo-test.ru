<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);


class redsign_favorite extends CModule
{
    /** @var string */
    public $MODULE_ID = 'redsign.favorite';
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

        $this->MODULE_NAME = Loc::getMessage('RS_FAVORITE_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('RS_FAVORITE_INSTALL_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('RS_FAVORITE_SPER_PARTNER');
        $this->PARTNER_URI = 'https://www.redsign.ru/';
    }

    public function InstallDB(bool $install_wizard = true): bool
    {
        /**
         * @var CDatabase $DB
         * @var string $DBType
         * @var CMain $APPLICATION
         */
        global $DB, $DBType, $APPLICATION;

        $errors = false;

        /** @var CDBResult|false */
        $result = $DB->Query('SELECT \'x\' FROM b_redsign_favorite', true);
        if (!$result) {
            $errors = $DB->RunSQLBatch(__DIR__ . '/db/' . $DBType . '/install.sql');
        }

        if ($errors !== false) {
            $APPLICATION->throwException(implode('', $errors));
            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);

        return true;
    }

    public function UnInstallDB(array $arParams = []): bool
    {
        /**
         * @var CDatabase $DB
         * @var string $DBType
         * @var CMain $APPLICATION
         */
        global $DB, $DBType, $APPLICATION;

        $errors = $DB->RunSQLBatch(__DIR__ . '/db/' . $DBType . '/uninstall.sql');
        if ($errors !== false) {
            $APPLICATION->throwException(implode('', $errors));
            return false;
        }

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
        CopyDirFiles(
            __DIR__ . '/js',
            $this->documentRoot . '/bitrix/js',
            true,
            true
        );

        return true;
    }

    public function UnInstallFiles(): bool
    {
        DeleteDirFiles(__DIR__ . '/components', $this->documentRoot . '/bitrix/components');
        DeleteDirFiles(__DIR__ . '/js', $this->documentRoot . '/bitrix/js');

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

        Redsign\Favorite\Core::registerInstallation($arData);

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

        Redsign\Favorite\Core::registerInstallation($arData);

        return true;
    }

    public function DoInstall(): bool
    {
        $this->InstallFiles();
        $this->InstallDB(false);
        $this->InstallEvents();
        $this->InstallRegister();

        return true;
    }

    public function DoUninstall(): bool
    {
        $this->UnInstallRegister();
        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallEvents();

        return true;
    }
}
