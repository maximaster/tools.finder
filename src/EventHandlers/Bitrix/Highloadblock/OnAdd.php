<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Highloadblock;

use Maximaster\Tools\Finder\HlBlock;

class OnAdd
{
    public static function invalidateCache()
    {
        (new HlBlock())->invalidateCache();
    }
}