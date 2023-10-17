<?php

namespace Redsign\LightBasket\Entity;

use Bitrix\Main\ORM;

class BasketUserTable extends ORM\Data\DataManager
{
    public static function getTableName(): string
    {
        return 'redsign_basket_user';
    }

    public static function getMap(): array
    {
        return [
            new ORM\Fields\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new ORM\Fields\StringField('CODE', [
                'required' => true,
            ]),
        ];
    }

    public static function getRowByCode(string $code): ?array
    {
        $result = self::getList([
            'filter' => [
                '=CODE' => $code,
            ],
            'limit' => 1,
        ]);

        return $result->fetch() ?: null;
    }
}
