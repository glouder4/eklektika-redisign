# Документация: Система компаний и холдингов

## Описание
Система управления компаниями-клиентами с поддержкой холдингов, интеграцией с Bitrix24 CRM и управлением пользователями компаний.

## Основные характеристики
- **Инфоблок компаний**: 57 (основной)
- **Интеграция с Bitrix24**: Синхронизация контактов и компаний
- **Система холдингов**: Поддержка головных компаний и дочерних структур
- **Управление пользователями**: Привязка пользователей к компаниям
- **Руководители компаний**: Система назначения боссов

## Места использования и реализации

### 1. Основной класс Company
**Файл**: `local/modules/eklektika.company/lib/Company.php`  
**Модуль**: `eklektika.company`; bootstrap: в [`local/classes/requires.php`](../../local/classes/requires.php) порядок `require_once` для `include.php` модулей сохраняется: `eklektika.b24.rest` → `eklektika.company` → `eklektika.catalog.pricing` → `eklektika.site` → `eklektika.catalog.import` → `eklektika.orders.applications` → `eklektika.b24.usersync`; при отсутствии `include.php` срабатывает `E_USER_WARNING` (без фатала), затем ранний вызов **`CatalogPriceFloor::markCompositeNonCacheableForAuthorizedCatalog()`**. Namespace класса **`OnlineService\Site\Company`** без изменений до ST-09.

CRM-транспорт и field-mapping для сценариев компании централизованы:
- вызовы B24 выполняются через `\OnlineService\B24\RestClient::callRestMethod()` (через локальный адаптер в `Company`);
- CRM/UF-константы вынесены в `local/modules/eklektika.company/lib/Config/CompanyB24Config.php`.
Это сохраняет BC по бизнес-веткам (`updateCompanyElement`, `updateCompanyProfile`, `createBranchCompany`) и убирает дублирование хардкода полей.

Контракт для ценообразования: статический метод **`Company::getMaxCompanyDiscountPercentForUserGroups(array $userGroupIds): float`** — узкая точка зависимости модуля **`eklektika.catalog.pricing`** (`CatalogPriceFloor`) от домена компании.

Fail-safe для шаблонов ЛК в период миграции в `eklektika.*`: `local/templates/universe_s1/components/bitrix/sale.personal.section/template.1/parts/personal-widgets.php` использует единый guard-поток `authorized && class_exists('\OnlineService\Site\Company')` перед вызовом контракта скидки; при недоступности класса сохраняется безопасный fallback `0%` без остановки рендера. Инвариант архитектуры: шаблоны не выполняют прямой `require/include` модульных `include.php`.

#### Свойства компаний:
```php
private static $codeProps = [
    "OS_COMPANY_IS_HEAD_OF_HOLDING",    // Головная компания холдинга
    "OS_COMPANY_BOSS",                  // Руководитель компании
    "OS_HEAD_COMPANY_B24_ID",           // ID головной компании в B24
    "OS_HOLDING_OF",                    // Принадлежность к холдингу
    "OS_COMPANY_INN",                   // ИНН компании
    "OS_COMPANY_WEB_SITE",              // Сайт компании
    "OS_COMPANY_USERS",                 // Связанные пользователи
    "OS_COMPANY_NAME",                  // Название компании
    "OS_COMPANY_PHONE",                 // Телефон
    "OS_COMPANY_EMAIL",                 // Email
    "OS_COMPANY_B24_ID",                // ID в Bitrix24
    "OS_COMPANY_CITY",                  // Город
    "OS_IS_MARKETING_AGENT",            // Маркетинговый агент
    "OS_IS_COMPANY_DISABLED",           // Статус активности
    "OS_COMPANY_STATUS",                // Статус компании
    "OS_REQUSITES_FILE"                 // Файл реквизитов
];
```

