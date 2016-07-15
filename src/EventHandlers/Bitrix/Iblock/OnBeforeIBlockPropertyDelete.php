<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Iblock;

use Bitrix\Iblock\PropertyTable;
use Maximaster\Tools\Finder\IblockProperty;
use Maximaster\Tools\Events\BaseEvent;

class OnBeforeIBlockPropertyDelete extends BaseEvent
{
    private static $deletedIblockId = [];

    public static function invalidateCache($id)
    {
        if (!static::$deletedIblockId) {
            $property = PropertyTable::query()->addFilter('ID', $id)->setSelect('IBLOCK_ID')->exec()->fetch();
            static::$deletedIblockId = $property['IBLOCK_ID'];
        }

        (new IblockProperty())->invalidateCache([static::$deletedIblockId]);
    }
}