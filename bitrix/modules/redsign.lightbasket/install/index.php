<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Redsign\LightBasket\Entity;

Loc::loadMessages(__FILE__);


class redsign_lightbasket extends CModule
{
    /** @var string */
    public $MODULE_ID = 'redsign.lightbasket';
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
    public $MODULE_GROUP_RIGHTS = 'Y';
    /** @var ?string */
    public $documentRoot = '';

    public function __construct()
    {
        $arModuleVersion = [];

        $this->documentRoot = \Bitrix\Main\Application::getDocumentRoot();

        include __DIR__ . '/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = Loc::getMessage('RS_LIGHTBASKET_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('RS_LIGHTBASKET_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('RS_LIGHTBASKET_SPER_PARTNER');
        $this->PARTNER_URI = 'https://www.redsign.ru/';
    }

    public function InstallDB(bool $install_wizard = true): bool
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            /** @var \Bitrix\Main\DB\Connection */
            $connection = Application::getConnection();
            $entityClasses = [
                Entity\BasketUserTable::class,
                Entity\BasketItemTable::class,
            ];

            foreach ($entityClasses as $entityClass) {
                if ($connection->isTableExists($entityClass::getTableName()))
                    continue;

                $entityClass::getEntity()->createDbTable();
            }
        }

        ModuleManager::registerModule($this->MODULE_ID);

        return true;
    }

    public function UnInstallDB(array $arParams = []): bool
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            /** @var \Bitrix\Main\DB\Connection */
            $connection = Application::getConnection();
            $entityClasses = [
                Entity\BasketUserTable::class,
                Entity\BasketItemTable::class,
            ];
            foreach ($entityClasses as $entityClass) {
                $entity = $entityClass::getEntity();

                if (!$connection->isTableExists($entityClass::getTableName()))
                    continue;

                $connection->dropTable($entityClass::getTableName());
            }
        }

        ModuleManager::unRegisterModule($this->MODULE_ID);
        Option::delete($this->MODULE_ID);

        return true;
    }

    public function InstallEvents(): bool
    {
        include_once(__DIR__ . '/events.php');
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

    public function InstallPublic(array $arParams = []): bool
    {
        $bReWriteAdditionalFiles = ($arParams['public_rewrite'] == 'Y');
        $rsSite = CSite::GetList();
        while ($site = $rsSite->Fetch()) {
            $source = realpath(__DIR__ . '/../public/') ?: '';
            $target = $site['ABS_DOC_ROOT'] . $site['DIR'] . $arParams['public_dir'] . '/';
            $this->copyPublicFiles($source, $target, $site, $bReWriteAdditionalFiles);
        }

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

        Redsign\Lightbasket\Core::registerInstallation($arData);

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

        Redsign\Lightbasket\Core::registerInstallation($arData);

        return true;
    }

    public function DoInstall(bool $display = true): bool
    {
        /** @var CMain $APPLICATION */
        global $APPLICATION, $step;

        $step = (int) $step;

        if ($step < 2 && $display) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage('rs_lighbasket_install'), __DIR__ . '/inst1.php');
        } else {
            $this->InstallFiles();
            $this->InstallDB(false);
            $this->InstallEvents();
            $this->InstallRegister();

            if (
                isset($_REQUEST['install_public']) && $_REQUEST['install_public'] == 'Y' &&
                isset($_REQUEST['public_dir']) && strlen($_REQUEST['public_dir']) > 0
            ) {
                $this->InstallPublic([
                    'public_dir' => $_REQUEST['public_dir'],
                    'public_rewrite' => $_REQUEST['public_rewrite'],
                ]);
            }
        }

        return true;
    }

    public function DoUninstall(bool $display = true): bool
    {
        $this->UnInstallRegister();
        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallEvents();

        return true;
    }

    protected function copyPublicFiles(string $source, string $target, array $site, bool $bReWriteAdditionalFiles): bool
    {
        if (!file_exists($source)) {
            return false;
        }

        CheckDirPath($target);
        $dh = opendir($source);

        if (!$dh) {
            return false;
        }

        while ($file = readdir($dh)) {
            if ($file == '.' || $file == '..') {
                continue;
            } elseif (is_dir($source . $file)) {
                $this->copyPublicFiles($source . $file . '/', $target . $file . '/', $site, $bReWriteAdditionalFiles);
                continue;
            }

            $targetFile = new \Bitrix\Main\IO\File($target . $file);
            $sourceFile = new \Bitrix\Main\IO\File($source . $file);

            if ($bReWriteAdditionalFiles || !$targetFile->isExists()) {

                $php_source = $sourceFile->getContents();

                if (preg_match_all('/Loc::getMessage\("(.*?)"\)/', $php_source, $matches)) {
                    IncludeModuleLangFile($source . $file, $site['LANGUAGE_ID']);
                    foreach ($matches[0] as $i => $text) {
                        $php_source = str_replace(
                            $text,
                            '"' . Loc::getMessage($matches[1][$i]) . '"',
                            $php_source
                        );
                    }
                }

                $targetFile->putContents($php_source);
            }
        }

        return true;
    }
}
