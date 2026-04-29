# Контракты обмена Site <=> B24 (yomerch, current)

## Старт для команды Bitrix24 (внешняя передача)

Один файл «что куда слать и что приложить»: **[BITRIX24_EXTERNAL_TEAM_HANDOFF.md](./BITRIX24_EXTERNAL_TEAM_HANDOFF.md)**.  
Таблицы **сайт ↔ `UF_CRM_*`** (дублируют и дополняют раздел 3 ниже): **[b24_site_to_crm_uf_field_map.md](./b24_site_to_crm_uf_field_map.md)**.

## Назначение документа
Документ фиксирует фактический контракт интеграции для текущей архитектуры модулей `yomerch.*` и предназначен для передачи команде Bitrix24 как рабочая спецификация.

Источник истины: код модулей `local/modules/yomerch.b24.inbound`, `local/modules/yomerch.b24.usersync`, `local/modules/yomerch.company`, `local/modules/yomerch.b24.rest`.

## 1) OUTBOUND (Сайт -> B24)

### 1.1 Канонический транспорт
- Все исходящие вызовы выполняются через `\OnlineService\B24\RestClient::callRestMethod()`.
- URL метода строится как `URL_B24/rest/1/{B24_REST_WEBHOOK_MAIN}/{method}.json`.

### 1.2 Сценарии/триггеры и методы B24
| Сценарий на сайте | Где в коде | Метод B24 | Назначение |
|---|---|---|---|
| Проверка дубликата ИНН перед регистрацией | `RegisterUserCompany::ensureInnDuplicatePrecheck` | `crm.requisite.list` | Проверка, что `RQ_INN` не занят |
| Регистрация пользователя (контакт) | `RegisterUserCompany::createB24Company` | `crm.contact.add` | Создать контакт CRM |
| Регистрация юрлица/агента (компания) | `RegisterUserCompany::createB24Company` | `crm.company.add` | Создать компанию CRM |
| Чтение созданной компании | `RegisterUserCompany::createB24Company` | `crm.company.get` | Получить `ID`/поля созданной компании |
| Создание реквизита компании | `RegisterUserCompany::createB24Company` | `crm.requisite.add` | Создать карточку реквизита |
| Заполнение реквизита | `RegisterUserCompany::createB24Company` | `crm.requisite.update` | Записать `RQ_INN`/`RQ_KPP`/`RQ_COMPANY_FULL_NAME` |
| Привязка контакта к компании | `RegisterUserCompany::createB24Company` | `crm.contact.company.add` | Связать контакт и компанию |
| Обновление компании из ЛК сайта | `Company::sendToBitrix24` | `crm.company.update` | Обновить карточку компании в CRM |
| Создание дочерней компании | `Company::createBranchCompany` | `crm.company.add` | Создать филиал в CRM |
| Проверка ИНН при создании филиала | `Company::createBranchCompany` | `crm.requisite.list` | Проверить уникальность ИНН |
| Привязка руководителя к филиалу | `Company::createBranchCompany` | `crm.contact.company.add` | Связать контакт руководителя и филиал |
| Создание/обновление реквизита филиала | `Company::createBranchCompany` | `crm.requisite.add` + `crm.requisite.update` | Заполнить реквизиты филиала |

### 1.3 Payload contract (основные исходящие методы)

#### `crm.contact.add` (регистрация)
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `fields.NAME` | string | yes | no | Имя |
| `fields.SECOND_NAME` | string | no | yes | Отчество |
| `fields.LAST_NAME` | string | yes | no | Фамилия |
| `fields.POST` | string | no | yes | Должность (`WORK_POSITION`) |
| `fields.BIRTHDATE` | string(date) | no | yes | День рождения |
| `fields.OPENED` | string enum | yes | no | Всегда `"Y"` |
| `fields.ASSIGNED_BY_ID` | int | yes | no | Основной менеджер CRM |
| `fields.UF_CRM_1757682312` | int | no | yes | Второй менеджер CRM (если есть mapping с `UF_MANAGER2`) |
| `fields.UF_CRM_3804624445810` | string | no | yes | Город контакта |
| `fields.UF_CRM_1701839165901` | string | no | yes | Текстовая пометка регистрации |
| `fields.UF_CRM_3804624445748` | int | no | yes | ID пользователя сайта |
| `fields.PHONE` | array<object> | yes | no | `[{"VALUE": string, "VALUE_TYPE":"WORK"}]` |
| `fields.EMAIL` | array<object> | yes | no | `[{"VALUE": string, "VALUE_TYPE":"WORK"}]` |
| `fields.COMPANY_ID` | int | no | yes | При найденной/созданной компании |

