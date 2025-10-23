<?php

namespace Maximaster\Tools\Finder;

use Bitrix\Iblock\PropertyTable;

/**
 * Finder для свойств инфоблока
 * @package Maximaster\Finder
 */
class IblockProperty extends AbstractFinder
{
    protected function requireModules()
    {
        return ['iblock'];
    }

    protected function getAdditionalCachePath()
    {
        return '/iblock_property';
    }
    /**
     * Получает свойство инфоблока по идентификатору инфоблока и коду свойства
     *
     * @param mixed ...$args первый аргумент Id ИБ - int, второй код ИБ - строка
     * @return \Bitrix\Main\ORM\Query\Query
     */
    protected function query(...$args)
    {
        list($iblockId, $code) = $args;

        $q = PropertyTable::query()
            ->addFilter('IBLOCK_ID', $iblockId)
            ->setSelect(['CODE', 'ID']);

        $this->setQueryMetadata('CODE', $code, [$iblockId]);

        return $q;
    }

    /**
     * @param mixed ...$keyParams
     * @return array
     */
    public static function get(...$keyParams)
    {
        list($iblockId, $code) = $keyParams;
        return parent::get($iblockId, $code);
    }

    /**
     * @param int $iblockId
     * @param string $code
     *
     * @return int
     */
    public static function getId($iblockId, $code)
    {
        return self::get($iblockId, $code)['ID'];
    }
}