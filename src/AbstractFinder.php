<?php

namespace Maximaster\Tools\Finder;

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Entity\Query;

/**
 * Базовый класс для Finder'ов, который реализовывает большую часть потребностей (кеш, выборка и т.д.)
 * @package Maximaster\Tools\Finder
 */
abstract class AbstractFinder
{
    /**
     * Возвращает путь для кеша конкретного файндера относительно значения, установленного в self::getBaseCachePath()
     * @return string
     */
    abstract protected function getAdditionalCachePath();

    /**
     * Возвращает перечень идентификаторов модулей, которые нужно подключиь
     * @return array
     */
    abstract protected function requireModules();

    /** @var array Хранилище для кешированных в памяти данных, чтобы не обращаться к файловому кешу дважды за хит */
    private $cached = [];

    /** @var array Хранилище инстансов для разных классов Finder'ов */
    private static $instanceStorage = [];

    /** @var mixed Имя ключевого поля для формирования массива с данными */
    private $queryKeyField;

    /** @var mixed Значение ключевого поля для получения данных из массива с кешем */
    private $queryKeyFieldValue;

    /** @var array Массив кусочков для формирования пути кеша */
    private $queryShards;

    /**
     * Устанавливает кеш в память
     * @param mixed $value кешируемое значение
     * @param array $keyParams Массив с параметрами, которые будут влиять на ключ сохранения кеша
     *
     * @return bool
     */
    private function setMemoryCache($value, array $keyParams = [])
    {
        $key = $this->getMemoryCacheKey($keyParams);
        $this->cached[ $key ] = $value;
        return isset($this->cached[ $key ]);
    }

    /**
     * Достает из памяти кешированное значение с помощью ключевых значений
     * @param array $keyParams
     *
     * @return mixed|null
     */
    private function getMemoryCache(array $keyParams = [])
    {
        $key = $this->getMemoryCacheKey($keyParams);
        return isset($this->cached[ $key ]) ? $this->cached[ $key ] : null;
    }

    /**
     * Генерирует ключ для кеша в памяти на основании ключевых значений и имени вызванного класса
     * @param array $keyParams
     *
     * @return string
     */
    private function getMemoryCacheKey(array $keyParams = [])
    {
        return get_called_class() . '_' . implode('_', $keyParams);
    }

    /**
     * Возвращает инстанс нужного класса
     * @return mixed
     */
    protected function getInstance()
    {
        $key = get_called_class();
        if (!isset(static::$instanceStorage[ $key ])) {
            static::$instanceStorage[ $key ] = new static();
        }

        return static::$instanceStorage[ $key ];
    }

    /**
     * Получает значение сущности из кеша. Данный метод необходимо вызывать непосредственно из дочерних классов
     * @param array $keyParams
     *
     * @return mixed|null
     */
    private function getCachedValue(array $keyParams = [])
    {
        $value = $this->getMemoryCache($keyParams);
        if ($value === null) {
            $q = call_user_func_array([$this, 'query'], $keyParams);
            $value = $this->getFromPermanentCache($q);
            $this->setMemoryCache($value, $keyParams);
        }

        return $value;
    }

    public function __construct()
    {
        array_map('\\Bitrix\\Main\\Loader::includeModule', $this->requireModules());
    }

    /**
     * Получает путь до папки с кешем файндеров относительно /bitrix/cache
     * @return string
     */
    private function getBaseCachePath()
    {
        return '/maximaster/tools.finder';
    }

    /**
     * Создает полный путь до кеша относительно папки с битриксовым кешем
     * @param array $shards Кусочки, которые могут быть добавлены к адресу кеша в конце
     *
     * @return string
     */
    private function getFullCachePath(array $shards = [])
    {
        $additionalCachePath = trim(trim($this->getAdditionalCachePath()), '/');

        if (strlen($additionalCachePath) === '') {
            throw new \LogicException('Необходимо определить адрес дополнительного кеша в методе getAdditionalCachePath()');
        }

        return trim($this->getBaseCachePath(), '/')
        . '/' . $additionalCachePath
        . '/' . implode('/', $shards);
    }

