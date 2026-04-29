# Документация: Система групп пользователей

## ⚠️ ВАЖНО: Собственная реализация

### Файлы СОБСТВЕННОЙ разработки:
- `local/modules/eklektika.b24.usersync/lib/User.php` - основной класс для работы с пользователями
- `local/modules/eklektika.company/lib/UserGroups.php` - класс для управления группами

### ❌ НЕ ИСПОЛЬЗОВАТЬ сторонний модуль:
- `local/modules/intec.eklectika/` - **СТОРОННИЙ МОДУЛЬ**, не документируется и не изменяется

---

## Описание
Система управления группами пользователей с автоматическим добавлением/удалением пользователей из групп на основе их статусов и ролей.

## Основные характеристики

### Константы групп (в классе `OnlineService\B24\User`):
```php
public int $MARKETING_AGENT_GROUP_ID = 12;   // Рекламные агенты
public int $DIRECTOR_GROUP_ID = 432;         // Руководители компаний
```

### Базовые группы:
- **Группа 12** - Рекламные агенты
- **Группа 432** - Руководители компаний
- **Группы 3, 4, 7** - Базовые группы пользователей

## Места использования и реализации

### 1. Класс User (local/modules/eklektika.b24.usersync/lib/User.php)

#### Получение групп пользователя (`getUserGroups`)

**Источник истины:** **`CUser::GetUserGroup($userId)`**, затем нормализация в список целых уникальных ID (`normalizeUserGroupIds`). Поле **`GROUPS_ID` из `CUser::GetByID`** для этой цели **не используется** — в Bitrix оно может быть неполным относительно фактического членства; передача урезанного списка в **`CUser::Update(..., ['GROUP_ID' => ...])`** приводила к потере групп (в т.ч. скидочных) при повторном включении рекламного агента.

#### Добавление пользователя в группу (`addUserToGroup`)

Членство меняется через **`CUser::SetUserGroup`** на основе полного списка из **`GetUserGroup`** (merge + нормализация; при необходимости сохраняется группа администраторов). Поля **`UF_ADVERSTERING_AGENT`** и **`ACTIVE`** для сценария рекламного агента выставляются **отдельным** **`CUser::Update` без `GROUP_ID` / `GROUPS_ID`**, чтобы не перезаписывать группы урезанным массивом.

#### `removeUserFromGroup($userId, $groupId)` (legacy в `User.php`)

В файле по-прежнему есть метод **`removeUserFromGroup`**, опирающийся на **`GetByID` / `GROUPS_ID`** и **`CUser::Update` с `GROUP_ID`** — тот же класс риска урезания членства, что описан выше для старого **`addUserToGroup`**. **В репозитории метод нигде не вызывается**; для новых сценариев использовать **`removeUserFromGroupsByIds()`** или пересборку через **`CUser::GetUserGroup` + `CUser::SetUserGroup`**.

#### Получение списка пользователей в группе:
```php
/**
 * Получить список ID пользователей в группе
 * @param int $groupId ID группы
 * @return array Массив ID пользователей
 */
public function getUsersInGroup($groupId){
    $userIds = array();
    
    // Получаем список пользователей в группе
    $rsUsers = \CUser::GetList(
        array('ID' => 'ASC'),
        array('ASC'),
        array('GROUPS_ID' => $groupId),
        array('SELECT' => array('ID'))
    );
    
    while ($user = $rsUsers->Fetch()) {
        $userIds[] = $user['ID'];
    }
    
    return $userIds;
}
```

### 2. Автоматическое управление группой руководителей

**Файл**: `local/modules/eklektika.b24.usersync/lib/User.php`, метод `update()`, только при **`ACTION === 'UPDATE_CONTACT'`** и **`array_key_exists('UF_IS_DIRECTOR', $fields)`**. Если CRM прислала частичный payload (например, меняют только рекламного агента) **без** ключа `UF_IS_DIRECTOR`, ветка руководителя **не выполняется** — группа **432** и прочие группы из этого сценария не трогаются.

#### При установке галочки UF_IS_DIRECTOR (явные «включено» из CRM: `1`, `'1'`, `'Y'`, `'y'`, `'Да'`, `true` — см. `isCrmDirectorFlagOn()` в `User.php`):

- Текущие группы нормализуются в список целых ID (`normalizeUserGroupIds`).
- Если **432** ещё нет в списке — **`SetUserGroup`** с объединением текущих групп и **432** (снова нормализация), без «дырок» в индексах массива.