#### Создание компании:
```php
public function createCompanyElement($params){
    // Поиск существующей компании по B24_ID
    $existingCompany = $this->getCompanyByB24ID($params['OS_COMPANY_B24_ID']);
    
    if ($existingCompany && !empty($existingCompany['ID'])) {
        // Компания найдена - добавляем пользователя
        $currentUsers = $existingCompany['OS_COMPANY_USERS'] ?? [];
        if (!in_array($params['USER_ID'], $currentUsers)) {
            $currentUsers[] = $params['USER_ID'];
        }
        // Обновляем список пользователей
        \CIBlockElement::SetPropertyValues(
            $companyId, $this->iblock_id, $currentUsers, 'OS_COMPANY_USERS'
        );
    } else {
        // Создаем новую компанию
        $el = new \CIBlockElement;
        $params['OS_COMPANY_USERS'] = [$params['USER_ID']];
        
        $arLoadProductArray = [
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_TYPE" => 'personal',
            "IBLOCK_ID" => $this->iblock_id,
            "PROPERTY_VALUES" => $params,
            "NAME" => $params["OS_COMPANY_NAME"],
            "ACTIVE" => "N",
            "CODE" => $params["OS_COMPANY_B24_ID"]
        ];
        
        $companyId = $el->Add($arLoadProductArray);
    }
}
```

#### Обновление компании:
```php
public function updateCompanyElement($params){
    // Поиск компании по B24_ID
    $company = $this->getCompanyByB24ID($params['OS_COMPANY_B24_ID']);
    
    if ($company && !empty($company['ID'])) {
        // Обработка связей с холдингом
        if( !empty($params['OS_HOLDING_OF']) && $params['OS_HOLDING_OF'] ){
            $params['OS_HOLDING_OF'] = $this->getCompanyByB24ID($params['OS_HOLDING_OF']);
        }
        
        // Получение текущих значений всех свойств
        $currentProps = [];
        foreach (self::$codeProps as $code) {
            $propertyValues = \CIBlockElement::GetProperty(
                $this->iblock_id, $companyId, [], ["CODE" => $code]
            );
            // ... получение значений ...
        }
        
        // Объединение текущих и новых значений
        $arProps = $currentProps;
        foreach (self::$codeProps as $code) {
            if (isset($params[$code])) {
                $arProps[$code] = $params[$code];
            }
        }
        
        // Обновление элемента
        $arUpdateArray = [
            "PROPERTY_VALUES" => $arProps,
            "NAME" => $params["OS_COMPANY_NAME"],
            "ACTIVE" => $params['ACTIVE'],
        ];
        
        $el->Update($companyId, $arUpdateArray);
    }
}
```

### 2. Добавление новых филиалов
**Файл**: `director/add_new_branch-action.php`

#### Создание филиала:
```php
$companyElementParamss = [
    'OS_COMPANY_B24_ID' => $arResult['COMPANY_ID'],
    'OS_COMPANY_NAME' => $_POST['company_name'],
    'OS_COMPANY_INN' => $_POST['company_inn'],
    'OS_COMPANY_EMAIL' => $_POST['company_email'],
    'OS_COMPANY_PHONE' => $_POST['company_phone'],
    'OS_COMPANY_CITY' => $_POST['company_city'],
    'USER_ID' => $USER->GetID(),
    'PROPERTY_OS_COMPANY_BOSS' => $USER->GetID(), // Назначение руководителем
];

$company->createCompanyElement($companyElementParamss);

// Поиск головной компании
$rsHeadCompany = CIBlockElement::GetList(
    [],
    ['CODE' => $_POST['head_company_id']],
    false, false,
    ['ID', 'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING', 'PROPERTY_OS_HOLDING_OF',
     'PROPERTY_OS_COMPANY_B24_ID','PROPERTY_OS_HEAD_COMPANY_B24_ID']
);

$headCompany = $rsHeadCompany->GetNext();

// Привязка к холдингу
if( $_POST['head_company_id'] != $headCompany['PROPERTY_OS_HEAD_COMPANY_B24_ID_VALUE'] ){
    // Обновление связи с холдингом
}
```

### 3. Система холдингов
**Файл**: `local/templates/universe_s1/components/bitrix/sale.personal.section/template.1/parts/companies.php`

