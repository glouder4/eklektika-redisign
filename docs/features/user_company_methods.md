# Документация: Методы работы с компаниями пользователей

## Описание
Новые методы в классе `User` для работы с компаниями пользователей, которые упрощают получение информации о компании и проверку прав доступа.

## Новые методы

### 1. getHeadCompany()
**Назначение**: Получить головную компанию холдинга, где пользователь является руководителем

**Параметры**:
- `$userId` (int|null) - ID пользователя (если не указан, используется текущий)

**Возвращает**: array|false - Данные головной компании или false если не найдена

**Пример использования**:
```php
$user = new \OnlineService\B24\User();
$user->userId = $USER->GetID();

// Получить головную компанию холдинга, где пользователь является руководителем
$headCompany = $user->getHeadCompany($userId);

if ($headCompany) {
    echo "Головная компания: " . $headCompany['NAME'];
}
```

### 2. getUserCompany()
**Назначение**: Получить любую компанию пользователя (руководитель или сотрудник)

**Параметры**:
- `$userId` (int|null) - ID пользователя (если не указан, используется текущий)
- `$userRole` (string) - Роль пользователя: 'boss' - руководитель, 'user' - обычный сотрудник
- `$companyId` (int|null) - ID конкретной компании для проверки принадлежности (опционально)

**Возвращает**: array|false - Данные компании или false если не найдена

**Пример использования**:
```php
$user = new \OnlineService\B24\User();
$user->userId = $USER->GetID();

// Получить компанию, где пользователь является руководителем
$company = $user->getUserCompany($userId, 'boss');

// Проверить, что пользователь руководит конкретной компанией
$company = $user->getUserCompany($userId, 'boss', $companyId);

// Получить компанию, где пользователь является обычным сотрудником
$company = $user->getUserCompany($userId, 'user');
```

### 3. isCompanyBoss()
**Назначение**: Проверить, является ли пользователь руководителем головной компании холдинга

**Параметры**:
- `$userId` (int|null) - ID пользователя (если не указан, используется текущий)

**Возвращает**: bool - true если пользователь руководитель головной компании холдинга

**Пример использования**:
```php
$user = new \OnlineService\B24\User();
$user->userId = $USER->GetID();

if ($user->isCompanyBoss($userId)) {
    // Пользователь является руководителем головной компании холдинга
    echo "Доступ разрешен";
} else {
    // Пользователь не является руководителем головной компании
    echo "Доступ запрещен";
}
```

### 4. getHeadCompanyId()
**Назначение**: Получить ID головной компании холдинга для пользователя

**Параметры**:
- `$userId` (int|null) - ID пользователя (если не указан, используется текущий)

**Возвращает**: int|false - ID головной компании холдинга или false если не найдена

**Пример использования**:
```php
$user = new \OnlineService\B24\User();
$user->userId = $USER->GetID();

$headCompanyId = $user->getHeadCompanyId($userId);

if ($headCompanyId) {
    echo "ID головной компании холдинга: " . $headCompanyId;
} else {
    echo "Пользователь не является руководителем головной компании холдинга";
}
```

## Практические примеры

### Проверка прав руководителя головной компании холдинга
```php
// Вместо прямого запроса к базе данных
$rsCompany = CIBlockElement::GetList(
    [],
    [
        'IBLOCK_ID' => 57,
        'PROPERTY_OS_COMPANY_BOSS' => $USER->GetID(),
        'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING' => 31520,
        'ACTIVE' => 'Y'
    ],
    false,
    false,
    ['ID', 'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING']
);

// Используем новый метод
$user = new \OnlineService\B24\User();
$user->userId = $USER->GetID();

if ($user->isCompanyBoss()) {
    $headCompany = $user->getHeadCompany();
    $headCompanyId = $user->getHeadCompanyId();
    // Работаем с головной компанией холдинга
}
```

### Получение ID головной компании холдинга
```php
// Вместо сложной логики определения холдинга
if (!empty($company['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'])) {
    $headCompanyId = $company['PROPERTY_OS_HEAD_COMPANY_B24_ID_VALUE'];
} else if (!empty($company['PROPERTY_OS_HOLDING_OF_VALUE'])) {
    $headCompanyId = $company['PROPERTY_OS_HOLDING_OF_VALUE'];
} else {
    $headCompanyId = $company['PROPERTY_OS_COMPANY_B24_ID_VALUE'];
}

// Используем новый метод
$user = new \OnlineService\B24\User();
$user->userId = $USER->GetID();

$headCompanyId = $user->getHeadCompanyId();
```

## Преимущества новых методов

1. **Упрощение кода**: Убираем дублирование логики получения компаний
2. **Централизация**: Вся логика работы с компаниями пользователей в одном месте
3. **Читаемость**: Более понятные названия методов
4. **Переиспользование**: Методы можно использовать в разных частях проекта
5. **Сопровождение**: Легче вносить изменения в логику работы с компаниями

## Места использования

### Уже используется в:
- `director/add_new_branch-action.php` - проверка прав руководителя головной компании холдинга при создании филиала
- `local/templates/universe_s1/components/intec/main.register/director-add-new-branch/result_modifier.php` - получение данных головной компании для формы добавления филиала

### Рекомендуется использовать в:
- Проверке прав доступа в личном кабинете руководителей холдингов
- Создании новых филиалов и дочерних компаний
- Управлении холдингами
- Любых операциях, требующих проверки принадлежности к головной компании холдинга

## Связанные файлы

- `local/modules/eklektika.b24.usersync/lib/User.php` - основной класс с методами
- `director/add_new_branch-action.php` - пример использования
- `docs/features/company_system.md` - документация системы компаний
- `docs/features/b24_integration.md` - документация интеграции с B24
