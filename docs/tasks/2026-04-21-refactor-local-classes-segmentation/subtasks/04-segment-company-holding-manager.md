# ST-04: Сегмент компаний, холдингов и менеджеров

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Сгруппировать логику компаний (включая холдинговую модель и параметры вроде скидки через группы), менеджеров и пользовательских групп в одном доменном сегменте с чётким публичным API для шаблонов, `director/`, компонентов заказа и AJAX.

## Описание работ

1. Структурировать [`local/classes/site/Company.php`](../../../local/classes/site/Company.php), [`local/classes/site/Manager.php`](../../../local/classes/site/Manager.php), [`local/classes/site/UserGroups.php`](../../../local/classes/site/UserGroups.php): разнести по подпапкам или классам по обязанностям (чтение ИБ, синхронизация с B24, реквизиты), не меняя внешнее поведение.
2. Централизовать REST-вызовы компании/реквизитов через клиент из ST-02.
3. Обновить точки входа: [`local/classes/ajax.php`](../../../local/classes/ajax.php) (`UPDATE_COMPANY`, `DELETE_COMPANY`, `SYNC_COMPANY_CONTACTS`, `UPDATE_MANAGER`), [`director/person/add-new-person-action.php`](../../../director/person/add-new-person-action.php), использование в [`local/components/bitrix/sale.order.ajax/class.php`](../../../local/components/bitrix/sale.order.ajax/class.php) — только при необходимости смены namespace/импортов.
4. Зафиксировать контракт для ST-05: метод(ы) получения максимальной скидки пользователя по компании/группам (сейчас используется из `CatalogPriceFloor`).

## Технические детали

- Компоненты/модули:
  - [`local/classes/site/Company.php`](../../../local/classes/site/Company.php)
  - [`local/classes/site/Manager.php`](../../../local/classes/site/Manager.php)
  - [`local/classes/site/UserGroups.php`](../../../local/classes/site/UserGroups.php)
  - [`local/classes/ajax.php`](../../../local/classes/ajax.php) (часть)
  - [`director/person/add-new-person-action.php`](../../../director/person/add-new-person-action.php)
- Изменяемые файлы/области:
  - `local/classes/site/`
  - потребители в `local/components/`, `local/templates/` при смене namespace

## Зависимости

- Блокируется:
  - ST-02
- Блокирует:
  - ST-05 (контракт скидки)
  - финальный перенос в модуль в ST-09

## Критерии приёмки

- [ ] Операции создания/обновления/удаления компании и синхронизации контактов работают как до рефакторинга
- [ ] Холдинговые связи и поля B24_ID не ломаются (проверка по сценарию из `docs/features/company_system.md`)
- [ ] Публичный метод/интерфейс для «скидки компании» описан и используется в плане ST-05

## Проверка

- Unit/интеграционные проверки:
  - нет
- Ручной сценарий:
  - Редактирование компании в ЛК; создание контакта из `director/`; оформление заказа с подтягиванием профиля компании

## Документация

- Изученные документы:
  - `docs/features/company_system.md`
  - `docs/features/user_company_methods.md`
- Что обновить:
  - `docs/features/company_system.md` — структура классов и точки входа после сегментации
  - `docs/features/local_classes_segments_and_modules.md`
- Что создать (если нужно):
  - нет

## Статус

- planned
