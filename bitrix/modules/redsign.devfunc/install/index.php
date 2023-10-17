<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);


class redsign_devfunc extends CModule
{
    /** @var string */
    public $MODULE_ID = 'redsign.devfunc';
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

        $this->MODULE_NAME = Loc::getMessage('RS_DEVFUNC_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('RS_DEVFUNC_INSTALL_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('RS_DEVFUNC_SPER_PARTNER');
        $this->PARTNER_URI = 'https://www.redsign.ru/';
    }

    public function InstallDB(bool $install_wizard = true): bool
    {
        ModuleManager::registerModule($this->MODULE_ID);

        Option::set($this->MODULE_ID, 'fakeprice_active', 'Y');
        Option::set($this->MODULE_ID, 'propcode_cml2link', 'CML2_LINK');
        Option::set($this->MODULE_ID, 'propcode_fakeprice', 'PROD_PRICE_FALSE');

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
        $eventManager = EventManager::getInstance();

        $eventManager->registerEventHandler(
            'iblock',
            'OnAfterIBlockElementAdd',
            $this->MODULE_ID,
            'RSDevFuncOffersExtension',
            'OnAfterIBlockElementAddHandler'
        );

        $eventManager->registerEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            $this->MODULE_ID,
            'RSDevFuncOffersExtension',
            'OnAfterIBlockElementUpdateHandler'
        );

        if (ModuleManager::isModuleInstalled('catalog') && ModuleManager::isModuleInstalled('sale')) {
            $eventManager->registerEventHandler(
                'catalog',
                'OnPriceAdd',
                $this->MODULE_ID,
                'RSDevFuncOffersExtension',
                'OnPriceUpdateAddHandler'
            );

            $eventManager->registerEventHandler(
                'catalog',
                'OnPriceUpdate',
                $this->MODULE_ID,
                'RSDevFuncOffersExtension',
                'OnPriceUpdateAddHandler'
            );
        }

