<?php

namespace Maximaster\Tools\Finder;

class SmartProcess extends AbstractFinder
{
    protected function getAdditionalCachePath()
    {
        return '/smartprocess';
    }

    protected function requireModules()
    {
        return [];
    }

    protected function query(...$args)
    {
        list($stringId) = $args;

        $q = GroupTable::query()
            ->setSelect(['ID', 'STRING_ID']);

        $this->setQueryMetadata('STRING_ID', $stringId);

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
}