    /**
     * Метод, который необходимо переопределить в родителе. Этот метод является основным для генерации запроса на выборку
     *
     * @return \Bitrix\Main\Entity\Query
     */
    protected function query()
    {
        throw new \LogicException('Необходимо переопределить метод ' . __METHOD__ . ' в потомке для генерации запроса');
    }

    /**
     * Устанавливает мета-данные, которые будут использованы в запросе
     *
     * @param string $keyField Ключевое поле. Значение этого поля будет использовано для построения индекса в кеше запроса.
     * Если необходимо доставать значение из кеша по какому-то коду сущности (по коду инфоблока, например), то тут надо выставить название поля CODE для этой сущности
     * @param mixed $keyValue Значение ключевого поля. Это значение будет использоваться для получения значения из кеша.
     * Тут нужно передать то значение, по ключу которого доставать значение из кеша. Например - код инфоблока
     * @param array $shards Массив кусочков для построения адреса кеша
     */
    protected function setQueryMetadata($keyField = 'CODE', $keyValue = null, array $shards = [])
    {
        $this->queryKeyField = $keyField;
        $this->queryKeyFieldValue = $keyValue;
        $this->queryShards = $shards;
    }

    /**
     * Очищает мета-данные запроса
     */
    private function clearQueryMetaData()
    {
        $this->queryKeyField =
        $this->queryKeyFieldValue = null;
        $this->queryShards = [];
    }

    /**
     * Достает значение из постоянного хранилища в кеше.
     * @param Query $q Запрос на получение данных из БД
     *
     * @return array
     */
    private function getFromPermanentCache(Query $q)
    {
        $cache = Cache::createInstance();
        $keyField = $this->queryKeyField ? $this->queryKeyField : 'CODE';
        $shards = $this->queryShards ? $this->queryShards : [];
        $path = $this->getFullCachePath($shards);

        if ($cache->initCache(86400, null, $path)) {
            $items = $cache->getVars();
        } else {
            $cache->startDataCache();

            $items = $this->getItems($q, $keyField);

            if (!empty($items)) {
                $cache->endDataCache($items);
            } else {
                $cache->abortDataCache();
            }
        }

        $key = $this->queryKeyFieldValue;
        $this->clearQueryMetaData();

        return $items[ $key ];
    }

    /**
     * Метод можно переопределять в дочерних классах с целью изменения структуры хранимых в кеше данных. Задачей метода
     * является формирование массива, который будет положен в кеш
     * @param Query $q
     * @param mixed $keyField
     *
     * @return array
     */
    protected function getItems(Query $q, $keyField) {

        $items = [];
        $result = $q->exec();

        //Если в данных будет числовое поле ID, то будем приводить его к числу насильно
        $result->addFetchDataModifier(function(&$data){
            if (isset($data['ID']) && is_numeric($data['ID'])) {
                $data['ID'] = (int)$data['ID'];
            }
        });
        while ($item = $result->fetch()) {

            if (!isset($item[ $keyField ])) {
                continue;
            }

            $keyValue = $item[ $keyField ];
            $items[ $keyValue ] = $item;
        }

        return $items;
    }

    /**
     * Метод инвалидации кеша
     * @param array $shards Кусочки адреса кеша, которые были использованы при формировании кеша и запроса
     * (то, что устанавливается методом setQueryShards)
     */
    public function invalidateCache(array $shards = [])
    {
        $path = $this->getFullCachePath($shards);
        $cache = Cache::createInstance();
        $cache->cleanDir($path);
    }

    /**
     * Инициализирует выборку данных из кеша. В общем-то шорткат, проброшенный наружу
     *
     * @param array $keyParams
     *
     * @return mixed
     */
    public static function get(...$keyParams)
    {
        return static::getInstance()->getCachedValue($keyParams);
    }
}