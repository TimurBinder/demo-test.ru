<?php

use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var string $DBType
 */

global $DBType;

Loc::loadMessages(__FILE__);

$arClasses = [
  'CRSFavorite' => 'classes/' . $DBType . '/favorite.php',
];

Loader::registerAutoLoadClasses(
    'redsign.favorite',
    $arClasses
);

$pathJS = getLocalPath('js/redsign/favorite');

CJSCore::RegisterExt('rs_favorite', array(
    'js' => $pathJS . '/favorite.js',
));

/**
 * @param int $IBLOCK_ID
 * @return int|false
 */
function RSFavoritePropCheck(int $IBLOCK_ID)
{
    if (!Loader::includeModule('iblock'))
        return false;

    $PropertyID = 0;
    $CODE = 'RSFAVORITE_COUNTER';

    if ($IBLOCK_ID > 0) {
        $propRes = CIBlockProperty::GetList(
            [
                'ID' => 'ASC'
            ],
            [
                'IBLOCK_ID' => $IBLOCK_ID,
                'CODE' => $CODE
            ]
        );

        if ($arProp = $propRes->GetNext()) {
            $PropertyID = $arProp['ID'];
        } else {
            $arFields = [
                'NAME' => Loc::getMessage('RS.FAVORITE.PROP_NAME'),
                'ACTIVE' => 'Y',
                'SORT' => '500',
                'CODE' => $CODE,
                'PROPERTY_TYPE' => 'N',
                'IBLOCK_ID' => $IBLOCK_ID,
                'WITH_DESCRIPTION' => 'N',
            ];
            $iblockproperty = new \CIBlockProperty();
            $PropertyID = $iblockproperty->Add($arFields);
        }
    }

    return $PropertyID;
}

/**
 * @param int $ELEMENT_ID
 * @return int|bool
 */
function RSFavoriteCounterIncDec(int $ELEMENT_ID)
{
    if (!Loader::includeModule('iblock') || !Loader::includeModule('sale'))
        return false;

    $res = false;
    $CODE = 'RSFAVORITE_COUNTER';

    if ($ELEMENT_ID < 0)
        return false;

    $res = CIBlockElement::GetByID($ELEMENT_ID);
    if ($arElement = $res->GetNext()) {
        $IBLOCK_ID = $arElement['IBLOCK_ID'];
        $dbProps = CIBlockElement::GetProperty(
            $IBLOCK_ID,
            $ELEMENT_ID,
            [
                'ID' => 'ASC'
            ],
            [
                'CODE' => $CODE
            ]
        );

        $VALUE = 0;
        if ($arProps = $dbProps->Fetch()) {
            $propID = $arProps['ID'];
            $res = true;
            $VALUE = $arProps['VALUE'];
        } else {
            $propID = RSFavoritePropCheck($IBLOCK_ID);
            $dbProps = CIBlockElement::GetProperty(
                $IBLOCK_ID,
                $ELEMENT_ID,
                [
                    'ID' => 'ASC'
                ],
                [
                    'ID' => $propID
                ]
            );

            if ($arProps = $dbProps->Fetch()) {
                $res = true;
                $VALUE = $arProps['VALUE'];
            }
        }

        if ($res) {
            $FUserID = CSaleBasket::GetBasketUserID();
            $dbRes = CRSFavorite::GetList(
                [],
                [
                    'FUSER_ID' => $FUserID,
                    'ELEMENT_ID' => $ELEMENT_ID
                ]
            );

            if ($arFields = $dbRes->Fetch()) {
                $res = 1;
                $VALUE--;
                CRSFavorite::Delete($arFields['ID']);
            } else {
                $res = 2;
                $VALUE++;
                $arFields = array(
                    'FUSER_ID' => $FUserID,
                    'ELEMENT_ID' => $ELEMENT_ID,
                    'PRODUCT_ID' => 0,
                );
                CRSFavorite::Add($arFields);
            }

            CIBlockElement::SetPropertyValueCode($ELEMENT_ID, $propID, $VALUE);
        }
    }

    return $res;
}

/**
 * @param int $ELEMENT_ID
 * @return int|bool
 */
function RSFavoriteAddDel(int $ELEMENT_ID)
{
    if (!Loader::includeModule('iblock'))
        return false;

    $res = false;
    if ($ELEMENT_ID < 1)
        return false;


    $res = CIBlockElement::GetByID($ELEMENT_ID);
    if ($arElement = $res->GetNext()) {
        $iUserId = false;
        if (Loader::includeModule('sale')) {
            //ShowError(GetMessage("RS_SALE_NOT_INSTALLED"));
            $iUserId = CSaleBasket::GetBasketUserID();
        } else {
            $user = CurrentUser::get();
            $iUserId = $user->getId();
        }

        if ($iUserId) {
            $dbRes = CRSFavorite::GetList(
                [],
                [
                    'FUSER_ID' => $iUserId,
                    'ELEMENT_ID' => $ELEMENT_ID
                ]
            );

            if ($arFields = $dbRes->Fetch()) {
                $res = 1;
                CRSFavorite::Delete($arFields['ID']);
            } else {
                $res = 2;
                $arFields = array(
                    'FUSER_ID' => $iUserId,
                    'ELEMENT_ID' => $ELEMENT_ID,
                    'PRODUCT_ID' => 0,
                );

                CRSFavorite::Add($arFields);
            }
        } else {
            if (is_array($_SESSION[CRSFavorite::SESSION_CODE]) && ($i = array_search($ELEMENT_ID, $_SESSION[CRSFavorite::SESSION_CODE])) !== false) {
                $res = 1;
                unset($_SESSION[CRSFavorite::SESSION_CODE][$i]);
            } else {
                $res = 2;
                $_SESSION[CRSFavorite::SESSION_CODE][] = $ELEMENT_ID;
            }
        }
    }

    return $res;
}