#### Логика работы с холдингами:
```php
// Получение компаний пользователя
$rsCompany = CIBlockElement::GetList(
    [],
    [
        'IBLOCK_ID' => 57,
        'PROPERTY_OS_COMPANY_USERS' => $USER->GetID(),
        'ACTIVE' => 'Y'
    ],
    false, false,
    ['ID', 'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING', 'PROPERTY_OS_HOLDING_OF']
);

$userCompany = $rsCompany->GetNext();
$companyIds = [];

if ($userCompany) {
    // Сценарий 1: Головная компания холдинга
    if (!empty($userCompany['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE']) && 
        ($userCompany['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'] === 'Y')) {
        
        // Получаем все компании холдинга
        $rsHoldingCompanies = CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => 57,
                'PROPERTY_OS_HOLDING_OF' => $userCompany['ID'],
                'ACTIVE' => 'Y'
            ],
            false, false, ['ID']
        );
        
        while ($holdingCompany = $rsHoldingCompanies->GetNext()) {
            $companyIds[] = $holdingCompany['ID'];
        }
        
        // Добавляем саму головную компанию
        $companyIds[] = $userCompany['ID'];
        
    } 
    // Сценарий 2: Обычная компания в холдинге
    else if (!empty($userCompany['PROPERTY_OS_HOLDING_OF_VALUE'])) {
        
        $holdingId = $userCompany['PROPERTY_OS_HOLDING_OF_VALUE'];
        
        // Получаем все компании этого холдинга
        $rsHoldingCompanies = CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => 57,
                'PROPERTY_OS_HOLDING_OF' => $holdingId,
                'ACTIVE' => 'Y'
            ],
            false, false, ['ID']
        );
        
        while ($holdingCompany = $rsHoldingCompanies->GetNext()) {
            $companyIds[] = $holdingCompany['ID'];
        }
    }
}
```

### 4. Интеграция с Bitrix24
**Файл**: `local/modules/eklektika.b24.usersync/lib/User.php`

#### Получение компании пользователя:
```php
// Получить компанию пользователя
public function getUserCompany($userId = null, $userRole = 'boss', $companyId = null) {
    if ($userId === null) {
        $userId = $this->userId;
    }

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
        [],
        $filter,
        false,
        false,
        [
            'ID', 
            'NAME',
            'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING', 
            'PROPERTY_OS_HOLDING_OF',
            'PROPERTY_OS_COMPANY_B24_ID',
            'PROPERTY_OS_HEAD_COMPANY_B24_ID'
        ]
    );

    if ($company = $rsCompany->GetNext()) {
        return $company;
    }

    return false;
}

// Проверить, является ли пользователь руководителем компании
public function isCompanyBoss($userId = null) {
    $company = $this->getUserCompany($userId, 'boss');
    return $company !== false;
}

// Получить ID головной компании холдинга для пользователя
public function getHeadCompanyId($userId = null) {
    $company = $this->getUserCompany($userId, 'boss', $companyId);
    
    if (!$company) {
        return false;
    }

    // Если это головная компания
    if (!empty($company['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE']) && 
        ($company['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'] === 'Y' || 
         $company['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'] === 'Да')) {
        
        return $company['PROPERTY_OS_HEAD_COMPANY_B24_ID_VALUE'] ?: $company['PROPERTY_OS_COMPANY_B24_ID_VALUE'];
    }
    
    // Если это дочерняя компания
    if (!empty($company['PROPERTY_OS_HOLDING_OF_VALUE'])) {
        return $company['PROPERTY_OS_HOLDING_OF_VALUE'];
    }

    // Если нет связей с холдингом
    return $company['PROPERTY_OS_COMPANY_B24_ID_VALUE'];
}
```


## Логика работы

### Создание компании:
1. Поиск существующей компании по `OS_COMPANY_B24_ID`
2. Если найдена - добавление пользователя в `OS_COMPANY_USERS`
3. Если не найдена - создание новой компании с привязкой пользователя
4. Установка статуса "Неактивна" (`ACTIVE = "N"`)

### Обновление компании:
1. Поиск компании по `OS_COMPANY_B24_ID`
2. Получение текущих значений всех свойств
3. Объединение текущих и новых значений (сохранение непереданных полей)
4. Обработка связей с холдингами
5. Обновление элемента в инфоблоке

### Система холдингов:
1. **Головная компания** (`OS_COMPANY_IS_HEAD_OF_HOLDING = Y`):
   - Пользователь видит все компании холдинга
   - Может управлять дочерними компаниями
   
2. **Дочерняя компания** (`OS_HOLDING_OF` заполнено):
   - Пользователь видит все компании того же холдинга
   - Ограниченные права управления

### Управление пользователями:
1. **Добавление пользователя**:
   - Поиск существующих пользователей в `OS_COMPANY_USERS`
   - Добавление нового ID без дублирования
   
