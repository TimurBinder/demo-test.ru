<?php

namespace Redsign\DevFunc\Iblock\CustomProperty;

use Bitrix\Iblock;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CustomFilter
{
    public const USER_TYPE = 'redsign_custom_filter';
    public const CONT_ID_PREFIX = 'custom_filter_';

    public static function getDescription(): array
    {
        return [
            'PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_STRING,
            'USER_TYPE' => self::USER_TYPE,
            'DESCRIPTION' => Loc::getMessage('RS_DF_IBLOCK_CUSTOMPROPERTY_CUSTOMFILTER_DESCRIPTION'),
            "GetPublicViewHTML" => [__CLASS__, "GetPublicViewHTML"],
            "GetPublicEditHTML" => [__CLASS__, "GetPublicEditHTML"],
            "GetAdminListViewHTML" => [__CLASS__, "GetAdminListViewHTML"],
            'GetPropertyFieldHtml' => [__CLASS__, 'getPropertyFieldHtml'],
            'ConvertToDB' => [__CLASS__, 'convertToDB'],
            'ConvertFromDB' => [__CLASS__, 'convertFromDB'],
            // "GetLength" => [__CLASS__, "GetLength"],
            // "PrepareSettings" => [__CLASS__, "PrepareSettings"],
            'GetSettingsHTML' => [__CLASS__, 'getSettingsHtml'],
            // "GetUIFilterProperty" => [__CLASS__, "GetUIFilterProperty"]
        ];
    }

    public static function GetPublicViewHTML(array $arProperty, array $arValue, array $arControlName): string
    {
        return '';
    }

    public static function GetAdminListViewHTML(array $arProperty, array $arValue, array $arControlName): string
    {
        return '';
    }

    public static function GetPublicEditHTML(array $arProperty, array $arValue, array $arControlName): string
    {
        return '';
    }

    public static function getPropertyFieldHtml(array $arProperty, array $arValue, array $arControlName): string
    {
        ob_start();

        if (Loader::includeModule('catalog')) {
            $sContId = self::CONT_ID_PREFIX . $arProperty['ID'];
            $conditions = $arValue['VALUE'];

            echo \CJSCore::Init(['redsign.devfunc.filter_conditions'], true);
            echo <<<EOL
            <script>
                (function() {
                    new RS.DevFunc.FilterConditions({
                        contId: '{$sContId}'
                    });
                }());
            </script>
EOL;

            $condTree = new \CGlobalCondTree();
            $condTree->Init(
                BT_COND_MODE_DEFAULT,
                BT_COND_BUILD_CATALOG,
                [
                    'FORM_NAME' => $arControlName['FORM_NAME'],
                    'PREFIX' => 'PROP[' . preg_replace("/([^a-z0-9])/is", "_", $arControlName['VALUE']) . ']',
                    'CONT_ID' => $sContId,
                    'JS_NAME' => $sContId,
                ]
            );

            $condTree->Show($conditions);

            echo '<div id="' . $sContId . '"></div>';
        }

        return ob_get_clean() ?: '';
    }

    public static function convertToDB(array $arProperty, array $arValue): string
    {
        if (!Loader::includeModule('catalog'))
            return '';

        $sContId = self::CONT_ID_PREFIX . $arProperty['ID'];

        $condTree = new \CGlobalCondTree();
        $condTree->Init(BT_COND_MODE_DEFAULT, BT_COND_BUILD_CATALOG, ['JS_NAME' => $sContId]);
        $conditions = $condTree->Parse($arValue['VALUE']);

        return is_array($conditions) && !empty($conditions['CHILDREN'])
            ? \Bitrix\Main\Web\Json::encode($conditions)
            : '';
    }

    public static function ConvertFromDB(array $arProperty, array $arValue): array
    {
        try {
            $arValue['VALUE'] = \Bitrix\Main\Web\Json::decode($arValue['VALUE'] ?: '');
        } catch (\Bitrix\Main\SystemException $ex) {
            $arValue['VALUE'] = [];
        }

        return $arValue;
    }

    public static function getSettingsHtml(array $arProperty, array $arControlName, array &$arPropertyFields): void
    {
        $arPropertyFields = [
            'HIDE' => [
                'MULTIPLE',
                'SEARCHABLE',
                'FILTRABLE',
                'WITH_DESCRIPTION',
                'MUTLIPLE_CNT',
                'SMART_FILTER',
                'ROW_COUNT',
                'COL_COUNT'
            ]
        ];
    }
}