#### При снятии галочки (`!$fields['UF_IS_DIRECTOR']`, ключ при этом **есть**):

- Снимается только **432** через **`array_diff`** от нормализованного списка, затем **`ensureCompanyDiscountGroupsPreserved`**: любая группа, которая была у пользователя **до** операции и входит в ключи **`CompanyModuleConfig::getCompanyDiscountPercentByAssignedGroupId()`** (боевой или тестовый набор по `B24_USE_TEST_PORTAL`), остаётся в финальном списке для **`SetUserGroup`**.

Идентификаторы скидочных групп задаются в **`local/modules/eklektika.company/lib/Config/CompanyModuleConfig.php`** (`PROD_*` / `TEST_*`); usersync только **читает** этот конфиг для инварианта «не потерять скидочные группы» при операции с **432**.

### 3. Управление группой рекламных агентов

#### Автоматическое управление при изменении UF_ADVERSTERING_AGENT:
**Файл**: `local/modules/eklektika.b24.usersync/lib/User.php`, метод `updateMarketingAgentPriceType()`

При снятии флага агента: сначала **`removeUserFromGroupsByIds()`** (только снятие группы агента), затем отдельный **`CUser::Update()`** с полями **`ACTIVE = 'N'`** и **`UF_ADVERSTERING_AGENT = 0`** без **`GROUP_ID` / `GROUPS_ID`**. Так инвариант **`UPDATE_CONTACT`**: группы на этом шаге трогаются только как группа агента; деактивация и сброс UF — вторым запросом без пересборки групп через `CUser::Update`.

```php
} elseif (!$shouldBeInGroup && $isUserInGroup) {
    if (!$this->removeUserFromGroupsByIds((int)$userId, [$this->MARKETING_AGENT_GROUP_ID])) {
        return false;
    }
    $u = new \CUser();
    return (bool)$u->Update((int)$userId, [
        'ACTIVE' => 'N',
        'UF_ADVERSTERING_AGENT' => 0,
    ]);
}
```

**`removeUserFromGroup()`** для снятия агента **не** вызывается: в нём группы и поля `UF_ADVERSTERING_AGENT` / `ACTIVE` смешаны в одном `CUser::Update` с `GROUP_ID`, что нежелательно для контролируемого сценария снятия агента.

### 4. Класс UserGroups (local/modules/eklektika.company/lib/UserGroups.php)

#### Создание/обновление группы:
```php
namespace OnlineService\Site;

class UserGroups{
    private array $request;
    private ?int $group_id = null;
    
    // Поиск группы по STRING_ID
    public function searchGroup($id = null){
        $rsGroups = \CGroup::GetList($by = "c_sort", $order = "asc", array(
            'STRING_ID' => !is_null($id) ? "GROUP_".$id : "GROUP_".$this->request['ID']
        ));
        
        return $rsGroups->Fetch() ?? false;
    }
    
    // Создание группы
    private function createGroup(){
        $group = new \CGroup;
        $arFields = Array(
            "ACTIVE"       => $this->request['ACTIVE'],
            "C_SORT"       => $this->request['C_SORT'],
            "NAME"         => $this->request['NAME'],
            "DESCRIPTION"  => "",
            "USER_ID"      => array(),
            "STRING_ID"    => "GROUP_".$this->request['ID']
        );
        $NEW_GROUP_ID = $group->Add($arFields);
        
        return $NEW_GROUP_ID;
    }
    
    // Обновление группы
    private function updateGroup(){
        $group = new \CGroup;
        $arFields = Array(
            "ACTIVE"       => $this->request['ACTIVE'],
            "C_SORT"       => $this->request['C_SORT'],
            "NAME"         => $this->request['NAME'],
        );
        $group->Update($this->group_id, $arFields);
    }
}
```

## Логика работы

### Автоматическое управление группами:

1. **При установке UF_IS_DIRECTOR = true**:
   - Пользователь назначается руководителем компаний
   - Автоматически добавляется в группу 432 (Руководители)

2. **При снятии UF_IS_DIRECTOR (false)**:
   - Пользователь удаляется из группы 432 (Руководители)

3. **При установке UF_ADVERSTERING_AGENT**:
   - Пользователь добавляется в группу 12 (Рекламные агенты)
   - Устанавливаются группы: [3, 4, 7, 12]

