<?php

namespace Maximaster\Tools\Finder;

use Bitrix\Iblock\PropertyEnumerationTable;

/**
 * Finder для значений типа "Список" свойств инфоблока
 * @package Maximaster\Tools\Finder
 */
class IblockPropertyEnum extends AbstractFinder
{
    protected function requireModules()
    {
        return ['iblock'];
    }
    
    protected function getAdditionalCachePath()
    {
        return '/iblock_property_enum';
    }

    /**
     * Получает идентификатор значения свойства типа "Список" по идентификатору свойства и XML_ID значения свойства
     *
     * @param mixed ...$args первый аргумент Id свойства - int, второй XML_ID свойства - строка
     * @return \Bitrix\Main\Entity\Query
     */
    protected function query(...$args)
    {
        list($propertyId, $enumCode) = $args;

        $q = PropertyEnumerationTable::query()
            ->addFilter('PROPERTY.ID', $propertyId)
            ->setSelect(['XML_ID', 'ID']);

        $this->setQueryMetadata('XML_ID', $enumCode, [$propertyId]);

        return $q;
    }

    /**
     * @param mixed ...$keyParams
     * @return array
     */
    public static function get(...$keyParams)
    {
        list($propertyId, $xmlId) = $keyParams;
        return parent::get($propertyId, $xmlId);
    }

    /**
     * @param int $propertyId
     * @param string $xmlId
     *
     * @return int
     */
    public static function getId($propertyId, $xmlId)
    {
        return self::get($propertyId, $xmlId)['ID'];
    }
}