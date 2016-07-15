<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Catalog;

use Maximaster\Tools\Finder\PriceType;
use Maximaster\Tools\Events\BaseEvent;

class OnGroupDelete extends BaseEvent
{
    public static function invalidateCache($id)
    {
        (new PriceType())->invalidateCache();
    }
}