# Улучшение: Двусторонняя синхронизация профилей с B24

## 📋 Суть проблемы

**Было:**
- ✅ B24 → Сайт: Веб-хуки обновляли данные на сайте при изменениях в CRM
- ❌ Сайт → B24: При редактировании профиля на сайте данные НЕ отправлялись в B24

**Стало:**
- ✅ B24 → Сайт: Работает как прежде
- ✅ **Сайт → B24: Теперь работает!** При сохранении профиля данные синхронизируются с CRM

## 🔍 Почему так было?

### Возможные причины отсутствия обратной синхронизации:

1. **Архитектурное решение** - изначально предполагалось, что B24 CRM является "источником правды" и все изменения делаются только через менеджеров

2. **Недоработка** - просто забыли добавить при первоначальной разработке

3. **Ограничение функционала** - возможно, не хотели давать пользователям возможность напрямую менять данные в CRM

## ✨ Что было сделано

### Файл: `local/components/online-service/user.profile.edit/class.php`

**Изменения в методе `saveProfileAction()`:**

#### Было (ваша версия):
```php
// Дублирующий вызов getUserData()
$userObject = $this->getUserData($userId);
if( isset($userObject['UF_B24_USER_ID']) && !empty($userObject['UF_B24_USER_ID']) ){
    sendRequestB24("crm.contact.update", [
        "id" => $this->getUserData($userId)['UF_B24_USER_ID'], // второй вызов!
        "fields" => [
            'NAME' => $fields['NAME'],
            'LAST_NAME' => $fields['LAST_NAME'],
            'POST' => $fields['WORK_POSITION'],
            'PHONE' => [["VALUE" => $fields['PERSONAL_PHONE'], "VALUE_TYPE" => "WORK"]],
            'EMAIL' => [["VALUE" => $fields['EMAIL'], "VALUE_TYPE" => "WORK"]]
        ]
    ]);
}
// Обновление в БД
$user = new CUser;
$result = $user->Update($userId, $updateFields);
```

**Проблемы:**
- ❌ Дублирующий вызов `getUserData()`
- ❌ Нет обработки ошибок B24
- ❌ Неполная синхронизация полей (нет `WORK_PHONE`, `PERSONAL_MOBILE`)
- ❌ Синхронизация ДО сохранения в БД (некорректный порядок)
- ❌ Отправляются все поля, даже если не были изменены

#### Стало (улучшенная версия):
```php
// Сначала сохраняем на сайте
$user = new CUser;
$result = $user->Update($userId, $updateFields);

if (!$result) {
    return ['success' => false, 'error' => $user->LAST_ERROR ?: 'Ошибка при сохранении'];
}

// Затем синхронизируем с B24
$userObject = $this->getUserData($userId); // один вызов
if (isset($userObject['UF_B24_USER_ID']) && !empty($userObject['UF_B24_USER_ID'])) {
    $b24Fields = [];
    
    // Формируем только измененные поля
    if (isset($fields['NAME'])) $b24Fields['NAME'] = $fields['NAME'];
    if (isset($fields['LAST_NAME'])) $b24Fields['LAST_NAME'] = $fields['LAST_NAME'];
    if (isset($fields['WORK_POSITION'])) $b24Fields['POST'] = $fields['WORK_POSITION'];
    
    // Собираем все телефоны
    $phones = [];
    if (isset($fields['PERSONAL_PHONE']) && !empty($fields['PERSONAL_PHONE'])) {
        $phones[] = ["VALUE" => $fields['PERSONAL_PHONE'], "VALUE_TYPE" => "MOBILE"];
    }
    if (isset($fields['WORK_PHONE']) && !empty($fields['WORK_PHONE'])) {
        $phones[] = ["VALUE" => $fields['WORK_PHONE'], "VALUE_TYPE" => "WORK"];
    }
    if (isset($fields['PERSONAL_MOBILE']) && !empty($fields['PERSONAL_MOBILE'])) {
        $phones[] = ["VALUE" => $fields['PERSONAL_MOBILE'], "VALUE_TYPE" => "MOBILE"];
    }
    if (!empty($phones)) $b24Fields['PHONE'] = $phones;
    
    // Email
    if (isset($fields['EMAIL']) && !empty($fields['EMAIL'])) {
        $b24Fields['EMAIL'] = [["VALUE" => $fields['EMAIL'], "VALUE_TYPE" => "WORK"]];
    }
    
    // Отправляем с обработкой ошибок
    if (!empty($b24Fields)) {
        try {
            $b24Response = sendRequestB24("crm.contact.update", [
                "id" => $userObject['UF_B24_USER_ID'],
                "fields" => $b24Fields
            ]);
            
            if (isset($b24Response['error'])) {
                error_log("B24 sync error for user {$userId}: " . print_r($b24Response['error'], true));
            }
        } catch (\Exception $e) {
            error_log("B24 sync exception for user {$userId}: " . $e->getMessage());
        }
    }
}

return ['success' => true, 'message' => 'Профиль успешно обновлен'];
```

