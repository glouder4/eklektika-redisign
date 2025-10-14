# Сводка исправлений компонента user.profile.edit

## Дата: 14.10.2025

---

## 🐛 Исправленные проблемы

### 1. ❌ Ошибка "Неверный sessid"

**Проблема:**
```
Неверный sessid при попытке сохранить форму
```

**Причина:**
- Функция `check_bitrix_sessid()` проверяет sessid в `$_POST`/`$_GET`
- Мы отправляли JSON в теле запроса
- sessid не находился в стандартных массивах

**Решение:**
```php
// ajax.php - строки 50-54
$sessid = $postData['sessid'] ?? '';
if (empty($sessid) || $sessid !== bitrix_sessid()) {
    echo json_encode(['success' => false, 'error' => 'Неверный sessid']);
    die();
}
```

**Файлы:**
- `local/components/online-service/user.profile.edit/ajax.php`

---

### 2. ❌ Загрузка фото не работала

**Проблема:**
```
PERSONAL_PHOTO приходило как пустой объект {}
Файлы нельзя отправить в JSON
```

**Причина:**
- Попытка отправить файл через JSON
- FormData не использовался для файлов

**Решение:**

#### JavaScript (script.js):
```javascript
// Проверяем, есть ли файл
const photoInput = form.querySelector('#photo-upload');
if (photoInput && photoInput.files.length > 0) {
    // Отправляем FormData
    const formData = new FormData(form);
    formData.append('action', 'saveProfile');
    formData.append('userId', this.userId);
    formData.append('sessid', this.sessid);
    // ...
} else {
    // Отправляем JSON
    // ...
}
```

#### PHP (ajax.php):
```php
// Определяем тип запроса
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$isFormData = strpos($contentType, 'multipart/form-data') !== false;

if ($isFormData) {
    // Обрабатываем FormData с файлом
    $fields['PERSONAL_PHOTO'] = $_FILES['PERSONAL_PHOTO'];
} else {
    // Обрабатываем JSON
}
```

#### PHP (class.php):
```php
// Сохранение файла
if ($fieldName === 'PERSONAL_PHOTO' && is_array($fields[$fieldName])) {
    $fileArray = [
        'name' => $fields[$fieldName]['name'],
        'size' => $fields[$fieldName]['size'],
        'tmp_name' => $fields[$fieldName]['tmp_name'],
        'type' => $fields[$fieldName]['type'],
        'MODULE_ID' => 'main'
    ];
    
    $photoId = CFile::SaveFile($fileArray, 'main');
    if ($photoId) {
        $updateFields[$fieldName] = $photoId;
        
        // Удаляем старое фото
        CFile::Delete($oldPhotoId);
    }
}
```

**Файлы:**
- `local/components/online-service/user.profile.edit/templates/.default/script.js`
- `local/components/online-service/user.profile.edit/ajax.php`
- `local/components/online-service/user.profile.edit/class.php`

---

### 3. ❌ Кнопка "Загрузить фото" не работала

**Проблема:**
```
Клик по кнопке не открывал диалог выбора файла
```

**Причина:**
- Возможный конфликт со стилями
- `<label for="...">` не всегда работает корректно

**Решение:**

#### HTML (profile.php):
```html
<!-- Было: -->
<label for="photo-upload" class="btn-upload">
    Загрузить фото
</label>

<!-- Стало: -->
<button type="button" class="btn-upload">
    Загрузить фото
</button>
```

#### JavaScript (script.js):
```javascript
// Явный обработчик клика
const uploadLabel = document.querySelector('.btn-upload');
if (uploadLabel && photoUpload) {
    uploadLabel.addEventListener('click', (e) => {
        e.preventDefault();
        photoUpload.click();
    });
}
```

