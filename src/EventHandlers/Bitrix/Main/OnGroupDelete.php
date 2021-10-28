<?php

namespace Maximaster\Tools\Finder\EventHandlers\Bitrix\Main;

use Maximaster\Tools\Finder\UserGroup;

class OnGroupDelete
{
    public static function invalidateCache($id)
    {
        (new UserGroup())->invalidateCache();
    }
}