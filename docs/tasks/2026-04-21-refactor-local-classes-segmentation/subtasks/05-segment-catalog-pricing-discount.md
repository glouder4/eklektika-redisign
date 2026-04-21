# ST-05: Сегмент ценообразования и скидки компании

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Выделить домен ценообразования ([`local/classes/site/CatalogPriceFloor.php`](../../../local/classes/site/CatalogPriceFloor.php)) в отдельный сегмент с минимальными зависимостями от CRM-транспорта и чёткой зависимостью от контракта «скидка компании», описанного в ST-04.

## Описание работ

1. Перенести класс и связанные константы/хелперы в структуру папок сегмента (например, `local/classes/catalog/pricing/` или модуль `eklektika.catalog.pricing` на этапе ST-09).
2. Заменить прямые обращения к `Company` внутри ценообразования на узкий интерфейс/статический фасад из ST-04 (`getMaxCompanyDiscountPercentForUserGroups` или аналог).
3. Сохранить регистрацию событий `catalog`/`sale` из [`CatalogPriceFloor::bootstrap()`](../../../local/classes/site/CatalogPriceFloor.php) и вызов из [`local/php_interface/init.php`](../../../local/php_interface/init.php); перенести регистрацию в bootstrap сегмента.
4. Инвентаризация всех использований в шаблонах ([`local/templates/universe_s1/`](../../../local/templates/universe_s1/), дубликаты в `bitrix/templates/`) — обновить namespace только при необходимости; составить чек-лист регрессии витрины и корзины.

## Технические детали

- Компоненты/модули:
  - [`local/classes/site/CatalogPriceFloor.php`](../../../local/classes/site/CatalogPriceFloor.php)
  - [`local/php_interface/init.php`](../../../local/php_interface/init.php) — `CatalogPriceFloor::bootstrap()`
  - шаблоны каталога/корзины с `class_exists(\OnlineService\Site\CatalogPriceFloor::class)`
- Изменяемые файлы/области:
  - `local/classes/` (новая подпапка сегмента)
  - `local/php_interface/init.php`
  - при смене имени класса — шаблоны в `local/templates/`

## Зависимости

- Блокируется:
  - ST-04 (контракт скидки)
- Блокирует:
  - нет (высокий риск — выполнять после стабилизации зависимостей)

## Критерии приёмки

- [ ] Цены на карточке товара, списке и в корзине совпадают с эталоном на выборке товаров (ручная сверка до/после)
- [ ] Скидка, зависящая от групп компании, применяется как раньше
- [ ] Нет циклических зависимостей между сегментом pricing и CRM user-sync

## Проверка

- Unit/интеграционные проверки:
  - при наличии — расчёт цены для фикстуры товара/пользователя
- Ручной сценарий:
  - Каталог (несколько типов цен), корзина, мини-корзина, оформление заказа с пользователем с корпоративной скидкой

## Документация

- Изученные документы:
  - `docs/features/company_system.md` (связь скидки и компании)
- Что обновить:
  - новый раздел в `docs/features/local_classes_segments_and_modules.md` про ценообразование
  - при необходимости — отдельный файл `docs/features/catalog_pricing_floor.md` (если решено выделить объёмную документацию)
- Что создать (если нужно):
  - `docs/features/catalog_pricing_floor.md` — по решению исполнителя при росте объёма

## Статус

- planned
