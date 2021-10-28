<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Main;

use Maximaster\Tools\Finder\UserGroup;

class OnAfterGroupUpdate
{
    public static function invalidateCache($group)
    {
        (new UserGroup())->invalidateCache();
    }
}