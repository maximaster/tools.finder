# maximaster/tools.finder

Библиотека предоставляет функционал для выборки из БД идентификаторов различных сущностей Битрикс. Это необходимо, чтобы отвязать свой код от хардкода идентификаторов.

Библиотека состоит глобально из 3х частей:

+ Абстрактный класс для всех Finder'ов
+ Реализация Finder'ов для следующих сущностей:
  + Инфоблок
  + Свойство инфоблока
  + Значение свойства типа "Список" инфоблока
  + Раздел инфоблока
  + Highload-блок
  + Группа пользователей
  + Тип цены
  + Свойство заказа
  + Смарт-процесс CRM
+ Обработчики событий на CRUD-операции с сущностями для инвалидации кеша

## Пример использования

Основными входными точками являются методы `get()` и `getId()`. У каждой реализации Finder'а они имеют разный набор параметров (см. phpDoc).

Например, для инфоблока нужно передать тип и символьный код:

```php
\Maximaster\Tools\Finder\Iblock::get('catalog', 'products');
```

Для раздела инфоблока — идентификатор инфоблока и символьный код раздела:

```php
\Maximaster\Tools\Finder\IblockSection::get(
    \Maximaster\Tools\Finder\Iblock::getId('catalog', 'products'),
    'section_code'
);
```

`get()` возвращает массив с данными сущности, `getId()` — только числовой идентификатор.

### Все доступные Finder'ы

| Класс | Сущность | Параметры `get()` |
|---|---|---|
| `Iblock` | Инфоблок | `(string $type, string $code)` |
| `IblockProperty` | Свойство инфоблока | `(int $iblockId, string $code)` |
| `IblockPropertyEnum` | Значение свойства типа "Список" | `(int $propertyId, string $enumCode)` |
| `IblockSection` | Раздел инфоблока | `(int $iblockId, string $sectionCode)` |
| `HlBlock` | Highload-блок | `(string $name)` |
| `UserGroup` | Группа пользователей | `(string $stringId)` |
| `PriceType` | Тип цены | `(string $name)` |
| `OrderProperty` | Свойство заказа | `(int $personTypeId, string $propertyCode)` |
| `SmartProcess` | Смарт-процесс CRM | `(string $name)` |

Для `SmartProcess` дополнительно доступен метод `getEntityId(string $name): int`, возвращающий `ENTITY_TYPE_ID`.

### Шорткаты

Для удобства имеет смысл создать собственные шорткаты, унаследовавшись от реализаций Finder'ов:

```php
namespace Vendor\Finder;

class Iblock extends \Maximaster\Tools\Finder\Iblock
{
    public static function catalog(): array
    {
        return static::get('catalog', 'products');
    }
}
```

## Кеширование

Библиотека использует двухуровневый кеш:

1. **In-memory кеш** — хранит данные в статическом массиве в рамках текущего запроса (предотвращает повторное чтение диска)
2. **Файловый кеш** — стандартный кеш Битрикс, TTL 24 часа

Кеш инвалидируется автоматически через обработчики событий при изменении сущностей.

## Реализация собственных Finder'ов

Необходимо унаследоваться от `AbstractFinder` и реализовать:

### Обязательные абстрактные методы

```php
protected function getAdditionalCachePath(): string
```
Возвращает строку-суффикс пути кеша. Например: `/iblock`, `/highloadblock`.

```php
protected function requireModules(): array
```
Возвращает массив модулей Битрикс для загрузки. Например: `['iblock']`, `['sale']`. Можно вернуть пустой массив.

```php
protected function query(...$args): \Bitrix\Main\ORM\Query\Query
```
Формирует ORM-запрос и вызывает `setQueryMetadata()`. Важно: запрос должен выбирать широкий набор сущностей (например, все инфоблоки одного типа), а не одну конкретную. Данные индексируются и кешируются целиком, нужный элемент извлекается из кеша.

### Мета-информация запроса

Внутри `query()` необходимо вызвать:

```php
$this->setQueryMetadata(
    'CODE',        // поле, по которому индексируется кеш
    $code,         // значение для выборки из кеша
    [$iblockId]    // shard-параметры кеша (влияют на путь кеша)
);
```

### Дополнительные возможности

- Можно переопределить `getItems(Query $q, string $keyField): array` для кастомизации структуры кешируемых данных (пример: `PriceType` добавляет специальную запись для базовой цены)
- Рекомендуется переопределить `get()` и `getId()` с явными параметрами для корректной работы автодополнения в IDE

### Инвалидация кеша

Необходимо создать обработчики событий `OnAfterAdd`, `OnAfterUpdate`, `OnBeforeDelete` (или аналоги для конкретного модуля), которые вызывают `AbstractFinder::invalidateCache()`:

```php
use Maximaster\Tools\Finder\Iblock;

class OnAfterIBlockUpdate
{
    public static function handle(array $fields): void
    {
        Iblock::getInstance()->invalidateCache([$fields['IBLOCK_TYPE_ID']]);
    }
}
```

Аргумент `$shards` в `invalidateCache()` должен совпадать с `$shards` в `setQueryMetadata()`. Все обработчики событий библиотеки подключаются через `bootstrap.php` с помощью `EventManager::addEventHandlerCompatible()`.

За примерами см. классы в `src/EventHandlers/`.

## Обработчики событий в поставке

| Модуль | События |
|---|---|
| `iblock` | `OnAfterIBlockAdd`, `OnAfterIBlockUpdate`, `OnBeforeIBlockDelete` |
| `iblock` | `OnAfterIBlockPropertyAdd`, `OnAfterIBlockPropertyUpdate`, `OnBeforeIBlockPropertyDelete` |
| `iblock` | `OnAfterIBlockSectionAdd`, `OnAfterIBlockSectionUpdate`, `OnAfterIBlockSectionDelete` |
| `main` | `OnAfterGroupAdd`, `OnAfterGroupUpdate`, `OnGroupDelete` |
| `catalog` | `OnGroupAdd`, `OnGroupUpdate`, `OnGroupDelete` |
| `sale` | `OnOrderPropsAdd`, `OnOrderPropsUpdate`, `OnOrderPropsDelete` |
| `highloadblock` | `OnAdd`, `OnUpdate`, `OnDelete` |
| `crm` | `OnAfterAdd`, `OnAfterUpdate`, `OnAfterDelete` (Dynamic Type) |

## WTF. Почему статика?

Потому что Битрикс — статический синглтоновый глобальный монстр. Как только он даст возможность работать хотя бы в режиме `ServiceLocator`, тогда и поговорим.
