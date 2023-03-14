<?php

namespace Maximaster\Tools\Finder;

use Bitrix\Main\GroupTable;

/**
 * Finder для групп пользователей
 * @package Maximaster\Tools\Finder
 */
class UserGroup extends AbstractFinder
{
    protected function requireModules()
    {
        return [];
    }

    protected function getAdditionalCachePath()
    {
        return '/user_groups';
    }

    /**
     * Получает группу пользователя по ее символьному коду
     * @param mixed ...$args единственный аргумент символьный код группы - строка
     * @return \Bitrix\Main\Entity\Query
     */
    protected function query(...$args)
    {
        list($stringId) = $args;

        $q = GroupTable::query()
            ->setSelect(['ID', 'STRING_ID']);

        $this->setQueryMetadata('STRING_ID', $stringId);

        return $q;
    }

    /**
     * @param mixed ...$keyParams
     * @return array
     */
    public static function get(...$keyParams)
    {
        list($stringId) = $keyParams;
        return parent::get($stringId);
    }

    /**
     * @param string $stringId
     *
     * @return int
     */
    public static function getId($stringId)
    {
        return self::get($stringId)['ID'];
    }
}