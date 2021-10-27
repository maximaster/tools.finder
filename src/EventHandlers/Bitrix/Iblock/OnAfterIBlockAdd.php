<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Iblock;

use Maximaster\Tools\Finder\Iblock;

class OnAfterIBlockAdd
{
    public static function invalidateCache($iblock)
    {
        if ($iblock['ID'] > 0) {
            (new Iblock())->invalidateCache([$iblock['IBLOCK_TYPE_ID']]);
        }
    }
}