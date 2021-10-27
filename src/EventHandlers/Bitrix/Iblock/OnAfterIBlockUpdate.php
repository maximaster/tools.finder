<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Iblock;

use Maximaster\Tools\Finder\Iblock;

class OnAfterIBlockUpdate
{
    public static function invalidateCache($iblock)
    {
        if ($iblock['CODE'] && $iblock['IBLOCK_ID']) {
            (new Iblock())->invalidateCache([$iblock['IBLOCK_ID']]);
        }
    }
}