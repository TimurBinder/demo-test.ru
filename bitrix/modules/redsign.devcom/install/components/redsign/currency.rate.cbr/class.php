<?php

namespace Redsign\Components;

use Bitrix\Main\SystemException;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Web\HttpClient;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


class CurrencyRateCbr extends \CBitrixComponent
{
    public const CBR_URL = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=';

    /**
     * @param array $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        $arParams = parent::onPrepareComponentParams($arParams);

        $arParams['CACHE_TIME'] = $arParams['CACHE_TIME'] ?? 3600;

        return $arParams;
    }

    public function executeComponent()
    {
        try {
            if (!$this->extractDataFromCache()) {
                $this->obtainResult();
                $this->setResultCacheKeys($this->getCacheKeys());
                $this->putDataToCache();
            }
        } catch (\Throwable $ex) {
            $this->abortDataCache();
            ShowError($ex->getMessage());
        }

        $this->IncludeComponentTemplate();
    }

    protected function extractDataFromCache(): bool
    {
        return !($this->startResultCache(false, $this->getAdditionalCacheId(), $this->getComponentCachePath()));
    }

    protected function getAdditionalCacheId(): array
    {
        $cacheId = [
        ];

        return $cacheId;
    }

    protected function getComponentCachePath(): string
    {
        return '/' . $this->getSiteId() . $this->getRelativePath();
    }

    protected function putDataToCache(): void
    {
    }

    protected function getCacheKeys(): array
    {
        return [];
    }

    protected function abortDataCache(): void
    {
        $this->abortResultCache();
    }

    protected function obtainResult(): void
    {
        $this->arResult['ITEMS'] = [
            'TODAY' => $this->getRates(new Date()),
            'YESTERDAY' => $this->getRates((new Date())->add('-1 day')),
        ];
    }

    protected function getRates(Date $date): array
    {
        $result = [];

        $url = self::CBR_URL . $date->format('d/m/Y');

        $client = new HttpClient();
        $response = $client->get($url);

        $xml = new \CDataXML();
        if (!$xml->LoadString($response))
            throw new SystemException('Unsupported answer');

        $response = $xml->GetArray();

        if (!$response)
            throw new SystemException('Unsupported answer');

        foreach ($response['ValCurs']['#']['Valute'] as $val) {
            $code = $val['#']['CharCode'][0]['#'];

            if (!in_array($code, ['USD', 'EUR']))
                continue;

            $result[$code]['ID'] = $val['#']['NumCode'][0]['#'];
            $result[$code]['DATE'] = $date->format('d.m');
            $result[$code]['CODE'] = $code;
            $result[$code]['NAME'] = $val['#']['Name'][0]['#'];
            $result[$code]['VALUE'] = $val['#']['Value'][0]['#'];
            $result[$code]['VALUE_ROUND'] = round((float)str_replace(',', '.', $val['#']['Value'][0]['#']), 2);
        }

        return $result;
    }
}
