<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Catalog;

use Maximaster\Tools\Finder\PriceType;

class OnGroupUpdate
{
    public static function invalidateCache($id, $group)
    {
        (new PriceType())->invalidateCache();
    }
}