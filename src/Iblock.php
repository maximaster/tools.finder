<?php

namespace Maximaster\Tools\Finder;

use Bitrix\Iblock\IblockTable;

/**
 * Finder для инфоблоков
 * @package Maximaster\Finder
 */
class Iblock extends AbstractFinder
{
    protected function requireModules()
    {
        return ['iblock'];
    }

    protected function getAdditionalCachePath()
    {
        return '/iblock';
    }

    /**
     * Получает инфоблок по его коду и типу
     *
     * @param mixed ...$args первый аргумент тип ИБ - строка, второй код ИБ, также строка
     * @return \Bitrix\Main\Entity\Query
     */
    protected function query(...$args)
    {
        list($type, $code) = $args;

        $q = IblockTable::query()
            ->addFilter('TYPE.ID', $type)
            ->setSelect(['CODE', 'ID']);

        $this->setQueryMetadata('CODE', $code, [$type]);

        return $q;
    }

    /**
     * @param mixed ...$keyParams
     * @return array
     */
    public static function get(...$keyParams)
    {
        list($type, $code) = $keyParams;
        return parent::get($type, $code);
    }

    /**
     * @param string $type
     * @param string $code
     *
     * @return int
     */
    public static function getId($type, $code)
    {
        return self::get($type, $code)['ID'];
    }
}