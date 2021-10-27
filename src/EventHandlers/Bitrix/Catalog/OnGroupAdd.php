<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Catalog;

use Maximaster\Tools\Finder\PriceType;

class OnGroupAdd
{
    public static function invalidateCache($id, $group)
    {
        (new PriceType())->invalidateCache();
    }
}