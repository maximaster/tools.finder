<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Sale;

use Maximaster\Tools\Finder\OrderProperty;

class OnOrderPropsAdd
{
    public static function invalidateCache($id, $property)
    {
        (new OrderProperty())->invalidateCache();
    }
}