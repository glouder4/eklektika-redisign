# CRM Field Mapping Audit (2026-04-29)

## Scope and source of truth
- **Source of truth (user side):**
  - текущий запрос на аудит (обязательные manager-поля: `UF_MANAGER`, `ASSIGNED_BY_ID`, `UF_MANAGER2`, `UF_CRM_1757682312`);
  - зафиксированные маппинги в `docs/features/b24_integration.md` (раздел "Синхронизируемые поля").
- **Source of truth (actual code):**
  - runtime-реализация в модуле `eklektika.b24.usersync`;
  - legacy-ветка добавления сотрудника через `director/person/add-new-person-action.php`.

## User mappings: confirmed/conflict/missing

### Confirmed
| Site/User field | CRM field | Status | Evidence |
|---|---|---|---|
| `NAME` | `NAME` | confirmed | `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |
| `LAST_NAME` | `LAST_NAME` | confirmed | `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |
| `WORK_POSITION` | `POST` | confirmed | `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |
| `PERSONAL_PHONE` | `PHONE[WORK]` | confirmed | `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |
| `EMAIL` | `EMAIL[WORK]` | confirmed | `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |
| `UF_CITY` | `UF_CRM_3804624445810` | confirmed | `local/modules/eklektika.b24.usersync/lib/Config/RegisterUserCompanyConfig.php`, `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |
| `USER_ID` (site user id) | `UF_CRM_3804624445748` | confirmed | `local/modules/eklektika.b24.usersync/lib/Config/RegisterUserCompanyConfig.php`, `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |
| `UF_ADVERSTERING_AGENT` | `UF_CRM_1698752707853` | confirmed | `local/modules/eklektika.b24.usersync/lib/Config/RegisterUserCompanyConfig.php`, `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |

### Conflict
| Topic | Expected (user/docs) | Actual code | Impact | Evidence |
|---|---|---|---|---|
| Менеджер 2 в CRM | Явный manager-mapping с `UF_CRM_1757682312` | В основном usersync-потоке (`RegisterUserCompany`, `User::update`) поле `UF_CRM_1757682312` не заполняется; используется только в legacy add-person | Разная семантика manager sync по разным потокам | `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php`, `local/modules/eklektika.b24.usersync/lib/User.php`, `director/person/add-new-person-action.php` |
| Входящие менеджеры из CRM | Ожидается целостная пара manager1/manager2 | `User::update()` использует `ASSIGNED_MANAGER` и `SECOND_MANAGER` как вход и пишет в site `UF_MANAGER`/`UF_MANAGER2`; без явной валидации присутствия/формата ключей | Частичный payload может обнулять/ломать привязки менеджеров | `local/modules/eklektika.b24.usersync/lib/User.php` |

### Missing
| Missing mapping | Why missing now | Evidence |
|---|---|---|
| Обратный (site->CRM) маппинг `UF_MANAGER2` -> `UF_CRM_1757682312` в модуле usersync | Реализован только в legacy-обработчике добавления сотрудника | `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php`, `director/person/add-new-person-action.php` |
| Единый contract-level документ для manager mapping (inbound + outbound) | Есть разрозненные реализации, но нет формализованного контракта по всем 4 полям | `docs/features/b24_integration.md` (нет явной матрицы manager-полей) |

## Company mappings: confirmed/conflict/missing

### Confirmed
| Site/company input | CRM field | Status | Evidence |
|---|---|---|---|
| `UF_NAME_COMPANY` | `TITLE` | confirmed | `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |
| `UF_SPERE` | `UF_CRM_1669208000616` | confirmed | `local/modules/eklektika.b24.usersync/lib/Config/RegisterUserCompanyConfig.php`, `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |
| `UF_JUR_ADDRESS` | `UF_CRM_1669208295583` | confirmed | `local/modules/eklektika.b24.usersync/lib/Config/RegisterUserCompanyConfig.php`, `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |
| `UF_CITY` | `UF_CRM_1618551330657` | confirmed | `local/modules/eklektika.b24.usersync/lib/Config/RegisterUserCompanyConfig.php`, `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |
| `UF_REQ` (file) | `UF_CRM_1755643990423` | confirmed | `local/modules/eklektika.b24.usersync/lib/Config/RegisterUserCompanyConfig.php`, `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |
| Owner default | `ASSIGNED_BY_ID = 3036` | confirmed | `local/modules/eklektika.b24.usersync/lib/Config/RegisterUserCompanyConfig.php`, `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` |

### Conflict
| Topic | Expected (user/docs) | Actual code | Impact | Evidence |
|---|---|---|---|---|
| Единый owner assignment policy | Единый подход к owner assignment в интеграции | В usersync owner фиксирован (`3036`), в legacy add-person owner может динамически браться из `UF_MANAGER` и fallback `3036` | Непредсказуемое распределение ответственных в CRM | `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php`, `director/person/add-new-person-action.php` |

### Missing
| Missing mapping | Why missing now | Evidence |
|---|---|---|
| Явная матрица "company field contract" (site->CRM->site) в одном документе | Текущие поля размазаны между config/классом/feature-doc | `local/modules/eklektika.b24.usersync/lib/Config/RegisterUserCompanyConfig.php`, `docs/features/b24_integration.md` |

## Manager mappings (UF_MANAGER, ASSIGNED_BY_ID, UF_MANAGER2, UF_CRM_1757682312)

| Field | Expected (source of truth from user) | Actual code state | Verdict |
|---|---|---|---|
| `UF_MANAGER` | Должен участвовать в manager mapping | Inbound: `User::update()` пишет `UF_MANAGER` из `ASSIGNED_MANAGER`; legacy add-person пишет `UF_MANAGER` при создании пользователя | partial-confirmed |
| `ASSIGNED_BY_ID` | Должен быть частью manager mapping | usersync registration: жестко `3036`; legacy add-person: берется из manager-кода (`UF_MANAGER`) или fallback `3036` | conflict |
| `UF_MANAGER2` | Должен участвовать в manager mapping | Inbound: `User::update()` пишет `UF_MANAGER2` из `SECOND_MANAGER`; legacy add-person пишет в профиль пользователя | partial-confirmed |
| `UF_CRM_1757682312` | Должен использоваться для manager2 в CRM | Используется только в legacy add-person при `crm.contact.add`; в основном usersync потоке отсутствует | missing-in-core-flow |

## Источники в коде (пути файлов)
- `local/modules/eklektika.b24.usersync/lib/Config/RegisterUserCompanyConfig.php`
- `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php`
- `local/modules/eklektika.b24.usersync/lib/User.php`
- `director/person/add-new-person-action.php`
- `local/components/online-service/user.profile.edit/class.php`
- `docs/features/b24_integration.md`

## Priority fix list (P0/P1/P2)

### P0
1. Зафиксировать единый contract для manager mapping в core usersync-потоке: как `UF_MANAGER/UF_MANAGER2` соответствуют CRM `ASSIGNED_BY_ID/UF_CRM_1757682312` в обе стороны.
2. Убрать расхождение owner-логики между usersync registration и legacy add-person (или явно зарегистрировать как временное исключение с owner/deadline).

### P1
1. Добавить защиту от частичного payload для manager-полей (`ASSIGNED_MANAGER`, `SECOND_MANAGER`) в `User::update()` с четкой политикой "missing key != clear field".
2. Документировать канонические типы значений (ID элемента ИБ vs XML_ID vs CRM user id) для каждого manager-поля.

### P2
1. Свести field-mapping в единую таблицу в docs/features и ссылаться из рефакторинг-ветки.
2. Добавить smoke-checklist для верификации manager mapping после изменений (регистрация, add-person, CRM update).
