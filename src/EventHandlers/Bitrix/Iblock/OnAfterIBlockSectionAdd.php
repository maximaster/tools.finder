<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Iblock;

use Maximaster\Tools\Finder\IblockSection;

class OnAfterIBlockSectionAdd
{
    public static function invalidateCache($section)
    {
        (new IblockSection())->invalidateCache([$section['IBLOCK_ID']]);
    }
}