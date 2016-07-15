<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Iblock;

use Maximaster\Tools\Finder\IblockSection;
use Maximaster\Tools\Events\BaseEvent;

class OnAfterIBlockSectionAdd extends BaseEvent
{
    public static function invalidateCache($section)
    {
        (new IblockSection())->invalidateCache([$section['IBLOCK_ID']]);
    }
}