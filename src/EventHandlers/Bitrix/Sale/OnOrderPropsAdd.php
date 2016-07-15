<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Sale;

use Maximaster\Tools\Finder\OrderProperty;
use Maximaster\Tools\Events\BaseEvent;

class OnOrderPropsAdd extends BaseEvent
{
    public static function invalidateCache($id, $property)
    {
        (new OrderProperty())->invalidateCache();
    }
}