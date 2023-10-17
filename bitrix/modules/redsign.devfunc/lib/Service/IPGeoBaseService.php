<?php

namespace Redsign\Devfunc\Service;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Service\GeoIp;

Loc::loadMessages(__FILE__);

class IPGeoBaseService extends GeoIp\Base
{
    public function getTitle(): string
    {
        return Loc::getMessage('RS_DF_GEOIP_IPGEOBASE_TITLE') ?: '';
    }

    public function getDescription(): string
    {
        return Loc::getMessage('RS_DF_GEOIP_IPGEOBASE_DESCRIPTION') ?: '';
    }

    /**
     * @param string $ip Ip address
     * @param string $lang Language identifier
     * @return GeoIp\Result | null
     */
    public function getDataResult($ip, $lang = '')
    {
        $dataResult = new GeoIp\Result();
        $geoData = new GeoIp\Data();

        $geoData->ip = $ip;
        $geoData->lang = $lang = strlen($lang) > 0 ? $lang : 'ru';

        $ipGeoBase = new \Redsign\DevFunc\GeoIp\IPGeoBase('utf-8' == strtolower(SITE_CHARSET));
        $res = $ipGeoBase->getRecord($ip);

        if ($res) {
            $geoData->countryName = $res['cc'] == 'UA'
                ? Loc::getMessage('RS_DF_GEOIP_UA')
                : Loc::getMessage('RS_DF_GEOIP_RU');

            $geoData->countryCode = $res['cc'];
            $geoData->regionName = $res['region'];
            $geoData->cityName = $res['city'];
            $geoData->latitude = $res['lat'];
            $geoData->longitude = $res['lng'];
        }

        $dataResult->setGeoData($geoData);

        return $dataResult;
    }

    public function getSupportedLanguages(): array
    {
        return ['ru'];
    }

    public function getProvidingData(): GeoIp\ProvidingData
    {
        $result = new GeoIp\ProvidingData();
        $result->countryName = true;
        $result->countryCode = true;
        $result->regionName = true;
        $result->cityName = true;
        $result->latitude = true;
        $result->longitude = true;

        return $result;
    }
}