4. **При снятии UF_ADVERSTERING_AGENT**:
   - Пользователь удаляется из группы 12 через **`removeUserFromGroupsByIds`** (остальные группы не трогаются)
   - Затем отдельно выставляются **`ACTIVE = 'N'`** и **`UF_ADVERSTERING_AGENT = 0`** без передачи групп в `CUser::Update`

### Безопасность работы с группами:

- **Проверка существования**: Перед добавлением проверяется, не находится ли пользователь уже в группе
- **Сохранение групп**: При добавлении/удалении сохраняются все остальные группы пользователя
- **B24 → сайт (`User::update`)**: из внешнего payload игнорируются ключи `GROUP_ID` / `GROUPS_ID`, чтобы не перезаписать группы из CRM-транспорта
- **Логирование**: Все операции с группами логируются через `pre()`

## Связанные поля и сущности

### Поля пользователей:
- `UF_IS_DIRECTOR` - Флаг руководителя компании
- `UF_ADVERSTERING_AGENT` - Флаг рекламного агента
- `UF_B24_USER_ID` - ID контакта в Bitrix24
- `GROUPS_ID` - Массив ID групп пользователя

### Связанные сущности:
- **Группы Bitrix** - через `CGroup`
- **Компании** - через поле `OS_COMPANY_BOSS` (инфоблок 57)
- **Bitrix24 CRM** - синхронизация полей контактов

## Практические примеры

### Пример 1: Назначение пользователя руководителем
```php
$user = new \OnlineService\B24\User();

// При обновлении контакта с UF_IS_DIRECTOR = true
$fields = [
    'B24_ID' => 12345,
    'UF_IS_DIRECTOR' => true,
    'ACTION' => 'UPDATE_CONTACT'
];

$user->update($fields);
// Результат: 
// - Пользователь назначен руководителем компаний
// - Добавлен в группу 432
```

### Пример 2: Проверка принадлежности к группе
```php
$user = new \OnlineService\B24\User();
$userId = 100;

// Получаем все группы пользователя
$userGroups = $user->getUserGroups($userId);

// Проверяем, является ли пользователь руководителем
$isDirector = in_array(432, $userGroups);

if ($isDirector) {
    echo "Пользователь является руководителем";
}
```

### Пример 3: Добавление в группу рекламного агента (только иллюстрация API)

Метод **`addUserToGroup()`** в текущем коде заточен под сценарий **рекламного агента** (группа **12**, выставление **`UF_ADVERSTERING_AGENT`** и **`ACTIVE`**). Для группы **432** используйте поток **`UPDATE_CONTACT` с `UF_IS_DIRECTOR`** или **`CUser::SetUserGroup`**, а не этот метод.

```php
$user = new \OnlineService\B24\User();
$userId = 100;
$groupId = 12; // MARKETING_AGENT_GROUP_ID

$result = $user->addUserToGroup($userId, $groupId);
```

### Пример 4: Получение списка руководителей
```php
$user = new \OnlineService\B24\User();

// Получаем всех пользователей из группы руководителей
$directorIds = $user->getUsersInGroup(432);

echo "Количество руководителей: " . count($directorIds);
```

## Связанные файлы

### Собственная разработка (используется):
- `local/modules/eklektika.b24.usersync/lib/User.php` - основной класс с методами управления группами
- `local/modules/eklektika.company/lib/UserGroups.php` - класс для работы с группами

### Документация:
- `docs/features/company_system.md` - система компаний и холдингов
- `docs/features/uf_advertising_agent.md` - поле рекламного агента
- `docs/features/user_company_methods.md` - методы работы с компаниями

### ⚠️ НЕ ИСПОЛЬЗУЕТСЯ (сторонний модуль):
- `local/modules/intec.eklektika/` - **СТОРОННИЙ МОДУЛЬ**

## Примечания

### Важные особенности:
1. **Автоматизация**: Группы управляются автоматически при изменении полей пользователя
2. **Синхронизация с B24**: Изменения приходят из Bitrix24 CRM
3. **Сохранение существующих групп**: При операциях сохраняются все группы пользователя
4. **Логирование**: Все операции логируются для отладки

### Производительность:
- Группы кэшируются на уровне Bitrix
- Минимальное количество запросов к базе данных
- Проверка существования перед добавлением

### Безопасность:
- Проверка прав доступа через группы
- Валидация данных при операциях с группами
- Защита от дублирования групп

