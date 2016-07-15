<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Catalog;

use Maximaster\Tools\Finder\PriceType;
use Maximaster\Tools\Events\BaseEvent;

class OnGroupUpdate extends BaseEvent
{
    public static function invalidateCache($id, $group)
    {
        (new PriceType())->invalidateCache();
    }
}