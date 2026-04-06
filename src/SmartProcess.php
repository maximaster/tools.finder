<?php

namespace Maximaster\Tools\Finder;

use Bitrix\Crm\Model\Dynamic\TypeTable;

class SmartProcess extends AbstractFinder
{
    protected function requireModules()
    {
        return ['crm'];
    }

    protected function getAdditionalCachePath()
    {
        return '/smartprocess';
    }

    /**
     * Получает смарт-процесс по его названию
     *
     * @param mixed ...$args единственный аргумент название смарт-процесса в виде строки
     * @return \Bitrix\Main\ORM\Query\Query
     */
    protected function query(...$args)
    {
        list($stringId) = $args;

        $q = TypeTable::query()
            ->setSelect(['ID', 'NAME', 'ENTITY_TYPE_ID']);

        $this->setQueryMetadata('NAME', $stringId);

        return $q;
    }

    /**
     * @param mixed ...$keyParams
     * @return array
     */
    public static function get(...$keyParams)
    {
        list($stringId) = $keyParams;
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
    /**
     * @param string $stringId
     *
     * @return string
     */
    public static function getEntityId($stringId)
    {
        return self::get($stringId)['ENTITY_TYPE_ID'];
    }
}
