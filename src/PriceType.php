<?php

namespace Maximaster\Tools\Finder;

use Bitrix\Catalog\GroupTable;
use Bitrix\Main\Entity\Query;

/**
 * Finder для типов цен
 * @package Maximaster\Tools\Finder
 */
class PriceType extends AbstractFinder
{
    protected function requireModules()
    {
        return ['catalog'];
    }

    protected function getAdditionalCachePath()
    {
        return '/catalog_group';
    }

    /**
     * Получает тип цены по его названию
     * @param array<mixed> $args
     * @return \Bitrix\Main\Entity\Query
     */
    protected function query(...$args)
    {
        [$name] = $args;

        $q = GroupTable::query()
            ->setSelect(['ID', 'NAME', 'BASE']);

        $this->setQueryMetadata('NAME', $name);

        return $q;
    }

    /**
     * {@inheritdoc}
     *
     * <b>Дополнение</b>. Для базовой цены значение будет продублировано в массиве с ключом FAKE_KEY_BASE_PRICE
     */
    protected function getItems(Query $q, $keyField)
    {
        $items = parent::getItems($q, $keyField);
        $basePrice = [];
        foreach ($items as $price) {
            if ($price['BASE'] === 'Y') {
                $basePrice = $price;
                break;
            }
        }

        $items['FAKE_KEY_BASE_PRICE'] = $basePrice;

        return $items;
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

    /**
     * Возвращает идентификатор базового типа цены
     *
     * @return int
     */
    public static function base()
    {
        return self::getId('FAKE_KEY_BASE_PRICE');
    }
}