# Документация: Отображение менеджеров пользователя

## Описание
Функционал отображения привязанных менеджеров в профиле пользователя. Менеджеры получаются из пользовательских полей `UF_MANAGER` и `UF_MANAGER2`, загружаются из инфоблока 553 и отображаются в правой колонке профиля.

## Основные характеристики
- **Источник данных**: Пользовательские поля `UF_MANAGER` и `UF_MANAGER2`
- **Тип полей**: Множественное или одиночное значение (ID элементов инфоблока 553)
- **Инфоблок менеджеров**: ID 553
- **Отображение**: Динамическое формирование карточек менеджеров

## Места использования и реализации

### 1. Компонент `online-service:user.profile`

#### Файл класса: `local/components/online-service/user.profile/class.php`

##### Метод `setPageTitle()`:
```php
/**
 * Установка заголовка страницы (H1)
 */
private function setPageTitle()
{
    global $APPLICATION;
    
    if (!empty($this->arResult['USER'])) {
        $user = $this->arResult['USER'];
        
        // Формируем полное имя пользователя
        $fullName = trim($user['NAME'] . ' ' . $user['LAST_NAME']);
        if (empty($fullName)) {
            $fullName = $user['LOGIN'];
        }
        
        // Устанавливаем заголовок через SetPageProperty (более надежный способ)
        $APPLICATION->SetPageProperty('title', $fullName);
        
        // Дублируем через SetTitle для совместимости
        $APPLICATION->SetTitle($fullName);
    }
}
```

##### Метод `getUserManagers($userId)`:
```php
/**
 * Получение менеджеров пользователя из полей UF_MANAGER и UF_MANAGER2
 */
private function getUserManagers($userId)
{
    $managers = [];
    
    // Получаем данные пользователя с пользовательскими полями
    $rsUser = CUser::GetList(
        $by = 'ID',
        $order = 'ASC',
        ['ID' => $userId],
        ['SELECT' => ['UF_MANAGER', 'UF_MANAGER2']]
    );
    
    if ($arUser = $rsUser->Fetch()) {
        // Собираем ID менеджеров из обоих полей
        $managerIds = [];
        
        // Обработка UF_MANAGER (может быть массивом или одиночным значением)
        if (!empty($arUser['UF_MANAGER'])) {
            if (is_array($arUser['UF_MANAGER'])) {
                $managerIds = array_merge($managerIds, $arUser['UF_MANAGER']);
            } else {
                $managerIds[] = $arUser['UF_MANAGER'];
            }
        }
        
        // Обработка UF_MANAGER2 (может быть массивом или одиночным значением)
        if (!empty($arUser['UF_MANAGER2'])) {
            if (is_array($arUser['UF_MANAGER2'])) {
                $managerIds = array_merge($managerIds, $arUser['UF_MANAGER2']);
            } else {
                $managerIds[] = $arUser['UF_MANAGER2'];
            }
        }
        
        // Убираем дубликаты
        $managerIds = array_unique($managerIds);
        
            // Получаем полную информацию о каждом менеджере из инфоблока 553
            foreach ($managerIds as $managerId) {
                if ($managerId && intval($managerId) > 0) {
                    $rsManager = CIBlockElement::GetByID($managerId);
                    if ($arManagerElement = $rsManager->GetNextElement()) {
                        $arManagerFields = $arManagerElement->GetFields();
                        $arManagerProps = $arManagerElement->GetProperties();
                        
                        // Формируем массив с данными менеджера
                        $managers[] = [
                            'ID' => $arManagerFields['ID'],
                            'NAME' => $arManagerFields['NAME'],
                            'PREVIEW_PICTURE' => $arManagerFields['PREVIEW_PICTURE'],
                            'DETAIL_PICTURE' => $arManagerFields['DETAIL_PICTURE'],
                            'PROPERTIES' => $arManagerProps,
                            'FIELDS' => $arManagerFields
                        ];
                    }
                }
            }
        }
        
        return $managers;
    }
```

#### Файл шаблона: `local/components/online-service/user.profile/templates/.default/template.php`

