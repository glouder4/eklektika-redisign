# Документация: Интеграция с Bitrix24 CRM

## Описание
Комплексная интеграция сайта с Bitrix24 CRM, включающая синхронизацию контактов, компаний, пользователей, веб-хуки и двусторонний обмен данными.

## Основные характеристики
- **URL Bitrix24**: задаётся в `local/php_interface/b24_integration_config.php` (константа `URL_B24` в `init.php`)
- **REST API**: Использование REST методов для взаимодействия
- **Веб-хуки**: Обработка событий CRM в реальном времени
- **Двусторонняя синхронизация**: Данные передаются в обе стороны
- **Файловая интеграция**: Загрузка и скачивание документов

## Места использования и реализации

### 1. Конфигурация URL и вебхуков Bitrix24

**Файл конфигурации**: `local/php_interface/b24_integration_config.php`

Возвращает массив с полями:

- **`base_url`** — базовый URL портала Bitrix24 (со слешем на конце), для теста и прода задаются разными значениями через флаг **`$useTestPortal`** в том же файле.
- **`rest_webhook_main`** — токен входящего вебхука для основных REST-вызовов (`crm.*`, `user.get` и т.д.), подставляется в путь `…/rest/1/{token}/{method}.json`.
- **`rest_webhook_kit`** — токен вебхука для методов `kit.productapplications.*` (заявки из сделки).

В **`local/php_interface/init.php`** после подключения конфига определяются константы:

- `URL_B24` — копия `base_url` (обратная совместимость со всем кодом, использующим константу).
- `B24_REST_WEBHOOK_MAIN`, `B24_REST_WEBHOOK_KIT` — токены из конфига.

**Шаблон без секретов** для нового стенда: `local/php_interface/b24_integration_config.example.php`.

**Транспортный слой**: класс `OnlineService\B24\RestClient` в модуле **`eklektika.b24.rest`**: `local/modules/eklektika.b24.rest/lib/RestClient.php`

- `callRestMethod($method, $params, $debug)` — POST на основной REST-вебхук; при успехе возвращает **`$response['result']`** (как прежняя функция `sendRequestB24`).
- `postAjaxProxy($params, $debug)` — POST на `/local/classes/ajax.php`, возвращает **полный** декодированный JSON (как `sendRequest()`).
- `postSiteRequestsHandler($params, $debug)` — POST на `site_requests_handler.php` (базовый класс `Request`).
- `getKitWebhookPrefix()` — префикс URL для `kit.productapplications.*`.
- Транспортные параметры и URL-мэппинг централизованы в `local/modules/eklektika.b24.rest/lib/Config/RestTransportConfig.php` (timeouts, пути прокси, построение webhook URL), бизнес-логика модулей не меняется.

Legacy-глобали **`sendRequestB24`** и **`sendRequest`** сохранены в `local/modules/eklektika.b24.rest/lib/LegacyGlobalB24.php` и помечены **`@deprecated`**; они делегируют вызовы в `RestClient`. В `init.php` эти функции не являются целевой точкой размещения.

**Контракт ответов:** при ошибках транспорта (CURL, HTTP≠200, невалидный JSON) возвращается массив с **`success`** = 0 (как в legacy). При успешном разборе JSON метод **`callRestMethod`** возвращает только значение **`result`** из ответа Bitrix24. Ошибки REST API внутри JSON при HTTP 200 по-прежнему не нормализуются отдельно (наследие **sendRequestB24**).

#### Legacy-глобали и статус миграции