#### `crm.company.add` (регистрация/филиал)
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `fields.TITLE` | string | yes | no | Название компании |
| `fields.PHONE` | array<object> | no | yes | Телефон компании |
| `fields.EMAIL` | array<object> | no | yes | Email компании |
| `fields.WEB` | array<object> | no | yes | Сайт компании |
| `fields.COMPANY_TYPE` | string | yes | no | `"CUSTOMER"` |
| `fields.ASSIGNED_BY_ID` | int | yes | no | Ответственный (default `3036` при отсутствии mapping) |
| `fields.UF_CRM_1669208000616` | string | no | yes | Сфера деятельности |
| `fields.UF_CRM_1669208295583` | string | no | yes | Юридический адрес (в потоке регистрации) |
| `fields.UF_CRM_1618551330657` | string | no | yes | Город компании |
| `fields.UF_CRM_3804624439373` | string | no | yes | Дополнительное поле компании (передаётся из формы/интеграционного payload при наличии) |
| `fields.UF_CRM_1755643990423` | object | no | yes | Файл реквизитов: `{"fileData":[filename, base64]}` |
| `fields.UF_CRM_1758028816` | int/string | no | yes | B24 ID головной компании (для филиала) |

#### `crm.company.update` (из ЛК сайта)
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `id` | int/string | yes | no | B24 ID компании |
| `fields.TITLE` | string | no | yes | Название |
| `fields.UF_CRM_INN` | string | no | yes | ИНН |
| `fields.UF_CRM_1669208295583` | string | no | yes | Город/адрес (по текущему config) |
| `fields.PHONE` | array<object> | no | yes | Телефон |
| `fields.EMAIL` | array<object> | no | yes | Email |
| `fields.WEB` | array<object> | no | yes | Сайт |
| `fields.UF_CRM_1755643990423` | object | no | yes | Файл реквизитов `fileData` |

#### `crm.requisite.list`
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `filter.RQ_INN` | string | yes | no | ИНН для проверки уникальности |
| `select` | array<string> | no | yes | Обычно `["ID"]` или `["ID","RQ_INN","ENTITY_ID"]` |

#### `crm.requisite.update`
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `id` | int | yes | no | ID реквизита |
| `fields.ENTITY_ID` | int/string | yes | no | ID компании CRM |
| `fields.ENTITY_TYPE_ID` | string | yes | no | `"4"` |
| `fields.RQ_INN` | string | yes | no | ИНН |
| `fields.RQ_KPP` | string | no | yes | КПП |
| `fields.RQ_COMPANY_FULL_NAME` | string | no | yes | Полное имя юрлица |

## 2) INBOUND (B24 -> Сайт)

### 2.1 Канонический endpoint и базовые требования
- Endpoint (canonical): `/local/modules/yomerch.b24.inbound/endpoint.php`
- HTTP method: `POST` (формат `application/x-www-form-urlencoded`; код читает `$_REQUEST`)
- Security:
  - fail-closed: если `inbound_secret` не задан, запрос запрещается (`403`);
  - dev-override на работу без секрета разрешен только через явный флаг `allow_inbound_without_secret=true` (или env `YOMERCH_ALLOW_INBOUND_WITHOUT_SECRET=1`) и по умолчанию выключен;
  - если задан `inbound_secret`, обязателен токен;
  - токен принимается из `X-SYNC-TOKEN` header или `sync_token` в request;
  - при отказе ответ: HTTP `403`, body `{"success":0,"error":"sync_forbidden"}`.
- Порядок обработки endpoint: сначала авторизация (`InboundSecurity`), затем полное request logging; для reject фиксируется только минимальный служебный лог без payload.

### 2.2 ACTION list (ожидаемые команды)
- `UPDATE_GROUP`
- `UPDATE_CONTACT`
- `UPDATE_BATCH_USERS`
- `DELETE_CONTACT`
- `DELETE_COMPANY`
- `UPDATE_COMPANY`
- `SYNC_COMPANY_CONTACTS`
- `UPDATE_MANAGER`

### 2.3 Payload по каждому ACTION

#### ACTION=`UPDATE_GROUP`
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `ID` | int/string | yes | no | Ключ группы, формирует `STRING_ID=GROUP_{ID}` |
| `ACTIVE` | string enum | yes | no | `"Y"`/`"N"` |
| `C_SORT` | int/string | yes | no | Сортировка группы |
| `NAME` | string | yes | no | Название группы |

