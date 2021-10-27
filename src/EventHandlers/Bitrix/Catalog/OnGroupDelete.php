<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Catalog;

use Maximaster\Tools\Finder\PriceType;

class OnGroupDelete
{
    public static function invalidateCache($id)
    {
        (new PriceType())->invalidateCache();
    }
}