- `sendRequestB24()` и `sendRequest()` оставлены как `@deprecated`-функции совместимости в `eklektika.b24.rest/lib/LegacyGlobalB24.php`.
- Целевой путь для нового кода: прямой вызов `\OnlineService\B24\RestClient`.
- В `local/classes/requires.php` модуль `eklektika.b24.rest` загружается первым в полной цепочке bootstrap: `eklektika.b24.rest` → `eklektika.company` → `eklektika.catalog.pricing` → `eklektika.site` → `eklektika.catalog.import` → `eklektika.orders.applications` → `eklektika.b24.usersync`; это стабилизирует доступность транспортных helper-функций для legacy-кода.
- Контракт загрузки модулей в bootstrap: используется `require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/<module_id>/include.php'`; при отсутствии `include.php` фиксируется `E_USER_WARNING`, bootstrap продолжается (fail-safe, без фатала).
- Контракт автозагрузки внутри `local/modules/eklektika.*/include.php`: `Loader::registerAutoLoadClasses(null, [...])` + абсолютные пути `/local/modules/<module_id>/lib/...`; использование `module_id` как первого аргумента для local-only модулей не допускается, чтобы исключить ошибочный префикс `/bitrix/modules/<module_id>/`.

#### Правила зависимостей (ST-10)

- `eklektika.b24.rest` — единственный общий транспортный модуль; здесь не размещается доменная логика usersync/company/pricing/orders.
- Доменные модули могут зависеть от `eklektika.b24.rest`, но не друг от друга напрямую, кроме документированных исключений в `local_classes_segments_and_modules.md`.
- `eklektika.orders.applications` использует только транспортный API (`RestClient::callKitRestGet`) и runtime-подключение Bitrix `sale/iblock`.
- Для временного исключения `usersync -> company` используется implement-ready follow-up `FU-ST11-USERSYNC-COMPANY-GATEWAY` (owner/deadline/criteria синхронизированы в ST-10/ST-11 и `local_classes_segments_and_modules.md`).

### 1.1 Поведение `UPDATE_CONTACT` (группы и активность)

Экшен **`UPDATE_CONTACT`** в `local/classes/ajax.php` делегирует в **`OnlineService\B24\User::update()`**.

Инварианты (защита от регрессий при переключении только `UF_ADVERSTERING_AGENT` / рекламного агента):

- **`User::update()`** перед `CUser::Update` сбрасывает из полей внешнего запроса ключи **`GROUP_ID`** и **`GROUPS_ID`**, чтобы членство в группах не применялось «как пришло» из транспорта.
- Любая пересборка групп в **`eklektika.b24.usersync`** (в т.ч. **`addUserToGroup`**, ветки руководителя) опирается на **`CUser::GetUserGroup`** + нормализацию ID или на **`CUser::SetUserGroup`** с полным merge; **`CUser::GetByID` → `GROUPS_ID`** не используется как единственный источник списка перед записью — иначе возможна потеря групп (скидочных, 432 и др.) при CRM-синхронизации.
- **`UF_IS_DIRECTOR` и группа руководителей (432):** назначение/снятие группы **432** выполняется **только если** в payload присутствует ключ **`UF_IS_DIRECTOR`** (частичный `UPDATE_CONTACT` без этого ключа **не** трактуется как «сняли руководителя» и **не** вызывает пересборку групп по этой ветке). При снятии 432 список групп нормализуется перед **`SetUserGroup`**, скидочные группы из матрицы **`CompanyModuleConfig::getCompanyDiscountPercentByAssignedGroupId()`** (prod/test по `B24_USE_TEST_PORTAL`) не должны теряться при этой операции.
- Снятие статуса рекламного агента в **`updateMarketingAgentPriceType()`** (ветка «был в группе агента, не должен быть»): (1) **`removeUserFromGroupsByIds($userId, [MARKETING_AGENT_GROUP_ID])`** — меняется только членство, снимается **только** группа агента; (2) отдельный **`CUser::Update($userId, ['ACTIVE' => 'N', 'UF_ADVERSTERING_AGENT' => 0])`** **без** `GROUP_ID` / `GROUPS_ID`. Метод **`removeUserFromGroup()`** для этого сценария **не** используется.
- Скидочные группы по компании настраиваются только в потоке **`Company::updateCompanyElement`** и **только если в payload компании явно передан** `OS_COMPANY_DISCOUNT_VALUE` (см. `docs/features/company_system.md`); иначе скидочные группы пользователя не трогаются.

### 2. Базовый класс для запросов
**Файл**: `local/modules/eklektika.b24.rest/lib/Request.php`

