<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Crm;

use Maximaster\Tools\Finder\SmartProcess;

class OnCrmDynamicTypeAdd
{
    public static function invalidateCache($event)
    {
        (new SmartProcess())->invalidateCache();
    }
}
