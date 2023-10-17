<?php

namespace Redsign\LightBasket\Entity;

use Bitrix\Main\ORM;
use Bitrix\Main\ORM\Query\Result as QueryResult;

class BasketItemTable extends ORM\Data\DataManager
{
    public static function getTableName(): string
    {
        return 'redsign_basket_item';
    }

    public static function getMap(): array
    {
        return [
            new ORM\Fields\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new ORM\Fields\IntegerField('USER_ID', [
                'required' => true,
            ]),
            new ORM\Fields\IntegerField('ELEMENT_ID', [
                'required' => true,
            ]),
            new ORM\Fields\FloatField('PRICE', [
                'required' => true,
            ]),
            new ORM\Fields\FloatField('DISCOUNT'),
            new ORM\Fields\FloatField('QUANTITY', [
                'required' => true,
            ]),
            new ORM\Fields\StringField('CURRENCY', [
                'required' => true,
            ]),
            new ORM\Fields\Relations\Reference(
                'USER',
                '\Redsign\LightBasket\Entity\BasketUserTable',
                ['=this.USER_ID' => 'ref.ID']
            ),
        ];
    }

    public static function getForUser(int $userId): QueryResult
    {
        $result = self::getList([
            'filter' => [
                '=USER_ID' => $userId,
            ],
        ]);

        return $result;
    }

    public static function deleteForUser(int $userId): void
    {
        /** @var \Bitrix\Main\DB\Connection */
        $connection = \Bitrix\Main\Application::getConnection();
        $sqlHelper = $connection->getSqlHelper();
        $connection->queryExecute(
            'DELETE FROM ' . self::getTableName() .
            ' WHERE ' . $sqlHelper->quote('USER_ID') . ' = ' . $sqlHelper->forSql((string)$userId)
        );
    }
}