Защищённый метод `sendRequest` делегирует выполнение в **`OnlineService\B24\RestClient::postSiteRequestsHandler()`** (единая обработка CURL/HTTP/JSON с классом `RestClient`).

**AJAX и контакт CRM:** экшены `UPDATE_CONTACT`, `UPDATE_BATCH_USERS`, `DELETE_CONTACT` в **`local/classes/ajax.php`** вызывают только **`OnlineService\B24\UserSync\ContactAjaxFacade`** (модуль **`eklektika.b24.usersync`**, файл `local/modules/eklektika.b24.usersync/lib/ContactAjaxFacade.php`), который делегирует в доменный класс **`OnlineService\B24\User`** без изменения контракта ответа для фронта.

### 3. Регистрация пользователей и компаний
**Файл**: `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php` (см. [MODULE-LAYOUT](../tasks/2026-04-21-refactor-local-classes-segmentation/MODULE-LAYOUT.md))

**Актуальный транспорт и конфигурация (ST-11):**
- CRM-вызовы внутри `RegisterUserCompany` выполняются через `\OnlineService\B24\RestClient::callRestMethod()` (через локальный адаптер класса), без прямых `sendRequestB24()/sendB24Request`.
- Маппинг CRM/UF-полей и константы сценария регистрации вынесены в модульный конфиг `local/modules/eklektika.b24.usersync/lib/Config/RegisterUserCompanyConfig.php`; групповые ID usersync централизованы в `lib/Config/UserSyncConfig.php`.
- Бизнес-поведение сценариев `OnBeforeUserRegisterHandler`, `OnAfterUserRegisterHandler`, `createB24Company`, `deleteStaffB24` сохранено; изменён только источник настроек и транспортный вызов.