##### Отображение менеджеров:
```php
<?php if (!empty($managers)): ?>
    <?php foreach ($managers as $index => $manager): ?>
        <?php
        // Получаем имя менеджера из элемента инфоблока
        $managerFullName = $manager['NAME'] ?? 'Менеджер';
        
        // Получаем фото менеджера из PREVIEW_PICTURE
        $managerPhoto = '/bitrix/templates/universe_s1/images/default-avatar.png';
        if (!empty($manager['PREVIEW_PICTURE'])) {
            $arPhoto = CFile::GetFileArray($manager['PREVIEW_PICTURE']);
            if ($arPhoto) {
                $managerPhoto = $arPhoto['SRC'];
            }
        }
        
        // Получаем должность из свойств (несколько вариантов названий)
        $managerPost = $manager['PROPERTIES']['POST']['VALUE'] ?? 
                       $manager['PROPERTIES']['POSITION']['VALUE'] ?? 
                       $manager['PROPERTIES']['DOLZHNOST']['VALUE'] ?? 
                       'Менеджер';
        
        // Получаем контакты из свойств
        $managerPhone = $manager['PROPERTIES']['PHONE']['VALUE'] ?? 
                        $manager['PROPERTIES']['TELEFON']['VALUE'] ?? '';
        $managerEmail = $manager['PROPERTIES']['EMAIL']['VALUE'] ?? '';
        ?>
        
        <div class="manager-card-fields"<?= $index > 0 ? ' style="margin-top: 15px;"' : '' ?>>
            <!-- Информация о менеджере -->
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="manager-card-fields">
        <p>Менеджеры не назначены</p>
    </div>
<?php endif; ?>
```

## Логика работы

### Этап 0: Установка заголовка страницы
1. Получаем данные пользователя
2. Формируем полное имя (NAME + LAST_NAME или LOGIN)
3. Устанавливаем заголовок через:
   - `$APPLICATION->SetPageProperty('title', $fullName)` - основной способ
   - `$APPLICATION->SetTitle($fullName)` - для совместимости

### Этап 1: Получение данных менеджеров
1. Получаем ID пользователя
2. Делаем запрос к CUser::GetByID для получения полей `UF_MANAGER` и `UF_MANAGER2`
3. Собираем ID менеджеров из обоих полей в единый массив
4. Удаляем дубликаты ID

### Этап 2: Загрузка информации о менеджерах
1. Для каждого ID менеджера получаем элемент из инфоблока 553 через CIBlockElement::GetByID
2. Извлекаем поля (GetFields) и свойства (GetProperties)
3. Собираем массив с данными всех менеджеров

### Этап 3: Отображение в шаблоне
1. Проверяем наличие менеджеров
2. Для каждого менеджера формируем:
   - Имя (NAME из полей элемента)
   - Фото (PREVIEW_PICTURE или изображение по умолчанию)
   - Должность (из свойств: POST, POSITION или DOLZHNOST)
   - Телефон (из свойств: PHONE или TELEFON)
   - Email (из свойства EMAIL)
3. Выводим карточки менеджеров с отступами (кроме первой)

## Связанные поля и сущности

### Пользовательские поля пользователя
- `UF_MANAGER` - Первый менеджер пользователя (ID элемента инфоблока 553 или массив ID)
- `UF_MANAGER2` - Второй менеджер пользователя (ID элемента инфоблока 553 или массив ID)

### Инфоблок менеджеров (ID 553)

#### Поля элемента:
- `ID` - ID элемента
- `NAME` - Имя менеджера
- `PREVIEW_PICTURE` - ID фото для аватара
- `DETAIL_PICTURE` - ID детального изображения

#### Свойства элемента (возможные варианты названий):
- `POST` / `POSITION` / `DOLZHNOST` - Должность менеджера
- `PHONE` / `TELEFON` - Телефон менеджера
- `EMAIL` - Email менеджера

## Примечания

### Обработка полей
- Поля `UF_MANAGER` и `UF_MANAGER2` могут быть как одиночными значениями, так и массивами
- Автоматическая обработка обоих вариантов с приведением к единому формату массива
- Удаление дубликатов менеджеров при совпадении ID

### Отображение
- Первая карточка менеджера выводится без отступа сверху
- Последующие карточки имеют отступ `margin-top: 15px`
- Если менеджеры не назначены, выводится соответствующее сообщение
- Телефон и email отображаются только если они заполнены

### Изображения
- По умолчанию используется изображение `/bitrix/templates/universe_s1/images/default-avatar.png`
- Фото менеджера берётся из поля `PREVIEW_PICTURE` элемента инфоблока
- Изображение преобразуется в путь через `CFile::GetFileArray()`

### Источник данных
- Менеджеры хранятся как элементы инфоблока 553 (не как пользователи системы)
- Связь с пользователем через пользовательские поля `UF_MANAGER` и `UF_MANAGER2`
- Данные загружаются через `CIBlockElement::GetByID()`

### Установка заголовка страницы
- Компонент автоматически устанавливает заголовок H1 на основе имени пользователя
- Используется `SetPageProperty('title')` - более надежный метод, работает даже если свойство title уже установлено выше по структуре
- Дублируется через `SetTitle()` для совместимости со старыми шаблонами
- Метод вызывается после получения данных пользователя и проверки прав доступа

---

*Дата создания: 14.10.2025*
*Последнее обновление: 14.10.2025*
*Версия: 1.2 - Добавлена автоматическая установка заголовка страницы*

