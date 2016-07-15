<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Main;

use Maximaster\Tools\Finder\UserGroup;
use Maximaster\Tools\Events\BaseEvent;

class OnGroupDelete extends BaseEvent
{
    public static function invalidateCache($id)
    {
        (new UserGroup())->invalidateCache();
    }
}