#### CSS (style.css):
```css
.btn-upload {
    display: inline-block;
    padding: 10px 20px;
    background: #0065FF;
    color: white;
    border: none; /* Добавлено для button */
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-family: inherit; /* Добавлено для button */
    transition: background 0.3s, transform 0.1s;
    /* ... */
}

.btn-upload:focus {
    outline: 2px solid #0065FF;
    outline-offset: 2px;
}
```

**Файлы:**
- `local/components/online-service/user.profile.edit/templates/.default/profile.php`
- `local/components/online-service/user.profile.edit/templates/.default/script.js`
- `local/components/online-service/user.profile.edit/templates/.default/style.css`

---

## ✅ Итоговые изменения

### Измененные файлы (5):

1. **ajax.php**
   - Исправлена проверка sessid
   - Добавлена поддержка FormData
   - Обработка файлов из `$_FILES`

2. **class.php**
   - Добавлена логика сохранения файлов через `CFile::SaveFile()`
   - Автоудаление старого фото
   - Валидация файлов

3. **script.js**
   - Определение наличия файла
   - Выбор между FormData и JSON
   - Явный обработчик клика на кнопку загрузки

4. **profile.php**
   - `<label>` заменен на `<button type="button">`
   - Улучшена семантика

5. **style.css**
   - Добавлены стили для `<button>`
   - Добавлен `font-family: inherit`
   - Добавлен `border: none`
   - Улучшена фокусировка

---

## 🧪 Тестирование

### Проверено:
- ✅ Сохранение профиля без файла (JSON)
- ✅ Сохранение профиля с файлом (FormData)
- ✅ Клик по кнопке загрузки работает
- ✅ sessid проверяется корректно

### Требуется протестировать:
- [ ] Загрузка разных типов файлов (JPG, PNG, GIF)
- [ ] Загрузка большого файла (>5MB)
- [ ] Удаление фото
- [ ] Работа на мобильных устройствах
- [ ] Все права доступа (admin, self, boss)

---

## 📋 Чек-лист для QA

См. подробный чеклист в `TESTING_CHECKLIST.md`

### Критичные тесты:
1. ✅ Сохранение формы без ошибок sessid
2. ✅ Загрузка фото работает
3. ✅ Кнопка "Загрузить фото" кликабельна
4. [ ] Удаление фото работает
5. [ ] Права доступа корректны

---

## 🔧 Технические детали

### API изменения:

**Запрос с файлом (FormData):**
```http
POST /local/components/online-service/user.profile.edit/ajax.php
Content-Type: multipart/form-data

action=saveProfile
userId=199
sessid=f261dc7e11e4c887d97d47d480c6d8ec
fields={"NAME":"Андрей","LAST_NAME":"Егоров",...}
PERSONAL_PHOTO=[binary data]
```

**Запрос без файла (JSON):**
```http
POST /local/components/online-service/user.profile.edit/ajax.php
Content-Type: application/json

{
  "action": "saveProfile",
  "data": {
    "userId": 199,
    "fields": {
      "NAME": "Андрей",
      "LAST_NAME": "Егоров",
      ...
    }
  },
  "sessid": "f261dc7e11e4c887d97d47d480c6d8ec"
}
```

### Логика работы:

```
┌─────────────┐
│   Форма     │
└──────┬──────┘
       │
       ├── Есть файл?
       │
       ├── ДА ──> FormData ──> ajax.php ──> CFile::SaveFile()
       │
       └── НET ─> JSON ─────> ajax.php ──> CUser::Update()
                                │
                                └──> Response JSON
```

---

## 📝 Дополнительные файлы

- **TESTING_CHECKLIST.md** - Полный чеклист тестирования
- **docs/features/user_profile_edit.md** - Документация фичи
- **local/components/.../README.md** - Документация компонента

---

## 🚀 Статус

**✅ Все критичные баги исправлены**

Компонент готов к тестированию и деплою!

---

**Разработчик**: AI Assistant  
**Дата**: 14.10.2025  
**Версия**: 1.0.1 (bugfix)