#### Основная логика:
```php
namespace OnlineService\B24;

class RegisterUserCompany extends Request {
    
    // Проверка существования пользователя в B24
    public function isUserRegistered($arFields, $debug = false) {
        $b24User = new \OnlineService\B24\User();
        $userObject = $b24User->isUserRegistered($arFields, $debug);
        
        if ($userObject && !empty($userObject)) {
            return $userObject;
        }
        return false;
    }

    // Создание компании в B24
    private function createB24Company($arFields) {
        global $APPLICATION;

        $companyId = false;
        $reqFile = [];
        
        // Обработка файла реквизитов
        if (!empty($arFields['UF_REQ']) && !empty($arFields['UF_REQ']['name'])) {
            $file = $arFields['UF_REQ'];
            
            // Сохраняем файл в систему Битрикс
            $savedFileId = \CFile::SaveFile($file, 'os_requisites');
            $fileInfo = \CFile::GetFileArray($savedFileId);

            if ($file['error'] === 0) {
                $fileName = $file['name'];
                $filePath = $file['tmp_name'];
                $fileContent = file_get_contents($filePath);

                if ($fileContent !== false) {
                    // Кодируем в base64 для передачи в B24
                    $fileData = [
                        $fileName,
                        base64_encode($fileContent),
                    ];

                    $arFields['UF_CRM_1755643990423'] = [
                        'fileData' => $fileData
                    ];
                }
            }
        }

        // Данные для контакта в B24
        $dataContact = [
            'fields' => [
                'NAME' => $arFields['NAME'],
                'LAST_NAME' => $arFields['LAST_NAME'],
                'EMAIL' => [
                    ['VALUE' => $arFields['EMAIL'], 'VALUE_TYPE' => 'WORK']
                ],
                'PHONE' => [
                    ['VALUE' => $arFields['PERSONAL_PHONE'], 'VALUE_TYPE' => 'WORK']
                ],
                'UF_CRM_1701839165901' => "Пользователь зарегистрировался через сайт",
                'UF_CRM_1681120601710' => 0, // Не в черном списке
                'UF_CRM_1698752707853' => $arFields['UF_ADVERSTERING_AGENT'] == 'on' ? 1 : 0, // Рекламный агент
            ]
        ];

        // Если есть файл реквизитов, добавляем его
        if (!empty($arFields['UF_CRM_1755643990423'])) {
            $dataContact['fields']['UF_CRM_1755643990423'] = $arFields['UF_CRM_1755643990423'];
        }

        // Отправка запроса на создание контакта
        $response = $this->sendRequest([
            'action' => 'create_contact',
            'data' => $dataContact
        ]);

        if ($response && isset($response['result'])) {
            $contactId = $response['result'];
            
            // Если это компания или рекламный агент
            if ($arFields['UF_TYPE'] == '5' || $arFields['UF_TYPE'] == '6') {
                // Создание компании в B24
                $dataCompany = [
                    'fields' => [
                        'TITLE' => $arFields['UF_NAME_COMPANY'],
                        'UF_CRM_1669208000616' => $arFields['UF_SPERE'],
                        'UF_CRM_1669208295583' => $arFields['UF_JUR_ADDRESS'],
                        'UF_CRM_1755643990423' => $arFields['UF_CRM_1755643990423'] ?? null,
                    ]
                ];

                $companyResponse = $this->sendRequest([
                    'action' => 'create_company',
                    'data' => $dataCompany
                ]);

                if ($companyResponse && isset($companyResponse['result'])) {
                    $companyId = $companyResponse['result'];
                    
                    // Привязка контакта к компании
                    $this->sendRequest([
                        'action' => 'bind_contact_company',
                        'data' => [
                            'contact_id' => $contactId,
                            'company_id' => $companyId
                        ]
                    ]);
                }
            }

            // Создание элемента компании на сайте
            $companyElementParamss = [
                'OS_COMPANY_B24_ID' => $companyId ?: $contactId,
                'OS_COMPANY_NAME' => $arFields['UF_NAME_COMPANY'] ?: $arFields['NAME'] . ' ' . $arFields['LAST_NAME'],
                'OS_COMPANY_INN' => $arFields['UF_INN'],
                'OS_COMPANY_EMAIL' => $arFields['EMAIL'],
                'OS_COMPANY_PHONE' => $arFields['PERSONAL_PHONE'],
                'OS_COMPANY_CITY' => $arFields['UF_CITY'],
                'USER_ID' => $arFields['ID'],
                'OS_REQUSITES_FILE' => $savedFileId ?? null,
            ];

            $this->createCompanyElement($companyElementParamss);
        }

        return $contactId;
    }
}
```

### 4. Веб-хуки для обработки событий CRM
**Файлы**: `script/crm/rest/`

#### Обработка событий контактов:
**Файл**: `script/crm/rest/contact.php`

```php
<?php
// Веб-хук на изменения контактов
// Примечание: Этот файл использует сторонний модуль intec.eklectika
// и не является частью собственных разработок

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$contactId = $_REQUEST['data']['FIELDS']['ID'];
if (empty($contactId)) {
    return;
}

// Получение информации о контакте из B24
$contactInfo = sendRequestB24("crm.contact.get", ["id" => $contactId]);    

if (empty($contactInfo['EMAIL'][0]['VALUE']) && empty($contactInfo['NAME'])) {
    return;
}

// Обработка событий B24
if ($_REQUEST['event'] == 'ONCRMCONTACTADD') {    
    $logFile = $_SERVER['DOCUMENT_ROOT'].'/script/crm/logs/user.add.txt';
    addLog($logFile, 'ID contact CRM - '.$contactId);    
    
    // Логика создания пользователя (использует сторонний модуль)
} 

if ($_REQUEST['event'] == 'ONCRMCONTACTUPDATE') {    
    // Логика обновления пользователя (использует сторонний модуль)
}

function addLog($file, $text) {
    file_put_contents($file, date("d.m.Y H:i:s")." - ".$text.PHP_EOL, FILE_APPEND);
}
```

#### Обработка событий компаний:
**Файл**: `script/crm/rest/company.php`

