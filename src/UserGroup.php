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
     * @param string $stringId
     *
     * @return \Bitrix\Main\Entity\Query
     */
    protected function query($stringId)
    {
        $q = GroupTable::query()
            ->setSelect(['ID', 'STRING_ID']);

        $this->setQueryMetadata('STRING_ID', $stringId);

        return $q;
    }

    /**
     * @param string $stringId
     *
     * @return array
     */
    public static function get($stringId)
    {
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