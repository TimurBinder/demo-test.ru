<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Sale;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


Loc::loadMessages(__FILE__);

class RedsignLocationTop extends \CBitrixComponent
{
    protected function checkModules(): void
    {
        $needModules = ['sale'];
        foreach ($needModules as $module) {
            if (!Loader::includeModule($module)) {
                throw new SystemException(
                    Loc::getMessage('RS_DEVFUNC_RLT_MODULE_NOT_INSTALLED', ['#MODULE_ID#' => $module]) ?: ''
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

        $locationIterator = Sale\Location\LocationTable::getList([
            'order' => ['SORT' => 'asc'],
            'select' => [
                'ID',
                'LNAME' => 'NAME.NAME',
                'CODE' => 'CODE'
            ],
            'filter' => [
                'NAME.LANGUAGE_ID' => LANGUAGE_ID,
                'TYPE.CODE' => 'CITY'
            ],
            'limit' => (isset($this->arParams['COUNT_ITEMS']) ? $this->arParams['COUNT_ITEMS'] : 15)
        ]);

        $this->arResult['ITEMS'] = $locationIterator->fetchAll();
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