Response: plain text (ID группы, не JSON).

#### ACTION=`UPDATE_CONTACT`
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `B24_ID` | int/string | yes | no | Канонический ID контакта B24 для поиска пользователя сайта |
| `NAME`,`LAST_NAME`,`SECOND_NAME` | string | no | yes | Персональные поля |
| `EMAIL` | string | no | yes | Email пользователя |
| `PERSONAL_PHONE` | string | no | yes | Телефон пользователя |
| `WORK_POSITION` | string | no | yes | Должность |
| `PERSONAL_BIRTHDAY` | string(date) | no | yes | ДР |
| `ASSIGNED_BY_ID` / `ASSIGNED_MANAGER` | int/string | no | yes | Primary manager (dual-read) |
| `UF_CRM_1757682312` / `SECOND_MANAGER` | int/string | no | yes | Secondary manager (dual-read) |
| `UF_CRM_1775034008956` | scalar | no | yes | Флаг маркетинг-агента (маппится в `UF_ADVERSTERING_AGENT`) |
| `UF_CRM_1777068292434` | scalar | no | yes | Флаг руководителя (маппится в `UF_IS_DIRECTOR`) |

Response JSON:
- success: `{"success":1,"data":{"updated":true}}`
- fail: `{"success":0,"data":{"updated":false,"reason_code":"..."}}` (если reason доступен)

#### ACTION=`UPDATE_BATCH_USERS`
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `CONTACT_IDS` | array<int|string> | yes | no | Список B24 contact IDs |
| `IS_MARKETING_AGENT` | scalar | yes | no | Признак маркетинг-агента |

Response JSON: `{"success":1|0,"data":{"batch":true|false}}`.

#### ACTION=`DELETE_CONTACT`
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `ID` | int/string | yes | no | B24 contact ID для удаления site user |

Response JSON: `{"success":1|0,"data":{"deleted":bool}}`.

#### ACTION=`UPDATE_COMPANY`
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `OS_COMPANY_B24_ID` | int/string | yes | no | Ключ связи с элементом компании на сайте |
| `OS_COMPANY_NAME` | string | yes | no | Имя элемента/компании |
| `ACTIVE` | string enum | yes | no | Статус элемента компании (`Y`/`N`) |
| `OS_COMPANY_USERS` | array<int|string> | no | yes | Список B24 contact IDs (конвертируются в site user IDs) |
| `CONTACT_IDS` | array<int|string> | no | yes | Fallback-массив при резолве пользователей |
| `OS_COMPANY_DISCOUNT_VALUE` | int/string | no | yes | Значение статуса/скидки для групп |
| `OS_IS_MARKETING_AGENT` | object | no | yes | Используется `VALUE` для групп marketing |
| `OS_HOLDING_OF` | int/string | no | yes | B24 ID головной компании |
| `OS_REQUSITES_FILE` | object | no | yes | Файл реквизитов (`SUBDIR`,`FILE_NAME`,`ORIGINAL_NAME`) |
| `OS_COMPANY_IS_HEAD_OF_HOLDING` | scalar | no | yes | Для логики скидок директора |
| `OS_COMPANY_INN`,`OS_COMPANY_CITY`,`OS_COMPANY_WEB_SITE`,`OS_COMPANY_PHONE`,`OS_COMPANY_EMAIL` | string | no | yes | Поля карточки компании |

Response JSON: `{"success":1|0,"data":{"company_id":int}}`.

#### ACTION=`DELETE_COMPANY`
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `ID` | int/string | yes | no | B24 company ID (ищется по `CODE`) |

Response JSON: `{"success":1|0,"data":{"deleted":bool}}`.

#### ACTION=`SYNC_COMPANY_CONTACTS`
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `COMPANY_ID` | int/string | yes | no | ID элемента головной компании на сайте (не B24 ID) |

Response: JSON-string от `Company::syncCompanyContacts` (`success`, `message`, `errors`, `debug_info`).

#### ACTION=`UPDATE_MANAGER`
| Поле | Тип | Required | Nullable | Примечание |
|---|---|---|---|---|
| `ID` | int/string | yes | no | B24 user ID (пишется в `XML_ID` элемента ИБ менеджеров) |
| `NAME` | string | no | yes | Имя |
| `LAST_NAME` | string | no | yes | Фамилия |
| `PHONE` | string | no | yes | Телефон |
| `EMAIL` | string | no | yes | Email |
| `POSITION` | string | no | yes | Должность |
| `PERSONAL_PHOTO` | string(path) | no | yes | Относительный URL фото на портале |

