<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Iblock;

use Bitrix\Iblock\IblockTable;
use Maximaster\Tools\Finder\Iblock;

class OnBeforeIBlockDelete
{
    private static $deletedIblockId = [];

    public static function invalidateCache($id)
    {
        if (!static::$deletedIblockId) {
            $iblock = IblockTable::query()
                ->addFilter('ID', $id)
                ->addSelect('TYPE.ID', 'TYPE_ID')
                ->exec()->fetch();
            static::$deletedIblockId = $iblock['TYPE_ID'];
        }
        (new Iblock())->invalidateCache([static::$deletedIblockId ]);
    }
}