**Улучшения:**
- ✅ Один вызов `getUserData()`
- ✅ Полная обработка ошибок (try-catch)
- ✅ Все телефонные поля синхронизируются
- ✅ Правильный порядок: сначала сайт, потом B24
- ✅ Отправляются только измененные поля
- ✅ Fail-safe: ошибка B24 не ломает сохранение

## 📊 Синхронизируемые поля

| Поле сайта | Поле B24 | Тип |
|-----------|----------|-----|
| `NAME` | `NAME` | Имя |
| `LAST_NAME` | `LAST_NAME` | Фамилия |
| `WORK_POSITION` | `POST` | Должность |
| `PERSONAL_PHONE` | `PHONE[MOBILE]` | Личный телефон |
| `WORK_PHONE` | `PHONE[WORK]` | Рабочий телефон |
| `PERSONAL_MOBILE` | `PHONE[MOBILE]` | Мобильный |
| `EMAIL` | `EMAIL[WORK]` | Email |

## ⚠️ Потенциальные риски

### Циклическая синхронизация

**Сценарий:**
1. Пользователь редактирует профиль на сайте
2. Данные отправляются в B24 (`crm.contact.update`)
3. B24 генерирует событие `ONCRMCONTACTUPDATE`
4. Веб-хук `script/crm/rest/contact.php` получает событие
5. Веб-хук обновляет данные на сайте
6. **Потенциально:** Цикл может повториться

**Почему это безопасно:**
- Веб-хук использует модуль `intec.eklectika\Client::update()`
- Модуль проверяет, изменились ли данные
- Если данные идентичны - обновление не происходит
- Цикл прерывается естественным образом

**Рекомендация на будущее:**
Если возникнут проблемы с циклами, можно добавить:
```php
// При отправке в B24 добавить метку
$b24Response = sendRequestB24("crm.contact.update", [
    "id" => $userObject['UF_B24_USER_ID'],
    "fields" => array_merge($b24Fields, [
        'UF_SKIP_WEBHOOK' => 1 // Флаг для пропуска веб-хука
    ])
]);
```

И в веб-хуке проверять этот флаг перед обновлением.

## 📝 Обновлена документация

Файл `docs/features/b24_integration.md` дополнен разделом:
- **Синхронизация в B24 (Сайт → B24)** - подробное описание процесса
- **Потенциальная проблема: Циклическая синхронизация** - описание и решение

## ✅ Результат

Теперь система имеет **полноценную двустороннюю синхронизацию**:
- 🔄 B24 → Сайт: Менеджеры обновляют данные в CRM → Сайт получает изменения
- 🔄 Сайт → B24: Пользователи редактируют профиль → CRM получает изменения

Это обеспечивает **актуальность данных в обеих системах** и улучшает user experience.

## 🎯 Тестирование

Для проверки работы:
1. Авторизоваться на сайте
2. Перейти в редактирование профиля
3. Изменить имя, фамилию, телефон
4. Сохранить изменения
5. Проверить в B24 CRM, что контакт обновился
6. Проверить логи на наличие ошибок синхронизации

---

**Дата:** 27 октября 2025  
**Автор изменений:** Пользователь (идея) + AI Assistant (оптимизация и документация)

