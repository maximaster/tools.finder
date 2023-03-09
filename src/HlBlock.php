<?php

namespace Maximaster\Tools\Finder;

use Bitrix\Highloadblock\HighloadBlockTable;

/**
 * Finder для Highload-блоков
 * @package Maximaster\Tools\Finder
 */
class HlBlock extends AbstractFinder
{
    protected function requireModules()
    {
        return ['highloadblock'];
    }
    
    protected function getAdditionalCachePath()
    {
        return '/highloadblock';
    }

    /**
     * Получает Highload-блок по его названию
     *
     * @param array<mixed> $args
     * @return \Bitrix\Main\Entity\Query
     */
    protected function query(...$args)
    {
        [$name] = $args;

        $q = HighloadBlockTable::query()
            ->setSelect(['ID', 'TABLE_NAME', 'NAME']);

        $this->setQueryMetadata('NAME', $name);

        return $q;
    }

    /**
     * @param array<mixed> $keyParams
     * @return array
     */
    public static function get(...$keyParams)
    {
        [$name] = $keyParams;
        return parent::get($name);
    }

    /**
     * @param string $name
     *
     * @return int
     */
    public static function getId($name)
    {
        return self::get($name)['ID'];
    }
}