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
     * @param string $type
     * @param string $code
     * 
     * @return \Bitrix\Main\Entity\Query
     */
    protected function query($type, $code)
    {
        $q = IblockTable::query()
            ->addFilter('TYPE.ID', $type)
            ->setSelect(['CODE', 'ID']);

        $this->setQueryMetadata('CODE', $code, [$type]);

        return $q;
    }

    /**
     * @param string $type
     * @param string $code
     *
     * @return array
     */
    public static function get($type, $code)
    {
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