Response JSON: `{"success":1|0,"data":{"updated":bool}}`.

### 2.4 Общий response/error контракт inbound
- Если `ACTION` неизвестен: HTTP `400`, `{"success":0,"error":"unknown_action","action":"..."}`.
- Если payload не проходит contract-validation: HTTP `400`, `{"success":0,"error":"invalid_payload","reason_code":"...","action":"..."}`.
- Если исключение на обработке: HTTP `500`, `{"success":0,"error":"dispatch_failed","message":"Internal error"}`.
- При включенном debug-trace в runtime к JSON может добавляться `debug_trace` (массив строк).

Типовые `reason_code` для `invalid_payload`:
- `missing_action`
- `update_group_missing_id`, `update_group_invalid_active`
- `update_contact_missing_b24_id`
- `update_batch_users_missing_contact_ids`, `update_batch_users_invalid_contact_ids`, `update_batch_users_missing_marketing_flag`
- `delete_contact_missing_id`, `delete_company_missing_id`
- `update_company_missing_os_company_b24_id`, `update_company_missing_os_company_name`, `update_company_missing_active`, `update_company_invalid_active`
- `sync_company_contacts_missing_company_id`
- `update_manager_missing_id`

## 3) Явный mapping Site <=> B24

### 3.1 User/Contact mapping
| Site field | B24 field | Направление | Комментарий |
|---|---|---|---|
| `NAME` | `NAME` | bidirectional | Контакт/пользователь |
| `LAST_NAME` | `LAST_NAME` | bidirectional | Контакт/пользователь |
| `SECOND_NAME` | `SECOND_NAME` | site->B24 | При регистрации |
| `WORK_POSITION` | `POST` | bidirectional | В outbound profile update используется `POST` |
| `PERSONAL_PHONE` | `PHONE[*].VALUE` | bidirectional | Тип `WORK` в текущем коде |
| `EMAIL` | `EMAIL[*].VALUE` | bidirectional | Тип `WORK` в текущем коде |
| `UF_B24_USER_ID` (site user) | `CONTACT.ID` | linkage | Ключ связи user<->contact |
| `UF_ADVERSTERING_AGENT` | `UF_CRM_1775034008956` / `IS_MARKETING_AGENT` | inbound | Через `CrmInboundUfMap` |
| `UF_IS_DIRECTOR` | `UF_CRM_1777068292434` | inbound | Через `CrmInboundUfMap` |
| `UF_CITY` | `UF_CRM_3804624445810` | site->B24 | Регистрационный поток |
| `USER_ID` | `UF_CRM_3804624445748` | site->B24 | ID пользователя сайта в контакте CRM |

### 3.2 Company mapping
| Site field | B24 field | Направление | Комментарий |
|---|---|---|---|
| `OS_COMPANY_B24_ID` | `COMPANY.ID` | bidirectional | Ключ связи (site `CODE`) |
| `OS_COMPANY_NAME` | `TITLE` | bidirectional | Название |
| `OS_COMPANY_INN` | `RQ_INN` / `UF_CRM_INN` | bidirectional | В requisites + `crm.company.update` |
| `OS_COMPANY_CITY` | `UF_CRM_1618551330657` / `UF_CRM_1669208295583` | bidirectional | В разных потоках используются разные UF |
| `OS_COMPANY_WEB_SITE` | `WEB[*].VALUE` | bidirectional | Сайт |
| `OS_COMPANY_PHONE` | `PHONE[*].VALUE` | bidirectional | Телефон |
| `OS_COMPANY_EMAIL` | `EMAIL[*].VALUE` | bidirectional | Email |
| `OS_REQUSITES_FILE` | `UF_CRM_1755643990423` | bidirectional | Файл реквизитов (`fileData`) |
| `OS_HEAD_COMPANY_B24_ID` | `UF_CRM_1758028816` | site->B24 | Ссылка на головную B24 компанию (филиалы) |

### 3.3 Manager mapping (обязательно)
| Site side | B24 canonical | B24 legacy alias | Правило |
|---|---|---|---|
| `UF_MANAGER` | `ASSIGNED_BY_ID` | `ASSIGNED_MANAGER` | Inbound: dual-read, outbound registration: primary manager или fallback `3036` |
| `UF_MANAGER2` | `UF_CRM_1757682312` | `SECOND_MANAGER` | Inbound: dual-read, outbound: отправляется только при валидном mapping manager XML_ID |