        $eventManager->registerEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            'RSSeo',
            'addMetaOG'
        );

        if (ModuleManager::isModuleInstalled('iblock')) {
            $eventManager->registerEventHandler(
                'iblock',
                'OnIBlockPropertyBuildList',
                $this->MODULE_ID,
                '\Redsign\DevFunc\Iblock\Property',
                'OnIBlockPropertyBuildListStores'
            );

            $eventManager->registerEventHandler(
                'iblock',
                'OnIBlockPropertyBuildList',
                $this->MODULE_ID,
                '\Redsign\DevFunc\Iblock\Property',
                'OnIBlockPropertyBuildListPrices'
            );

            $eventManager->registerEventHandler(
                'iblock',
                'OnIBlockPropertyBuildList',
                $this->MODULE_ID,
                '\Redsign\DevFunc\Iblock\Property',
                'OnIBlockPropertyBuildListLocations'
            );

            $eventManager->registerEventHandler(
                'iblock',
                'OnIBlockPropertyBuildList',
                $this->MODULE_ID,
                '\Redsign\DevFunc\Iblock\CustomProperty\CustomFilter',
                'getDescription'
            );
        }

        $eventManager->registerEventHandler(
            'main',
            'onMainGeoIpHandlersBuildList',
            $this->MODULE_ID,
            '\Redsign\DevFunc\Sale\Location\Location',
            'onGeoIpHandlersBuildList'
        );

        $eventManager->registerEventHandler(
            'sale',
            'OnSaleComponentOrderProperties',
            $this->MODULE_ID,
            '\Redsign\DevFunc\Sale\Location\Location',
            'OnSaleComponentOrderSetLocation'
        );

        return true;
    }

    public function UnInstallEvents(): bool
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'iblock',
            'OnAfterIBlockElementAdd',
            $this->MODULE_ID,
            'RSDevFuncOffersExtension',
            'OnAfterIBlockElementAddHandler'
        );

        $eventManager->unRegisterEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            $this->MODULE_ID,
            'RSDevFuncOffersExtension',
            'OnAfterIBlockElementUpdateHandler'
        );

        if (ModuleManager::isModuleInstalled('catalog') && ModuleManager::isModuleInstalled('sale')) {
            $eventManager->unRegisterEventHandler(
                'catalog',
                'OnPriceAdd',
                $this->MODULE_ID,
                'RSDevFuncOffersExtension',
                'OnPriceUpdateAddHandler'
            );

            $eventManager->unRegisterEventHandler(
                'catalog',
                'OnPriceUpdate',
                $this->MODULE_ID,
                'RSDevFuncOffersExtension',
                'OnPriceUpdateAddHandler'
            );
        }

        $eventManager->unRegisterEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            'RSSeo',
            'addMetaOG'
        );

        if (ModuleManager::isModuleInstalled('iblock')) {
            $eventManager->unRegisterEventHandler(
                'iblock',
                'OnIBlockPropertyBuildList',
                $this->MODULE_ID,
                '\Redsign\DevFunc\Iblock\Property',
                'OnIBlockPropertyBuildListStores'
            );

            $eventManager->unRegisterEventHandler(
                'iblock',
                'OnIBlockPropertyBuildList',
                $this->MODULE_ID,
                '\Redsign\DevFunc\Iblock\Property',
                'OnIBlockPropertyBuildListPrices'
            );

            $eventManager->unRegisterEventHandler(
                'iblock',
                'OnIBlockPropertyBuildList',
                $this->MODULE_ID,
                '\Redsign\DevFunc\Iblock\Property',
                'OnIBlockPropertyBuildListLocations'
            );

            $eventManager->unRegisterEventHandler(
                'iblock',
                'OnIBlockPropertyBuildList',
                $this->MODULE_ID,
                '\Redsign\DevFunc\Iblock\CustomProperty\CustomFilter',
                'getDescription'
            );
        }

        $eventManager->unRegisterEventHandler(
            'main',
            'onMainGeoIpHandlersBuildList',
            $this->MODULE_ID,
            '\Redsign\DevFunc\Sale\Location\Location',
            'onGeoIpHandlersBuildList'
        );

        $eventManager->unRegisterEventHandler(
            'sale',
            'OnSaleComponentOrderProperties',
            $this->MODULE_ID,
            '\Redsign\DevFunc\Sale\Location\Location',
            'OnSaleComponentOrderSetLocation'
        );

        return true;
    }

    public function InstallFiles(): bool
    {
        Option::set($this->MODULE_ID, 'no_photo_path', dirname(__DIR__, 1) . '/img/no-photo.png');

        $arFile = \CFile::MakeFileArray(dirname(__DIR__, 1) . '/img/no-photo.png');
        $fid = \CFile::SaveFile($arFile, 'redsign_devfunc_nophoto');
        Option::set($this->MODULE_ID, 'no_photo_fileid', $fid);

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
        CopyDirFiles(
            __DIR__ . '/panel',
            $this->documentRoot . '/bitrix/panel',
            true,
            true
        );

        CopyDirFiles(
            __DIR__ . '/admin',
            $this->documentRoot . '/bitrix/admin',
            true,
            true
        );

        return true;
    }

    public function UnInstallFiles(): bool
    {
        DeleteDirFiles(__DIR__ . '/admin', $this->documentRoot . '/bitrix/admin');
        DeleteDirFiles(__DIR__ . '/components', $this->documentRoot . '/bitrix/components');
        DeleteDirFiles(__DIR__ . '/js', $this->documentRoot . '/bitrix/js');
        DeleteDirFiles(__DIR__ . '/panel', $this->documentRoot . '/bitrix/panel');
        DeleteDirFiles(__DIR__ . '/themes', $this->documentRoot . '/bitrix/themes');

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

        Redsign\Devfunc\Module::registerInstallation($arData);

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

        Redsign\Devfunc\Module::registerInstallation($arData);

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