2. **Назначение руководителя**:
   - Установка поля `OS_COMPANY_BOSS`
   - Автоматическое назначение при создании филиала

### Синхронизация групп пользователя при апдейте компании из B24

В **`Company::applyB24CompanyGroupsToUser()`** (вызывается из `updateCompanyElement` / `createCompanyFromUpdate` при обходе `OS_COMPANY_USERS`):

- группы скидки по маппингу компании **снимаются и выставляются заново только если в payload явно есть ключ** `OS_COMPANY_DISCOUNT_VALUE` (`array_key_exists`);
- если ключ отсутствует (частичное обновление компании или побочная цепочка после других событий CRM), **текущие скидочные группы пользователя не изменяются**;
- признак маркетингового агента компании (`OS_IS_MARKETING_AGENT`) по-прежнему может добавить пользователя в группу агента через `addUserToGroups`, не затрагивая скидку при отсутствии ключа скидки.

## Архитектурные зависимости (ST-10)

- Модуль `eklektika.company` допускает зависимость только на Bitrix core и транспорт `eklektika.b24.rest`.
- Зависимость `eklektika.catalog.pricing -> eklektika.company` зафиксирована как узкий контракт: `Company::getMaxCompanyDiscountPercentForUserGroups`.
- Обратная зависимость `eklektika.company -> eklektika.catalog.pricing` запрещена.
- Временное исключение для совместимости: `eklektika.b24.usersync` обращается к `\OnlineService\Site\Company` при регистрации/синхронизации пользователя (исторический сценарий создания компании на сайте из CRM-данных). Исключение задокументировано до выделения отдельного фасада.
- Implement-ready follow-up: `FU-ST11-USERSYNC-COMPANY-GATEWAY` (ST-11 stabilization) — owner: architecture/refactoring team (maintainers usersync + company), deadline/condition: до `2026-05-29` или в первый следующий code-touch usersync/company; критерий закрытия: usersync использует только узкий gateway/контракт без прямой зависимости на доменный `Company`.

## Связанные поля и сущности

### Поля компаний:
- `OS_COMPANY_B24_ID` - Уникальный идентификатор в Bitrix24
- `OS_COMPANY_NAME` - Название компании
- `OS_COMPANY_INN` - ИНН компании
- `OS_COMPANY_USERS` - Множественное поле с ID пользователей
- `OS_COMPANY_BOSS` - ID руководителя компании
- `OS_COMPANY_IS_HEAD_OF_HOLDING` - Флаг головной компании
- `OS_HEAD_COMPANY_B24_ID` - ID головной компании в B24
- `OS_HOLDING_OF` - Связь с холдингом (ID компании)
- `OS_IS_MARKETING_AGENT` - Маркетинговый агент
- `OS_REQUSITES_FILE` - Файл с реквизитами

### Связанные сущности:
- **Пользователи** - через поле `OS_COMPANY_USERS`
- **Заказы** - через ИНН компании в свойствах заказа
- **Bitrix24 CRM** - синхронизация контактов и компаний
- **Файловая система** - хранение реквизитов компаний

## Инфоблоки

### Инфоблок 57 (основной):
- Тип: `personal`
- Используется для основных компаний
- Содержит полную информацию о компании
- Интегрирован с Bitrix24

### Инфоблок 52 (рекламные агенты):
- Тип: `personal`
- Упрощенная структура для рекламных агентов
- Основные поля: ИНН, название, контакты, сотрудники

## См. также

- [Карта сегментов `local/classes`](./local_classes_segments_and_modules.md) — как сегмент «компании и холдинги» сочетается с остальными доменами и B24 (ST-01).

## Примечания

### Особенности реализации:
1. **Двойная система**: Два инфоблока для разных типов компаний
2. **Сохранение данных**: При обновлении сохраняются непереданные поля
3. **Холдинги**: Сложная логика определения доступных компаний
4. **Интеграция B24**: Автоматическая синхронизация с CRM
5. **Файлы**: Автоматическое скачивание и сохранение реквизитов

### Безопасность:
- Проверка принадлежности пользователя к компании
- Ограничение прав доступа в зависимости от роли
- Валидация данных при создании/обновлении

### Производительность:
- Индексация по `OS_COMPANY_B24_ID` для быстрого поиска
- Кэширование результатов запросов
- Оптимизация запросов к инфоблокам
