<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Iblock;

use Maximaster\Tools\Finder\IblockProperty;

class OnAfterIBlockPropertyUpdate
{
    public static function invalidateCache($property)
    {
        $prop = new IblockProperty();

        //Кеш свойства
        $prop->invalidateCache([$property['IBLOCK_ID']]);

        //Кеш значений свойства типа "Список"
        $prop->invalidateCache([$property['ID']]);
    }
}