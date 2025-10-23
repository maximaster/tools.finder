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
     * @param mixed ...$args первый аргумент тип плательщика - int, второй код свойства - строка
     * @return \Bitrix\Main\ORM\Query\Query
     */
    protected function query(...$args)
    {
        list($personTypeId, $propertyCode) = $args;

        $q = OrderPropsTable::query()
            ->addFilter('PERSON_TYPE_ID', $personTypeId)
            ->setSelect(['PERSON_TYPE_ID', 'ID', 'CODE']);

        $this->setQueryMetadata('CODE', $propertyCode, [$personTypeId]);

        return $q;
    }

    /**
     * @param mixed ...$keyParams
     * @return array
     */
    public static function get(...$keyParams)
    {
        list($personTypeId, $propertyCode) = $keyParams;
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
        return self::get($personTypeId, $propertyCode)['ID'];
    }

}
