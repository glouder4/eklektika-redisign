# Документация: Поле UF_ADVERSTERING_AGENT (Рекламный агент)

## Описание
Поле `UF_ADVERSTERING_AGENT` - это пользовательское поле в Битрикс, которое определяет статус пользователя как "Рекламный агент". Активация этого поля означает, что личный кабинет пользователя становится активным с расширенными правами доступа.

## Основные характеристики
- **Тип поля**: Boolean (0/1)
- **Значение 1**: Пользователь является рекламным агентом
- **Значение 0**: Пользователь не является рекламным агентом
- **Группа пользователей**: ID = 12 (MARKETING_AGENT_GROUP_ID)

## Места использования и реализации

### 1. Основная логика в классе User
**Файл**: `local/modules/eklektika.b24.usersync/lib/User.php`

#### Обработчик обновления пользователя:
```php
public function OnAfterUserUpdateHandler($arFields){
    if( isset($arFields['UF_ADVERSTERING_AGENT']) )
        $this->updateMarketingAgentPriceType($arFields['UF_ADVERSTERING_AGENT']);
    return true;
}
```

#### Обновление типа цены для маркетинговых агентов:
```php
private function updateMarketingAgentPriceType($status, $userId = null){
    // Получаем группу рекламных агентов (ID = 12)
    $rsGroup = \CGroup::GetByID($this->MARKETING_AGENT_GROUP_ID);
    
    // Добавляем или удаляем пользователя из группы в зависимости от статуса
    if ($shouldBeInGroup && !$isUserInGroup) {
        return $this->addUserToGroup($userId, $this->MARKETING_AGENT_GROUP_ID);
    } elseif (!$shouldBeInGroup && $isUserInGroup) {
        return $this->removeUserFromGroup($userId, $this->MARKETING_AGENT_GROUP_ID);
    }
}
```

#### Константа группы:
```php
public int $MARKETING_AGENT_GROUP_ID = 12;
```

### 2. Регистрация пользователей
**Файл**: `local/modules/eklektika.b24.usersync/lib/RegisterUserCompany.php`

#### Обработка рекламных агентов при регистрации:
```php
// В методе createB24Company
if ($arFields['UF_ADVERSTERING_AGENT'] == 'on') {
    $dataContact['fields']['UF_CRM_1701839165901'] = "Пользователь зарегистрировался как рекламный агент";
    $dataContact['fields']['UF_CRM_1698752707853'] = 1; // Флаг рекламного агента в B24
} else {
    $dataContact['fields']['UF_CRM_1698752707853'] = 0;
}
```

### 3. Шаблоны регистрации
**Файлы**: 
- `local/templates/universe_s1/components/intec/main.register/director-add-new-branch/template.php`
- `local/templates/universe_s1/components/intec/main.register/template.2/template.php`

#### JavaScript логика показа/скрытия поля:
```javascript
case "6": // Тип пользователя "Рекламный агент"
    // Показываем поля компании
    $("[data-name=UF_JUR_ADDRESS]").show();
    $("[data-name=UF_NAME_COMPANY]").show();
    $("[data-name=UF_INN]").show();
    // ... другие поля
    
    // Автоматически устанавливаем чекбокс "Рекламный агент"
    $("[name=UF_ADVERSTERING_AGENT]").attr("checked" , true);
break;

case "4": // Физическое лицо
case "5": // Юридическое лицо
    // Скрываем поле и снимаем галочку
    $("[data-name=UF_ADVERSTERING_AGENT]").hide();        
    $("[name=UF_ADVERSTERING_AGENT]").attr("checked" , false);
break;
```

### 4. Связь с компаниями
**Файл**: `local/modules/eklektika.company/lib/Company.php`

#### Поле в компании:
```php
private static $codeProps = [
    // ...
    'OS_IS_MARKETING_AGENT',
    // ...
];
```

#### Обработка при обновлении компании:
```php
if( $params['OS_IS_MARKETING_AGENT']['VALUE'] ){
    $groups[] = $user->getMarketingGroupId();
}
```

### 5. Заказы и корзина
**Файл**: `personal/basket/create_reserve.php`

#### Связь с заказами:
```php
$arProps["ADVERSTERING_AGENT"] = [
    // Свойство заказа, связанное с рекламным агентом
];
```

## Типы пользователей в системе

1. **Тип 4** - Физическое лицо (fiz)
2. **Тип 5** - Юридическое лицо (jur) 
3. **Тип 6** - Рекламный агент (agent)

## Группы пользователей

При активации статуса рекламного агента пользователь добавляется в группы:
- Группа 3: Базовая группа
- Группа 4: Расширенные права
- Группа 7: Дополнительные права
- **Группа 12**: Группа рекламных агентов (MARKETING_AGENT_GROUP_ID)

## Логика работы

### Активация рекламного агента:
1. Устанавливается `UF_ADVERSTERING_AGENT = 1`
2. Пользователь добавляется в группу 12
3. Обновляется тип цены (если настроено)
4. Активируется личный кабинет с расширенными правами

### Деактивация рекламного агента:
1. Устанавливается `UF_ADVERSTERING_AGENT = 0`
2. Пользователь удаляется из группы 12
3. Тип пользователя меняется на 4 (физическое лицо) или 5 (юридическое лицо)

## Влияние на функциональность

### Личный кабинет:
- При активации поля открывается доступ к расширенному функционалу ЛК
- Доступ к специальным ценам и условиям
- Возможность работы с заказами от имени компании

### Заказы:
- Специальные типы плательщиков для агентов
- Дополнительные поля в заказах
- Интеграция с CRM Bitrix24

### Цены:
- Доступ к специальным ценам для рекламных агентов
- Автоматическое применение скидок

## Связанные поля и сущности

- `UF_TYPE` - основной тип пользователя (4, 5, 6)
- `OS_IS_MARKETING_AGENT` - поле компании, указывающее на маркетингового агента
- `ADVERSTERING_AGENT` - свойство заказа
- Группа пользователей ID = 12

## Примечания

- Поле активно используется в интеграции с Bitrix24 CRM
- При регистрации как тип "6" поле автоматически устанавливается в true
- Есть проверки на заполненность обязательных полей при регистрации агентов
- Система поддерживает как ручное управление статусом, так и автоматическое через регистрацию
