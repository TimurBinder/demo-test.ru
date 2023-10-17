<?php

namespace Redsign\LightBasket\Entity;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity;

class BasketUserTable extends DataManager
{
    public static function getTableName()
    {
        return 'redsign_basket_user';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
            )),
            new Entity\StringField('CODE', array(
                'required' => true,
            )),
        );
    }

    public static function getRowByCode($code)
    {
        $result = self::getList(array(
            'filter' => array(
                '=CODE' => $code,
            ),
            'limit' => 1,
        ));

        return $result->fetch();
    }
}
