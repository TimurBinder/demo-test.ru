<?php

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\Loader;
use \Redsign\LightBasket\Entity;

global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

class redsign_lightbasket extends CModule
{
    public $MODULE_ID = 'redsign.lightbasket';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_CSS;
    public $MODULE_GROUP_RIGHTS = 'Y';

    // const MODULE_DIR = '/local/modules/redsign.lightbasket';
    const MODULE_DIR = '/bitrix/modules/redsign.lightbasket';

    public function redsign_lightbasket()
    {
        $arModuleVersion = array();

        $path = str_replace('\\', '/', __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include $path.'/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_NAME = GetMessage('RS.LIGHTBASKET.INSTALL_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('RS.LIGHTBASKET.INSTALL_DESCRIPTION');
        $this->PARTNER_NAME = GetMessage('RS.LIGHTBASKET.INSTALL_COPMPANY_NAME');
        $this->PARTNER_URI = 'http://redsign.ru/';
    }

    public function InstallDB($install_wizard = true)
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            Entity\BasketUserTable::getEntity()->createDbTable();
            Entity\BasketItemTable::getEntity()->createDbTable();
        }

        return true;
    }

    public function UnInstallDB($arParams = array())
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $connection = Application::getInstance()->getConnection();
            $connection->dropTable(Entity\BasketUserTable::getTableName());
            $connection->dropTable(Entity\BasketItemTable::getTableName());
        }

        return true;
    }

    public function InstallEvents()
    {
        include_once($_SERVER["DOCUMENT_ROOT"].static::MODULE_DIR."/install/events.php");
        return true;
    }

    public function UnInstallEvents()
    {
        return true;
    }

    public function InstallFiles(array $arParams = array())
    {
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'].static::MODULE_DIR.'/install/components', $_SERVER['DOCUMENT_ROOT'].'/bitrix/components', true, true);
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'].static::MODULE_DIR.'/install/js', $_SERVER['DOCUMENT_ROOT'].'/bitrix/js', true, true);

        return true;
    }

    public function InstallPublic(array $arParams = array())
    {
        $bReWriteAdditionalFiles = ($arParams['public_rewrite'] == 'Y');
        $rsSite = CSite::GetList(($by = 'sort'), ($order = 'asc'));
        while ($site = $rsSite->Fetch()) {
            $source = $_SERVER['DOCUMENT_ROOT'].static::MODULE_DIR.'/public/';
            $target = $site['ABS_DOC_ROOT'].$site['DIR'].$arParams['public_dir'].'/';
            $this->copyPublicFiles($source, $target, $site, $bReWriteAdditionalFiles);
        }
    }

    protected function copyPublicFiles($source, $target, $site, $bReWriteAdditionalFiles)
    {
        if (file_exists($source)) {
            CheckDirPath($target);
            $dh = opendir($source);
            while ($file = readdir($dh)) {
                if ($file == '.' || $file == '..') {
                    continue;
                } elseif (is_dir($source.$file)) {
                    $this->copyPublicFiles($source.$file.'/', $target.$file.'/', $site, $bReWriteAdditionalFiles);
                    continue;
                }
                if ($bReWriteAdditionalFiles || !file_exists($target.$file)) {
                    $fh = fopen($source.$file, 'rb');
                    $php_source = fread($fh, filesize($source.$file));
                    fclose($fh);
                    if (preg_match_all('/GetMessage\("(.*?)"\)/', $php_source, $matches)) {
                        IncludeModuleLangFile($source.$file, $site['LANGUAGE_ID']);
                        foreach ($matches[0] as $i => $text) {
                            $php_source = str_replace(
                                        $text,
                                        '"'.GetMessage($matches[1][$i]).'"',
                                        $php_source
                                    );
                        }
                    }
                    $fh = fopen($target.$file, 'wb');
                    fwrite($fh, $php_source);
                    fclose($fh);
                }
            }
        }
    }

    public function UnInstallFiles()
    {
        return true;
    }

    public function DoInstall()
    {
        global $APPLICATION, $step;

        $step = intval($step);

        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(GetMessage('rs_lighbasket_install'), $_SERVER['DOCUMENT_ROOT'].static::MODULE_DIR.'/install/inst1.php');
        } elseif ($step == 2) {
            ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallFiles();
            $this->InstallDB(false);
            $this->InstallEvents();

            if (
                isset($_REQUEST['install_public']) && $_REQUEST['install_public'] == 'Y' &&
                isset($_REQUEST['public_dir']) && strlen($_REQUEST['public_dir']) > 0
            ) {
                $this->InstallPublic(array(
                    'public_dir' => $_REQUEST['public_dir'],
                    'public_rewrite' => $_REQUEST['public_rewrite'],
                ));
            }
        }

        return true;
    }

    public function DoUninstall()
    {
        global $APPLICATION, $step;

        $step = intval($step);

        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallEvents();

        ModuleManager::unregisterModule($this->MODULE_ID);

        return true;
    }
}
