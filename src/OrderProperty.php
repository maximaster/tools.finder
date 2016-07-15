<?php

namespace Maximaster\Tools\Finder;

use Bitrix\Sale\Internals\OrderPropsTable;

/**
 * Finder для свойств заказа
 * @package Maximaster\Tools\Finder
 */
class OrderProperty extends AbstractFinder
{
    protected function getAdditionalCachePath()
    {
        return '/order_property';
    }

    protected function requireModules()
    {
        return ['sale'];
    }

    /**
     * Получает свойство заказа по идентификатору типа плательщика и коду свойства
     *
     * @param int $personTypeId
     * @param string $propertyCode
     *
     * @return \Bitrix\Main\Entity\Query
     */
    protected function query($personTypeId, $propertyCode)
    {
        $q = OrderPropsTable::query()
            ->addFilter('PERSON_TYPE_ID', $personTypeId)
            ->setSelect(['PERSON_TYPE_ID', 'ID', 'CODE']);

        $this->setQueryMetadata('CODE', $propertyCode, [$personTypeId]);

        return $q;
    }

    /**
     * @param int $personTypeId
     * @param string $propertyCode
     *
     * @return array
     */
    public static function get($personTypeId, $propertyCode)
    {
        return parent::get($personTypeId, $propertyCode);
    }

    /**
     * @param int $personTypeId
     * @param string $propertyCode
     *
     * @return int
     */
    public static function getId($personTypeId, $propertyCode)
    {
        return self::get($personTypeId, $propertyCode);
    }

}