Нормализация менеджеров:
- Сайт хранит менеджера как ID элемента ИБ `53`.
- В CRM передается `XML_ID` этого элемента (числовой B24 user ID).

## 4) Compatibility / legacy aliases
- Для inbound `UPDATE_CONTACT` поддержаны legacy aliases:
  - primary manager: `ASSIGNED_MANAGER` (fallback к `ASSIGNED_BY_ID`);
  - secondary manager: `SECOND_MANAGER` (fallback к `UF_CRM_1757682312`).
- Для marketing inbound поддержан alias `IS_MARKETING_AGENT` (кроме `UF_CRM_1775034008956`).
- Legacy helper-функции `sendRequestB24`/`sendRequest` допустимы только как compatibility shim; целевой API — `RestClient`.

## 5) Ошибки/коды/валидация

### 5.1 Inbound endpoint-level
| Условие | HTTP | Body |
|---|---|---|
| `inbound_secret` не задан (fail-closed, без dev-override) | 403 | `{"success":0,"error":"sync_forbidden"}` |
| Неверный/отсутствующий sync token (при активном секрете) | 403 | `{"success":0,"error":"sync_forbidden"}` |
| Неизвестный `ACTION` | 400 | `{"success":0,"error":"unknown_action","action":"..."}` |
| Необработанное исключение | 500 | `{"success":0,"error":"dispatch_failed","message":"Internal error"}` |

### 5.2 Business validation (site -> B24)
- Регистрация юрлица/агента: при пустом ИНН -> `required_inn`.
- Дубликат ИНН (локально/B24): `duplicate_inn`.
- Недоступен транспорт проверки ИНН: `inn_check_unavailable`.

### 5.3 Transport-level (RestClient)
- CURL error -> `{"success":0,"error":"CURL Error: ...","errno":...}`.
- HTTP != 200 -> `{"success":0,"error":"HTTP Error: ...","response":"..."}`.
- Невалидный JSON -> `{"success":0,"error":"JSON Parse Error: ...","raw_response":"..."}`.
- HTTP 200 с B24 `error`/`error_description` -> нормализованная ошибка:
  `{"success":0,"error":"B24 API error","error_code":"...","error_description":"...","http_code":200,...}`.
- Для `callRestMethod` отсутствие `result` в HTTP 200 считается ошибкой контракта:
  `{"success":0,"error":"B24 response contract violation: result is missing","error_code":"b24_missing_result",...}`.

## 6) Open questions / assumptions
1. **`UPDATE_CONTACT`: `B24_ID` vs `ID`**  
   Текущий код требует `B24_ID`, но часть legacy-потоков исторически использует `ID`. Принято: для B24-команды канонический ключ `B24_ID`; `ID` считать неканоничным и не гарантированным.
2. **Два разных CRM-поля для города компании**  
   В регистрационном потоке используется `UF_CRM_1618551330657`, в `crm.company.update` из ЛК — `UF_CRM_1669208295583`. Нужна финальная унификация на стороне B24.
3. **`SYNC_COMPANY_CONTACTS` принимает site company element ID**  
   Не B24 company ID. Нужно подтвердить, что B24-сторона действительно может передавать ID элемента сайта.
4. **Смешанный формат response по ACTION**  
   `UPDATE_GROUP` и `SYNC_COMPANY_CONTACTS` возвращают не унифицированный JSON-объект с `success:int`; допустимо как legacy-поведение, но желательно унифицировать в следующем релизе.

## 7) Handoff для команды B24 (что отправляем / что ожидаем)

### Что отправляем команде B24
- Канонический inbound endpoint: `/local/modules/yomerch.b24.inbound/endpoint.php` + требования по токену (`X-SYNC-TOKEN`/`sync_token`).
- Полный список `ACTION` и contract payload/response из разделов `2.2` и `2.3`.
- Outbound-методы и обязательные поля payload из разделов `1.2` и `1.3`.
- Явный mapping Site <=> B24 (user/contact, company, manager) из раздела `3`.

### Что ожидаем от команды B24
- Передавать только канонические ключи и форматы (в первую очередь `B24_ID` для `UPDATE_CONTACT`).
- Подтвердить рабочий формат для `SYNC_COMPANY_CONTACTS` (передаётся site company element ID).
- Согласовать и зафиксировать единое CRM-поле города компании.
- Подтвердить план унификации response-формата для `UPDATE_GROUP` и `SYNC_COMPANY_CONTACTS`.
