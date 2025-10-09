# Документация: Система групп пользователей

## ⚠️ ВАЖНО: Собственная реализация

### Файлы СОБСТВЕННОЙ разработки:
- `local/classes/b24/User.php` - основной класс для работы с пользователями
- `local/classes/site/UserGroups.php` - класс для управления группами

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

### 1. Класс User (local/classes/b24/User.php)

#### Получение групп пользователя:
```php
/**
 * Получить список групп пользователя
 * @param int $userId ID пользователя
 * @return array Массив ID групп пользователя
 */
public function getUserGroups($userId){
    $groupIds = array();
    
    // Получаем данные пользователя
    $rsUser = \CUser::GetByID($userId);
    $userData = $rsUser->Fetch();
    
    if ($userData && !empty($userData['GROUPS_ID'])) {
        $groupIds = $userData['GROUPS_ID'];
        if (!is_array($groupIds)) {
            $groupIds = array($groupIds);
        }
    }
    
    return $groupIds;
}
```

#### Добавление пользователя в группу:
```php
/**
 * Добавить пользователя в группу
 * @param int $userId ID пользователя
 * @param int $groupId ID группы
 * @return bool Результат операции
 */
public function addUserToGroup($userId, $groupId){
    $user = (new \CUser);
    // Получаем текущие группы пользователя
    $userGroups = $this->getUserGroups($userId);
    
    // Проверяем, не добавлен ли пользователь уже в эту группу
    if (in_array($groupId, $userGroups)) {
        return true;
    }
    
    // Добавляем новую группу к существующим группам
    $userGroups[] = $groupId;
    
    $arFields = array(
        'GROUP_ID' => $userGroups
    );
    
    $result = (new \CUser)->Update($userId, $arFields);
    
    return $result;
}
```

#### Удаление пользователя из группы:
```php
/**
 * Удалить пользователя из группы
 * @param int $userId ID пользователя
 * @param int $groupId ID группы
 * @return bool Результат операции
 */
public function removeUserFromGroup($userId, $groupId){
    $user = new \CUser();
    
    // Получаем текущие группы пользователя
    $rsUser = \CUser::GetByID($userId);
    $userData = $rsUser->Fetch();
    
    if (!$userData) {
        return false;
    }
    
    // Удаляем группу из списка групп пользователя
    $userGroups = $userData['GROUPS_ID'];
    if (is_array($userGroups)) {
        $userGroups = array_diff($userGroups, array($groupId));
    } else {
        $userGroups = array();
    }
    
    $arFields = array(
        'GROUP_ID' => $userGroups
    );
    
    $result = $user->Update($userId, $arFields);
    
    return $result;
}
```

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

#### При установке галочки UF_IS_DIRECTOR:
**Файл**: `local/classes/b24/User.php`, метод `update()`, строки 492-498

```php
// Добавляем пользователя в группу руководителей (ID: 432)
$userGroups = \CUser::GetUserGroup($this->userId);
if (!in_array($this->DIRECTOR_GROUP_ID, $userGroups)) {
    $userGroups[] = $this->DIRECTOR_GROUP_ID;
    \CUser::SetUserGroup($this->userId, $userGroups);
}
```

#### При снятии галочки UF_IS_DIRECTOR:
**Файл**: `local/classes/b24/User.php`, метод `update()`, строки 499-507

```php
// Убираем пользователя из группы руководителей при снятии галочки
$userGroups = \CUser::GetUserGroup($this->userId);
if (($key = array_search($this->DIRECTOR_GROUP_ID, $userGroups)) !== false) {
    unset($userGroups[$key]);
    \CUser::SetUserGroup($this->userId, $userGroups);
}
```

### 3. Управление группой рекламных агентов

#### Автоматическое управление при изменении UF_ADVERSTERING_AGENT:
**Файл**: `local/classes/b24/User.php`, метод `updateMarketingAgentPriceType()`, строки 313-345

```php
private function updateMarketingAgentPriceType($status, $userId = null){
    // Получаем информацию о группе рекламных агентов
    $rsGroup = \CGroup::GetByID($this->MARKETING_AGENT_GROUP_ID);
    $groupData = $rsGroup->Fetch();

    if( is_null($userId) ){
        $userId = $this->userId;
    }
    
    if (!$groupData) {
        return false;
    }
    
    // Получаем текущий список пользователей в группе
    $currentUserIds = $this->getUsersInGroup($this->MARKETING_AGENT_GROUP_ID);
    
    // Определяем, нужно ли добавить или удалить пользователя из группы
    $isUserInGroup = in_array($userId, $currentUserIds);
    $shouldBeInGroup = ($status === 'Y' || $status === true || $status === 1 || $status === "1");
    
    if ($shouldBeInGroup && !$isUserInGroup) {
        // Добавляем пользователя в группу
        return $this->addUserToGroup($userId, $this->MARKETING_AGENT_GROUP_ID);
        
    } elseif (!$shouldBeInGroup && $isUserInGroup) {
        // Удаляем пользователя из группы
        return $this->removeUserFromGroup($userId, $this->MARKETING_AGENT_GROUP_ID);
        
    } else {
        return true;
    }
}
```

### 4. Класс UserGroups (local/classes/site/UserGroups.php)

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
   - Пользователь удаляется из группы 12
   - Устанавливаются группы: [3, 4, 7]

### Безопасность работы с группами:

- **Проверка существования**: Перед добавлением проверяется, не находится ли пользователь уже в группе
- **Сохранение групп**: При добавлении/удалении сохраняются все остальные группы пользователя
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

### Пример 3: Добавление пользователя в группу вручную
```php
$user = new \OnlineService\B24\User();
$userId = 100;
$groupId = 432;

// Добавляем пользователя в группу руководителей
$result = $user->addUserToGroup($userId, $groupId);

if ($result) {
    echo "Пользователь добавлен в группу";
}
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
- `local/classes/b24/User.php` - основной класс с методами управления группами
- `local/classes/site/UserGroups.php` - класс для работы с группами

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

