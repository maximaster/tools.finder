<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Main;

use Maximaster\Tools\Finder\UserGroup;
use Maximaster\Tools\Events\BaseEvent;

class OnAfterGroupAdd extends BaseEvent
{
    public static function invalidateCache($group)
    {
        (new UserGroup())->invalidateCache();
    }
}