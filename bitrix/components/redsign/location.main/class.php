<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Redsign\DevFunc\Sale\Location\Location;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


Loc::loadMessages(__FILE__);

class RedsignLocationMain extends \CBitrixComponent
{
    protected function checkModules(): void
    {
        $needModules = ['redsign.devfunc', 'catalog', 'sale'];
        foreach ($needModules as $module) {
            if (!Loader::includeModule($module)) {
                throw new SystemException(
                    Loc::getMessage('RS_DEVFUNC_RLM_MODULE_NOT_INSTALLED', ['#MODULE_ID#' => $module]) ?: ''
                );
            }
        }
    }

    protected function abortDataCache(): void
    {
        $this->AbortResultCache();
    }

    public function readDataFromCache(): bool
    {
        $cacheAddon = [];

        return !($this->startResultCache(false, $cacheAddon, md5(serialize($this->arParams))));
    }

    public function putDataToCache(): void
    {
        $this->SetResultCacheKeys(array_keys($this->arResult));
    }

    protected function obtainResult(): void
    {
        $this->arResult = Location::getMyCity();
    }

    /**
     * @inheritdoc
     */
    public function executeComponent()
    {
        try {
            $this->checkModules();

            if (!$this->readDataFromCache()) {
                $this->obtainResult();
                $this->putDataToCache();
                $this->includeComponentTemplate();
            }
        } catch (\Throwable $e) {
            $this->abortDataCache();
            ShowError($e->getMessage());
        }
    }
}