```php
<?php
// Обработка событий компаний в B24
// Примечание: Этот файл использует сторонний модуль intec.eklectika
// и не является частью собственных разработок

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if ($_REQUEST['event'] == 'ONCRMCOMPANYADD' || $_REQUEST['event'] == 'ONCRMCOMPANYUPDATE') {
    $companyB24Id = $_REQUEST['data']['FIELDS']['ID'];
    
    if (!empty($companyB24Id)) {
        $file = $_SERVER['DOCUMENT_ROOT'].'/script/crm/logs/company.txt';
        file_put_contents($file, date('Y.m.d').PHP_EOL, FILE_APPEND);
        file_put_contents($file, 'Запрос из Б24 на работу компании ID - '.$companyB24Id.PHP_EOL, FILE_APPEND);
        
        // Получение данных о компании из B24
        $companyInfo = sendRequestB24("crm.company.get", ["id" => $companyB24Id]);
        
        // Логика обработки компании (использует сторонний модуль)
    }
}
```

#### Обработка событий реквизитов:
**Файл**: `script/crm/rest/requisite.php`

```php
<?php
// Обработка событий реквизитов в B24
// Примечание: Этот файл использует сторонний модуль intec.eklectika
// и не является частью собственных разработок

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if ($_REQUEST['event'] == 'ONCRMREQUISITEUPDATE') {
    $file = $_SERVER['DOCUMENT_ROOT'].'/script/crm/logs/requisite.txt';
    file_put_contents($file, date('Y.m.d').PHP_EOL, FILE_APPEND);
    file_put_contents($file, 'Запрос из Б24 на работу реквизитов ID - '.$_REQUEST['data']['FIELDS']['ID'].PHP_EOL, FILE_APPEND);

    // Получение реквизитов из B24
    $companyB24 = sendRequestB24("crm.requisite.get", [
        'id' =>  $_REQUEST['data']['FIELDS']['ID']
    ]);    
    
    // Логика обработки реквизитов (использует сторонний модуль)
}
```

### 5. Синхронизация пользователей
**Файл**: `local/modules/eklektika.b24.usersync/lib/User.php`

#### Основные методы:
```php
// Получение пользователя по B24 ID
public function getUserIDByB24ID($b24_id) {
    $rsUser = \CUser::GetList([], ["XML_ID" => $b24_id]);
    if ($arUser = $rsUser->Fetch()) {
        return $arUser['ID'];
    }
    return false;
}

// Получить компанию пользователя
public function getUserCompany($userId = null, $userRole = 'boss', $companyId = null) {
    // Определяем фильтр в зависимости от роли
    $filter = [
        'IBLOCK_ID' => 57,
        'ACTIVE' => 'Y'
    ];

    if ($userRole === 'boss') {
        $filter['PROPERTY_OS_COMPANY_BOSS'] = $userId;
    } else {
        $filter['PROPERTY_OS_COMPANY_USERS'] = $userId;
    }

    // Получаем компанию пользователя
    $rsCompany = \CIBlockElement::GetList(
        [], $filter, false, false,
        ['ID', 'NAME', 'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING', 
         'PROPERTY_OS_HOLDING_OF', 'PROPERTY_OS_COMPANY_B24_ID',
         'PROPERTY_OS_HEAD_COMPANY_B24_ID']
    );

    return ($company = $rsCompany->GetNext()) ? $company : false;
}

// Проверить, является ли пользователь руководителем компании
public function isCompanyBoss($userId = null) {
    return $this->getUserCompany($userId, 'boss', $companyId) !== false;
}

// Получить ID головной компании холдинга
public function getHeadCompanyId($userId = null) {
    $company = $this->getUserCompany($userId, 'boss', $companyId);
    if (!$company) return false;

    // Логика определения головной компании холдинга
    if (!empty($company['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'])) {
        return $company['PROPERTY_OS_HEAD_COMPANY_B24_ID_VALUE'] ?: 
               $company['PROPERTY_OS_COMPANY_B24_ID_VALUE'];
    }
    
    if (!empty($company['PROPERTY_OS_HOLDING_OF_VALUE'])) {
        return $company['PROPERTY_OS_HOLDING_OF_VALUE'];
    }

    return $company['PROPERTY_OS_COMPANY_B24_ID_VALUE'];
}
```

