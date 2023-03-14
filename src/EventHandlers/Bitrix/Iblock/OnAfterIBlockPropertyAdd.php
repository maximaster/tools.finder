<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Iblock;

use Maximaster\Tools\Finder\IblockProperty;

class OnAfterIBlockPropertyAdd
{
    public static function invalidateCache($property)
    {
        if ($property['ID'] > 0) {
            (new IblockProperty())->invalidateCache([$property['IBLOCK_ID']]);
        }
    }
}