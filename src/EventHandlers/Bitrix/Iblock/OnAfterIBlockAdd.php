<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Iblock;

use Maximaster\Tools\Finder\Iblock;
use Maximaster\Tools\Events\BaseEvent;

class OnAfterIBlockAdd extends BaseEvent
{
    public static function invalidateCache($iblock)
    {
        if ($iblock['ID'] > 0) {
            (new Iblock())->invalidateCache([$iblock['IBLOCK_TYPE_ID']]);
        }
    }
}