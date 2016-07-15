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
     * @param string $name
     *
     * @return \Bitrix\Main\Entity\Query
     */
    protected function query($name)
    {
        $q = HighloadBlockTable::query()
            ->setSelect(['ID', 'TABLE_NAME', 'NAME']);

        $this->setQueryMetadata('NAME', $name);

        return $q;
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public static function get($name)
    {
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