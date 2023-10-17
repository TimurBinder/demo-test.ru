<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Web\Uri;

class RSSeo
{
    public static function addMetaOG(): void
    {
        /** @var CMain $APPLICATION */
        global $APPLICATION;
        $arMetaPropName = [
            'og:type',
            'twitter:card',
            'og:site_name',
            'og:url',
            'og:title',
            'og:description',
            'og:image',
            'fb:admins',
            'fb:app_id',
        ];

        $Asset = Asset::getInstance();

        $arDirProps = $APPLICATION->GetDirPropertyList();
        $arPageProps = $APPLICATION->GetPagePropertyList();

        if ($arDirProps) {
            $arPageProps = array_merge($arDirProps, $arPageProps);
        }

        if (!isset($arPageProps['OG:TYPE'])) {
            $Asset->addString('<meta property="og:type" content="website">', true);
            $Asset->addString('<meta property="twitter:card" content="summary">', true);
        }

        foreach ($arMetaPropName as $sMetaPropName) {
            $key = mb_strtoupper($sMetaPropName);

            if (isset($arPageProps[$key])) {
                switch ($key) {
                    case 'OG:IMAGE':
                        if (!empty($arPageProps[$key])) {
                            $uri = new Uri($arPageProps[$key]);
                            $Asset->addString('<meta property="' . $sMetaPropName . '" content="' . $uri->toAbsolute()->getUri() . '">', true);
                        }
                        break;

                    default:
                        $Asset->addString('<meta property="' . $sMetaPropName . '" content="' . htmlspecialcharsbx($arPageProps[$key]) . '">', true);
                        break;
                }
            } else {
                switch ($key) {
                    case 'OG:SITE_NAME':
                        $dbSite = CSite::GetByID(SITE_ID);
                        if ($arSite = $dbSite->GetNext()) {
                            $Asset->addString('<meta property="' . $sMetaPropName . '" content="' . $arSite['SITE_NAME'] . '">', true);
                        }

                        break;

                    case 'OG:TITLE':
                        if (!empty($arPageProps['TITLE'])) {
                            $Asset->addString('<meta property="' . $sMetaPropName . '" content="' . $arPageProps['TITLE'] . '">', true);
                        } else {
                            $Asset->addString('<meta property="' . $sMetaPropName . '" content="' . $APPLICATION->GetTitle() . '">', true);
                        }
                        break;

                    case 'OG:DESCRIPTION':
                        if (!empty($arPageProps['DESCRIPTION'])) {
                            $Asset->addString('<meta property="' . $sMetaPropName . '" content="' . $arPageProps['DESCRIPTION'] . '">', true);
                        }
                        break;

                    case 'OG:URL':
                        if (!empty($arPageProps['CANONICAL'])) {
                            $Asset->addString('<meta property="' . $sMetaPropName . '" content="' . $arPageProps['CANONICAL'] . '">', true);
                        } else {
                            /** @var \Bitrix\Main\HttpRequest */
                            $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
                            $uriString = $request->getRequestUri();
                            $uri = new Uri($uriString);

                            $Asset->addString('<meta property="og:url" content="' . $uri->toAbsolute()->getUri() . '">', true);
                        }
                        break;

                    case 'FB:APP_ID':
                        $sFbApiId = Option::get('socialservices', 'facebook_appid', '');
                        if ($sFbApiId != '') {
                            $Asset->addString('<meta property="' . $sMetaPropName . '" content="' . $sFbApiId . '">', true);
                        }
                        break;

                    default:
                        break;
                }
            }
        }
    }
}
