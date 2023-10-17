<?php

namespace Redsign\Lightbasket;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Text\Encoding;
use Bitrix\Main\Web\HttpClient;

class Core
{
    public const MODULE_ID = 'redsign.lightbasket';

    /**
     * @param array $arParams
     * @return string|bool
     */
    public static function registerInstallation(array $arParams = [])
    {
        if (!Loader::includeModule('main'))
            return false;

        $documentRoot = \Bitrix\Main\Application::getDocumentRoot();
        $defaultParams = [
            'action' => 'devfunc_called',
            'devfunc-action' => 'install',
            'mp_code' => [ self::MODULE_ID ],
            'bitrix_name' => '',
            'bitrix_active_from' => '',
            'bitrix_active_to' => '',
            'bitrix_key_hash' => '',
            'bitrix_version' => SM_VERSION,
            'bitrix_edition' => '',
            'site_name' => Encoding::convertEncoding(
                Option::get('main', 'site_name'),
                SITE_CHARSET,
                'windows-1251'
            ),
            'site_url' => Encoding::convertEncoding(
                Option::get('main', 'server_name'),
                SITE_CHARSET,
                'windows-1251'
            ),
            'site_default_email' => Encoding::convertEncoding(
                Option::get('main', 'email_from'),
                SITE_CHARSET,
                'windows-1251'
            ),
            'server_ip' => ($_SERVER['HTTP_X_REAL_IP'] ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['SERVER_ADDR']),
            'server_host' => $_SERVER['HTTP_HOST'],
        ];

        require_once($documentRoot . '/bitrix/modules/main/classes/general/update_client.php');


        $arUpdateList = \CUpdateClient::GetUpdatesList($errorMessage, 'ru', 'Y');
        if (array_key_exists('CLIENT', $arUpdateList) && !empty($arUpdateList['CLIENT'][0]['@']['LICENSE'])) {
            $defaultParams['bitrix_name'] = $arUpdateList['CLIENT'][0]['@']['NAME'];
            $defaultParams['bitrix_active_from'] = $arUpdateList['CLIENT'][0]['@']['DATE_FROM'];
            $defaultParams['bitrix_active_to'] = $arUpdateList['CLIENT'][0]['@']['DATE_TO'];
            $defaultParams['bitrix_edition'] = $arUpdateList['CLIENT'][0]['@']['LICENSE'];
        }

        $url = 'https://portal.redsign.ru/mp_clients/';
        $bitrixKey = \CUpdateClient::GetLicenseKey();
        $defaultParams['bitrix_key_hash'] = md5('BITRIX' . $bitrixKey . 'LICENCE');

        foreach ($defaultParams as $key => $value) {
            if (!array_key_exists($key, $arParams)) {
                $arParams[$key] = $value;
            }
        }

        $firstMpCode = reset($arParams['mp_code']);
        $arParams['mp_code'] = serialize($arParams['mp_code']);

        if (empty($arParams['module_version'])) {
            if ($info = \CModule::CreateModuleObject($firstMpCode)) {
                $arParams['module_version'] = $info->MODULE_VERSION . ' (' . SM_VERSION . ')';
            }
        }

        $httpClient = new HttpClient();
        $response = $httpClient->post($url, $arParams, true);

        return $response;
    }
}
