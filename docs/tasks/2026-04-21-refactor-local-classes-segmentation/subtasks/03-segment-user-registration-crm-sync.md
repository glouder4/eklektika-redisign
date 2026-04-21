# ST-03: Сегмент синхронизации пользователя с CRM

## Связь с задачей

- Родительская задача: [TASK-2026-04-21-refactor-local-classes-segmentation](../README.md)
- Внешние ссылки:
  - нет

## Цель подзадачи

Изолировать логику «пользователь сайта ↔ контакт Bitrix24» в отдельный сегмент (папка/namespace, в перспективе модуль `eklektika.b24.usersync`), отделив её от компаний и ценообразования.

## Описание работ

1. Перенести регистрацию обработчиков из [`local/classes/events.php`](../../../local/classes/events.php) в bootstrap сегмента (класс `ServiceProvider` или аналог), сохранив те же события: `OnBeforeUserDelete`, `OnBeforeUserRegister`, `OnAfterUserRegister`, `OnAfterUserUpdate`.
2. Сфокусировать код в [`local/classes/b24/RegisterUserCompany.php`](../../../local/classes/b24/RegisterUserCompany.php) и [`local/classes/b24/User.php`](../../../local/classes/b24/User.php) под ответственность сегмента; убрать лишние связи с не относящимися классами, если найдены.
3. Обновить [`local/classes/ajax.php`](../../../local/classes/ajax.php) действиями `UPDATE_CONTACT`, `UPDATE_BATCH_USERS`, `DELETE_CONTACT` — только через публичный фасад сегмента (без изменения контракта ответа для фронта без необходимости).
4. Убедиться, что [`sendRequest`](../../../local/php_interface/init.php) / новый клиент из ST-02 используется единообразно.

## Технические детали

- Компоненты/модули:
  - [`local/classes/events.php`](../../../local/classes/events.php)
  - [`local/classes/b24/RegisterUserCompany.php`](../../../local/classes/b24/RegisterUserCompany.php)
  - [`local/classes/b24/User.php`](../../../local/classes/b24/User.php)
  - [`local/classes/ajax.php`](../../../local/classes/ajax.php) (часть экшенов)
- Изменяемые файлы/области:
  - `local/classes/b24/`
  - `local/classes/events.php` или его замена подключаемым файлом сегмента

## Зависимости

- Блокируется:
  - ST-02 (транспорт REST)
- Блокирует:
  - полную модуляризацию в ST-09 для домена пользователя

## Критерии приёмки

- [ ] Обработчики событий `main` для пользователя зарегистрированы из одного места сегмента
- [ ] Сценарии: регистрация нового пользователя, обновление профиля, удаление пользователя — сохраняют прежнее поведение синхронизации с CRM
- [ ] В документации отражено, какие именно REST-методы использует сегмент (ссылка на обновлённый раздел)

## Проверка

- Unit/интеграционные проверки:
  - нет / по возможности smoke-тест на тестовом B24
- Ручной сценарий:
  - Регистрация тестового пользователя; изменение полей профиля; проверка контакта в CRM

## Документация

- Изученные документы:
  - `docs/features/b24_integration.md`
  - `docs/features/user_profile_edit.md` (если есть расхождения — синхронизировать)
- Что обновить:
  - `docs/features/b24_integration.md` — блок «пользователь и контакт»
  - `docs/features/local_classes_segments_and_modules.md` — границы сегмента
- Что создать (если нужно):
  - нет

## Статус

- planned
