<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Sale;

use Maximaster\Tools\Finder\OrderProperty;

class OnOrderPropsDelete
{
    public static function invalidateCache()
    {
        (new OrderProperty())->invalidateCache();
    }
}