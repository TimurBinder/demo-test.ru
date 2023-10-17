<?php

namespace Redsign\LightBasket\Entity;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity;

class BasketItemTable extends DataManager
{
    public static function getTableName()
    {
        return 'redsign_basket_item';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
            )),
            new Entity\IntegerField('USER_ID', array(
                'required' => true,
            )),
            new Entity\IntegerField('ELEMENT_ID', array(
                'required' => true,
            )),
            new Entity\FloatField('PRICE', array(
                'required' => true,
            )),
            new Entity\FloatField('DISCOUNT'),
            new Entity\FloatField('QUANTITY', array(
                'required' => true,
            )),
            new Entity\StringField('CURRENCY', array(
                'required' => true,
            )),
            new Entity\ReferenceField(
                'USER',
                '\Redsign\LightBasket\Entity\BasketUserTable',
                array('=this.USER_ID' => 'ref.ID')
            ),
        );
    }

    public static function getForUser($userId)
    {
        $result = self::getList(array(
            'filter' => array(
                '=USER_ID' => $userId,
            ),
        ));

        return $result;
    }

    public static function deleteForUser($userId)
    {
        $connection = \Bitrix\Main\Application::getConnection();
        $sqlHelper = $connection->getSqlHelper();
        $query = $connection->queryExecute(
            'DELETE FROM '.self::getTableName().' WHERE '.$sqlHelper->quote('USER_ID').' = '.$sqlHelper->forSql($userId)
        );
    }
}