// Создание пользователя из данных B24
public function createUserFromB24($fields) {
    if (empty($fields['B24_ID'])) {
        return false;
    }

    $b24ID = $fields['B24_ID'];
    unset($fields['B24_ID']);

    $user = new \CUser;
    $userFields = [
        'NAME' => $fields['NAME'],
        'LAST_NAME' => $fields['LAST_NAME'],
        'EMAIL' => $fields['EMAIL'],
        'LOGIN' => $fields['EMAIL'],
        'PASSWORD' => $fields['EMAIL'], // Временный пароль
        'CONFIRM_PASSWORD' => $fields['EMAIL'],
        'ACTIVE' => 'Y',
        'XML_ID' => $b24ID, // Связь с B24
        'UF_ADVERSTERING_AGENT' => $fields['IS_MARKETING_AGENT'] ?? 0,
    ];

    $userId = $user->Add($userFields);
    
    if ($userId) {
        // Обновление типа цены для маркетинговых агентов
        $this->updateMarketingAgentPriceType($fields['IS_MARKETING_AGENT'], $userId);
        return $userId;
    }
    
    return false;
}

// Синхронизация компаний пользователя
public function syncUserCompanies($userId) {
    // Получение компаний пользователя из B24
    $rsCompany = CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => 57,
            'PROPERTY_OS_COMPANY_USERS' => $userId,
            'ACTIVE' => 'Y'
        ],
        false, false,
        ['ID', 'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING', 'PROPERTY_OS_HOLDING_OF']
    );

    // Обработка холдингов и назначение руководителя
    if ($userCompany = $rsCompany->GetNext()) {
        if (!empty($userCompany['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'])) {
            // Головная компания - обновляем все дочерние
            $this->updateHoldingCompanies($userCompany['ID'], $userId);
        } else if (!empty($userCompany['PROPERTY_OS_HOLDING_OF_VALUE'])) {
            // Дочерняя компания - обновляем холдинг
            $holdingId = $userCompany['PROPERTY_OS_HOLDING_OF_VALUE'];
            $this->updateHoldingCompanies($holdingId, $userId);
        }
        
        // Назначение руководителя
        $el = new \CIBlockElement;
        $companyUpdated = $el->SetPropertyValues(
            $companyId, 57, [$this->userId], "OS_COMPANY_BOSS"
        );
    }
}
```

### 6. Файловая интеграция
**Файл**: `local/modules/eklektika.company/lib/Company.php` (модуль `eklektika.company`)

#### Скачивание и обработка файлов:
```php
// Обработка файла реквизитов при обновлении компании
if ($params['OS_REQUSITES_FILE'] && !empty($params['OS_REQUSITES_FILE'])) {
    $downloadableUrl = URL_B24.$params['OS_REQUSITES_FILE']['SUBDIR'].'/'.urlencode($params['OS_REQUSITES_FILE']['FILE_NAME']);

    // Директория для сохранения
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/os_requisites/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $originalName = $params['OS_REQUSITES_FILE']['ORIGINAL_NAME'];
    $filePath = $uploadDir . $originalName;

    // Скачивание файла из B24
    $fileContent = file_get_contents($downloadableUrl);

    if ($fileContent === false) {
        pre($downloadableUrl);
        die("Не удалось скачать файл. Проверь URL и доступ к B24.");
    }

    // Сохранение на сервер
    $content = file_put_contents($filePath, $fileContent);

    if ($content && file_put_contents($filePath, $content)) {
        // Загрузка файла в Битрикс
        $fileArray = \CFile::MakeFileArray($filePath, false, $originalName);

        if ($fileArray && !isset($fileArray['error'])) {
            // Сохранение в систему Битрикс
            $savedFileId = \CFile::SaveFile($fileArray, 'os_requisites');

            if ($savedFileId) {
                $params['OS_REQUSITES_FILE'] = $savedFileId;
            }

            // Удаление временного файла
            unlink($filePath);
        } else {
            echo 'Ошибка загрузки файла: ' . ($fileArray['error'] ?? 'неизвестная ошибка');
        }
    }
}
```

### 7. Веб-хуки для заказов и сделок
**Файл**: `local/php_interface/init.php`

#### Отправка данных в B24:
```php
// Функция отправки webhook в B24 (префикс URL из конфига — см. RestClient::getKitWebhookPrefix())
function sendWebhookToB24($method, $dealId, $data = []) {
    $webhook = \OnlineService\B24\RestClient::getKitWebhookPrefix();
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhook . $method . $dealId);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    
    if (!empty($data)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    return [
        'result' => $result,
        'http_code' => $httpCode
    ];
}
```

## Логика работы

### Регистрация пользователя:
1. **Проверка существования** - поиск пользователя в B24 по email
2. **Создание контакта** - отправка данных в B24 CRM
3. **Создание компании** - если это юридическое лицо или рекламный агент
4. **Привязка контакта к компании** - установка связей в B24
5. **Создание на сайте** - создание элемента компании в инфоблоке
6. **Загрузка файлов** - обработка реквизитов и документов

### Синхронизация из B24 (B24 → Сайт):
1. **Веб-хук получает событие** - ONCRMCONTACTADD, ONCRMCONTACTUPDATE и т.д.
2. **Получение данных** - запрос к B24 API для получения полной информации
3. **Поиск на сайте** - поиск существующего пользователя/компании
4. **Создание/обновление** - создание нового или обновление существующего
5. **Управление статусами** - блокировка, назначение агентов, руководителей
6. **Логирование** - запись всех операций в лог-файлы

### Синхронизация в B24 (Сайт → B24):
**Файл**: `local/components/online-service/user.profile.edit/class.php`

1. **Редактирование профиля** - пользователь изменяет данные через личный кабинет
2. **Сохранение на сайте** - данные сначала сохраняются в Битрикс
3. **Проверка связи с B24** - проверяется наличие `UF_B24_USER_ID`
4. **Формирование данных** - собираются только измененные поля
5. **Отправка в B24** - вызов `crm.contact.update` через REST API
6. **Обработка ошибок** - ошибки синхронизации логируются, но не блокируют сохранение

**Синхронизируемые поля:**
- `NAME` → `NAME` (Имя)
- `LAST_NAME` → `LAST_NAME` (Фамилия)
- `WORK_POSITION` → `POST` (Должность)
- `PERSONAL_PHONE` → `PHONE[MOBILE]` (Личный телефон)
- `WORK_PHONE` → `PHONE[WORK]` (Рабочий телефон)
- `PERSONAL_MOBILE` → `PHONE[MOBILE]` (Мобильный)
- `EMAIL` → `EMAIL[WORK]` (Email)

**Особенности реализации:**
- Синхронизация происходит **после** успешного сохранения на сайте
- Ошибки синхронизации с B24 не прерывают процесс (fail-safe)
- Отправляются только измененные поля для оптимизации
- Try-catch блок предотвращает падение при недоступности B24

### Файловая интеграция:
1. **Загрузка на сайт** - файл сохраняется в системе Битрикс
2. **Кодирование в base64** - для передачи в B24
3. **Отправка в B24** - через REST API
4. **Скачивание из B24** - автоматическое скачивание при обновлении
5. **Сохранение на сервер** - в директорию upload/os_requisites/

## Связанные поля и сущности

### Поля связи с B24:
- `XML_ID` - ID пользователя в B24
- `OS_COMPANY_B24_ID` - ID компании в B24
- `OS_HEAD_COMPANY_B24_ID` - ID головной компании в B24
- `UF_CRM_*` - пользовательские поля CRM

### События B24:
- `ONCRMCONTACTADD` - добавление контакта
- `ONCRMCONTACTUPDATE` - обновление контакта
- `ONCRMCOMPANYADD` - добавление компании
- `ONCRMCOMPANYUPDATE` - обновление компании
- `ONCRMREQUISITEUPDATE` - обновление реквизитов
- `ONUSERUPDATE` - обновление пользователя

### Логи и мониторинг:
- `script/crm/logs/user.add.txt` - логи добавления пользователей
- `script/crm/logs/company.txt` - логи работы с компаниями
- `script/crm/logs/requisite.txt` - логи работы с реквизитами

## Безопасность и производительность

### Безопасность:
- SSL-соединения с отключенной проверкой сертификатов (для разработки)
- Валидация входящих данных от B24
- Логирование всех операций для аудита
- Проверка прав доступа при синхронизации

### Производительность:
- Таймауты для CURL запросов (30 сек)
- Обработка ошибок сети и API
- Логирование для отладки
- Асинхронная обработка веб-хуков

### ⚠️ Потенциальная проблема: Циклическая синхронизация

**Описание проблемы:**
При двусторонней синхронизации возможна ситуация:
1. Пользователь редактирует профиль на сайте
2. Данные отправляются в B24 (`crm.contact.update`)
3. B24 генерирует событие `ONCRMCONTACTUPDATE`
4. Веб-хук обновляет данные на сайте
5. Цикл может повториться

**Решение:**
Текущая реализация безопасна, т.к.:
- Веб-хук в `script/crm/rest/contact.php` обрабатывается отдельным сценарием (вне зоны правок собственных классов в `local/classes/`)
- Перед обновлением проверяются изменения
- Если данные не изменились, обновление не происходит
- **Рекомендация**: При активной работе с двусторонней синхронизацией добавить флаг `SKIP_WEBHOOK=1` в метаданные обновления для пропуска веб-хука

## См. также

- [Карта сегментов `local/classes` и предлагаемые модули `eklektika.*`](./local_classes_segments_and_modules.md) — где лежит REST-слой, синхронизация пользователя с CRM и границы относительно общего плана рефакторинга (ST-01).

## Примечания

### Особенности реализации:
1. **Двусторонняя синхронизация** - данные передаются в обе стороны
2. **Автоматическая обработка файлов** - загрузка и скачивание документов
3. **Управление статусами** - автоматическое назначение ролей и прав
4. **Система холдингов** - сложная логика управления группами компаний
5. **Логирование** - подробные логи всех операций для отладки

### Настройки:
- Базовый URL портала и токены входящих вебхуков задаются только в **`local/php_interface/b24_integration_config.php`** (шаблон без секретов: **`b24_integration_config.example.php`**). Реальные значения **не** дублируются в этой документации.
- Переключение тестового/боевого портала: флаг **`$useTestPortal`** в том же файле конфигурации.
- Таймауты HTTP-транспорта (`RestClient`): 30 с на запрос, 10 с на соединение.

### Deployment и secrets (ST-11 hardening):

1. В репозитории хранится только шаблон **`local/php_interface/b24_integration_config.example.php`**.
2. Рабочий секретный файл **`local/php_interface/b24_integration_config.php`** создаётся на сервере деплоя из шаблона и не является обязательным git-артефактом.
3. Bootstrap в **`local/php_interface/init.php`** использует безопасный контракт:
   - сначала проверяется наличие файла через `file_exists`;
   - при отсутствии файла применяется controlled fallback (пустые значения `URL_B24`, `B24_REST_WEBHOOK_MAIN`, `B24_REST_WEBHOOK_KIT`);
   - константы остаются определёнными всегда, поэтому runtime-контракт для модулей не ломается.
4. Проверка после выката:
   - убедиться, что `b24_integration_config.php` присутствует на целевом стенде и содержит реальные значения;
   - если файл временно отсутствует, сайт не падает фатально, а B24 REST-вызовы ожидаемо становятся no-op/ошибочными по месту вызова;
   - убедиться, что секреты не попали в git (`git status`/`git check-ignore local/php_interface/b24_integration_config.php`).

### Мониторинг:
- Лог-файлы в `script/crm/logs/`
- Обработка ошибок CURL и HTTP
- Детальное логирование всех операций
- Возможность включения debug-режима
