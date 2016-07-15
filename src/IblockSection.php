<?php

namespace Maximaster\Tools\Finder;

use Bitrix\Iblock\SectionTable;

/**
 * Finder для разделов инфоблока
 * @package Maximaster\Tools\Finder
 */
class IblockSection extends AbstractFinder
{
    protected function requireModules()
    {
        return ['iblock'];
    }

    protected function getAdditionalCachePath()
    {
        return '/iblock_section';
    }

    /**
     * Получает раздел инфоблока по идентификатору инфоблока и коду раздела
     *
     * @param int $iblockId
     * @param string $sectionCode
     *
     * @return \Bitrix\Main\Entity\Query
     */
    protected function query($iblockId, $sectionCode)
    {
        $q = SectionTable::query()
            ->addFilter('IBLOCK_ID', $iblockId)
            ->setSelect(['ID', 'CODE']);

        $this->setQueryMetadata('CODE', $sectionCode, [$iblockId]);

        return $q;
    }

    /**
     * @param int $iblockId
     * @param string $sectionCode
     *
     * @return array
     */
    public static function get($iblockId, $sectionCode)
    {
        return parent::get($iblockId, $sectionCode);
    }

    /**
     * @param int $iblockId
     * @param string $sectionCode
     *
     * @return int
     */
    public static function getId($iblockId, $sectionCode)
    {
        return self::get($iblockId, $sectionCode)['ID'];
    }

}