<?php

namespace Redsign\DevFunc\Iblock\Template\Functions;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Loader;
use Redsign\DevFunc\Sale\Location\Region as DevfuncRegion;

class Region extends \Bitrix\Iblock\Template\Functions\FunctionBase
{
    public static function eventHandler(Event $event): ?EventResult
    {
        $parameters = $event->getParameters();
        $functionName = $parameters[0];

        if ($functionName === 'region') {
            return new EventResult(
                EventResult::SUCCESS,
                '\Redsign\DevFunc\Iblock\Template\Functions\Region'
            );
        }

        return null;
    }

    public function calculate(array $parameters): ?string
    {
        if (!Loader::includeModule('redsign.devfunc'))
            return null;

        if (!DevfuncRegion::isUseRegionality())
            return null;

        $arRegion = DevfuncRegion::getCurrentRegion();
        $sResult = $arRegion['NAME'];

        return $sResult;
    }
}
