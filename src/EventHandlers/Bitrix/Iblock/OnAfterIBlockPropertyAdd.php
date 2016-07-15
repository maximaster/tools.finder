<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Iblock;

use Maximaster\Tools\Finder\IblockProperty;
use Maximaster\Tools\Events\BaseEvent;

class OnAfterIBlockPropertyAdd extends BaseEvent
{
    public function invalidateCache($property)
    {
        if ($property['ID'] > 0) {
            (new IblockProperty())->invalidateCache([$property['IBLOCK_ID']]);
        }
    }
}