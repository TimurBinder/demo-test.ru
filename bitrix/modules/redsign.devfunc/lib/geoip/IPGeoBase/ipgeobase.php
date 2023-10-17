<?php

namespace Redsign\DevFunc\GeoIp;

class IPGeoBase
{
    /** @var resource|false $fhandleCIDR */
    private $fhandleCIDR;

    /** @var resource|false $fhandleCities */
    private $fhandleCities;

    private int $fSizeCIDR;
    // private int $fsizeCities;

    public function __construct(bool $isUTF8 = false, string $CIDRFile = '', string $CitiesFile = '')
    {
        if (!$CIDRFile)
            $CIDRFile = dirname(__FILE__) . '/cidr_optim.txt';

        if (!$CitiesFile) {
            if ($isUTF8)
                $CitiesFile = dirname(__FILE__) . '/cities_utf8.txt';
            else $CitiesFile = dirname(__FILE__) . '/cities.txt';
        }

        $this->fhandleCIDR = fopen($CIDRFile, 'r') or die("Cannot open $CIDRFile");
        $this->fhandleCities = fopen($CitiesFile, 'r') or die("Cannot open $CitiesFile");
        $this->fSizeCIDR = (int) filesize($CIDRFile);
        // $this->fsizeCities = (int) filesize($CitiesFile);
    }

    private function getCityByIdx(string $idx): ?array
    {
        if (!$this->fhandleCities)
            return null;

        rewind($this->fhandleCities);
        while (!feof($this->fhandleCities)) {
            $str = (string) fgets($this->fhandleCities);

            $arRecord = explode("\t", trim($str));
            if ($arRecord[0] == $idx) {
                return [
                    'city' => $arRecord[1],
                    'region' => $arRecord[2],
                    'district' => $arRecord[3],
                    'lat' => $arRecord[4],
                    'lng' => $arRecord[5]
                ];
            }
        }

        return null;
    }

    public function getRecord(string $ip): ?array
    {
        $ip = sprintf('%u', ip2long($ip));

        if (!$this->fhandleCIDR)
            return null;

        rewind($this->fhandleCIDR);
        $rad = (int) floor($this->fSizeCIDR / 2);
        $pos = $rad;
        while (fseek($this->fhandleCIDR, $pos, SEEK_SET) != -1) {
            if ($rad)
                $str = fgets($this->fhandleCIDR);
            else rewind($this->fhandleCIDR);

            $str = fgets($this->fhandleCIDR);

            if (!$str)
                return null;

            $arRecord = explode("\t", trim($str));
            $rad = (int) floor($rad / 2);

            if (!$rad && ($ip < $arRecord[0] || $ip > $arRecord[1]))
                return null;

            if ($ip < $arRecord[0]) {
                $pos -= $rad;
            } elseif ($ip > $arRecord[1]) {
                $pos += $rad;
            } else {
                $result = array('range' => $arRecord[2], 'cc' => $arRecord[3]);

                if ($arRecord[4] != '-' && $cityResult = $this->getCityByIdx($arRecord[4]))
                    $result += $cityResult;

                return $result;
            }
        }

        return null;
    }
}
