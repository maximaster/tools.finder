<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Iblock;

use Maximaster\Tools\Finder\Iblock;
use Maximaster\Tools\Events\BaseEvent;

class OnAfterIBlockUpdate extends BaseEvent
{
    public static function invalidateCache($iblock)
    {
        if ($iblock['CODE'] && $iblock['IBLOCK_ID']) {
            (new Iblock())->invalidateCache([$iblock['IBLOCK_ID']]);
        }
    }
}