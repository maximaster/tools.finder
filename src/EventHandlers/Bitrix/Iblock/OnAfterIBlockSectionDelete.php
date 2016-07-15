<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Iblock;

use Bitrix\Iblock\SectionTable;
use Maximaster\Tools\Finder\IblockSection;
use Maximaster\Tools\Events\BaseEvent;

class OnAfterIBlockSectionDelete extends BaseEvent
{
    private static $deletedIblockId = [];

    public static function invalidateCache($id)
    {
        if (!static::$deletedIblockId) {
            $section = SectionTable::query()
                ->addFilter('ID', $id)
                ->addSelect('IBLOCK_ID')
                ->exec()->fetch();

            static::$deletedIblockId = $section['IBLOCK_ID'];
        }

        (new IblockSection())->invalidateCache([static::$deletedIblockId]);
    }
}