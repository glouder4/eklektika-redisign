# ST-08: Рефакторинг bootstrap init.php и requires.php

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Свести [`local/php_interface/init.php`](../../../local/php_interface/init.php) и [`local/classes/requires.php`](../../../local/classes/requires.php) к **тонким загрузчикам**: инфраструктура сайта (заголовки, cookie, тайминги) и последовательное подключение сегментов без бизнес-логики в одном файле.

## Описание работ

1. Вынести класс `PageSettings` и функции-хелперы `getPageEditorSettings` / `getPageSettingValue` из `init.php` в `local/classes/` (сегмент контента/лендинги) с сохранением API.
2. Перенести регистрацию поискового обработчика из [`requires.php`](../../../local/classes/requires.php) в явный bootstrap (или оставить в модуле поиска сегмента с комментарием зависимости от `local/php_interface/classes/handlers/search/stemming.php`).
3. После выполнения ST-03–ST-07 удалить из `init.php` перенесённые функции; оставить только: include `requires.php` / модульный аналог, вызовы `CatalogPriceFloor::bootstrap()` (или из сегмента pricing), инфраструктурные обработчики (`OnEpilog`, Server-Timing).
4. Документировать порядок подключения (чтобы события регистрировались в нужном порядке).

## Технические детали

- Компоненты/модули:
  - [`local/php_interface/init.php`](../../../local/php_interface/init.php)
  - [`local/classes/requires.php`](../../../local/classes/requires.php)
  - класс `PageSettings` сейчас inline в `init.php`
  - [`local/php_interface/classes/handlers/search/stemming.php`](../../../local/php_interface/classes/handlers/search/stemming.php) (путь из requires)
- Изменяемые файлы/области:
  - `local/php_interface/init.php`
  - `local/classes/requires.php`
  - новые файлы под PageSettings и общий Bootstrap

## Зависимости

- Блокируется:
  - ST-03–ST-07 (чтобы не переносить дважды)
- Блокирует:
  - чистую установку модулей в ST-09

## Критерии приёмки

- [ ] `init.php` не содержит REST-функций и тяжёлой доменной логики перенесённых сегментов
- [ ] `PageSettings` доступен из кода так же, как раньше (через автозагрузку или require из одного места)
- [ ] Поисковый `BeforeIndex` продолжает работать

## Проверка

- Unit/интеграционные проверки:
  - нет
- Ручной сценарий:
  - Открытие страницы с настройками из ИБ; полнотекстовый поиск smoke; регистрация пользователя (интеграция с сегментами)

## Документация

- Изученные документы:
  - `docs/features/local_classes_segments_and_modules.md`
- Что обновить:
  - раздел «Стратегия init.php» с фактическим порядком include
- Что создать (если нужно):
  - нет

## Статус

- planned
