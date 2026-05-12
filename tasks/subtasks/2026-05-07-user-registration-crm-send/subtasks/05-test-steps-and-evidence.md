# Subtask 05: test steps and evidence

- Parent: [../task.md](../task.md)
- Status: `todo`

## Goal

Сформировать проверяемый план тестирования для подтверждения “регистрация → отправка в CRM”, включая минимальный набор evidence.

## Test plan (manual, TL-owned)

### Preconditions

- В `local/php_interface/b24_integration_config.php` настроены `base_url` и `rest_webhook_main` для целевого портала/стенда (без публикации значений в репозитории/логах).
- Модуль `yomerch.b24.usersync` подключается через `local/php_interface/init.php` → `local/modules/bootstrap.php`.

### Scenario A: физлицо (без компании)

- **Steps**
  - Зарегистрировать пользователя через штатную форму (тот путь, который реально вызывает `CUser::Add()`).
  - Убедиться, что сработал `main:OnAfterUserAdd` (косвенно: запись `UF_B24_USER_ID` у пользователя).
- **Expected**
  - В CRM появился контакт с email/телефоном.
  - У пользователя на сайте заполнен `UF_B24_USER_ID` (CRM contact id).
  - Пользователь остаётся `ACTIVE='N'` (ожидаемая политика до подтверждения).
- **Evidence to capture**
  - `USER_ID` на сайте
  - значение `UF_B24_USER_ID`
  - CRM contact id (тот же, что в `UF_B24_USER_ID`)

### Scenario B: юрлицо/агент (с компанией)

- **Steps**
  - Зарегистрировать пользователя с `UF_TYPE` = `5` или `6` и заполненными `UF_INN`, `UF_NAME_COMPANY`, `EMAIL`, `PERSONAL_PHONE`.
- **Expected**
  - В CRM создана или найдена компания по ИНН (через requisite).
  - Контакт привязан к компании (`crm.contact.company.add`), а в контакте `COMPANY_ID` заполнен.
  - Если на сайте создаётся элемент каталога компании, его id записывается в CRM extra field (`RegisterUserCompanyConfig::CRM_COMPANY_EXTRA_FIELD`) через `crm.company.update`.
- **Evidence to capture**
  - `USER_ID` на сайте
  - `UF_B24_USER_ID` (contact id)
  - CRM company id (если применимо)
  - наличие/значение CRM extra field для site company element id (если применимо)

### Negative checks

- Повторная регистрация с тем же email должна блокироваться:
  - если конфликт на сайте: `ensureEmailUniquenessPrecheck()` находит пользователя через `CUser::GetList`.
  - если конфликт в CRM: `crm.duplicate.findbycomm`.

## DoD

- [ ] TL подтвердил сценарии A/B на стенде.
- [ ] TL приложил evidence: user id + `UF_B24_USER_ID` + CRM ids.
- [ ] TL отметил любые расхождения (например, если регистрация идёт через `CUser::Register()` и события отличаются).

