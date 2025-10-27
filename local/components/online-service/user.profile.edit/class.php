<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Context;

class OnlineServiceUserProfileEditComponent extends CBitrixComponent implements Controllerable
{
    /**
     * Инициализация компонента
     */
    public function onPrepareComponentParams($arParams)
    {
        // Приводим параметры к нужным типам
        $arParams['USER_ID'] = intval($arParams['USER_ID']);
        $arParams['TYPE'] = strval($arParams['TYPE'] ?? 'profile');
        
        // Валидация типа редактирования
        if (!in_array($arParams['TYPE'], ['profile', 'companies'])) {
            $arParams['TYPE'] = 'profile';
        }
        
        return $arParams;
    }

    /**
     * Выполнение компонента
     */
    public function executeComponent()
    {
        // Проверяем наличие необходимых модулей
        if (!Loader::includeModule('main')) {
            ShowError('Модуль main не подключен');
            return;
        }

        // Получаем ID пользователя из параметра
        $userId = $this->arParams['USER_ID'];
        
        if (empty($userId)) {
            ShowError('Не указан ID пользователя');
            return;
        }

        // Получаем данные пользователя
        $userData = $this->getUserData($userId);
        
        if (!$userData) {
            ShowError('Пользователь не найден');
            return;
        }

        // Проверяем права доступа на редактирование
        if (!$this->checkEditAccess($userId)) {
            ShowError('Доступ к редактированию запрещен');
            return;
        }

        // Формируем результат
        $this->arResult = [
            'USER' => $userData,
            'TYPE' => $this->arParams['TYPE'],
            'COMPANIES' => [],
            'ALL_COMPANIES' => [],
            'EDIT_ACCESS_LEVEL' => $this->getEditAccessLevel($userId)
        ];

        // Если тип - компании, получаем дополнительные данные
        if ($this->arParams['TYPE'] === 'companies') {
            $this->arResult['COMPANIES'] = $this->getUserCompanies($userId);
            $this->arResult['ALL_COMPANIES'] = $this->getAllAvailableCompanies($userId);
        }

        // Устанавливаем заголовок страницы
        $this->setPageTitle();

        // Подключаем шаблон
        $this->includeComponentTemplate();
    }

    /**
     * Получение данных пользователя
     */
    private function getUserData($userId)
    {
        $rsUser = CUser::GetByID($userId);
        if ($arUser = $rsUser->Fetch()) {
            return $arUser;
        }
        
        return false;
    }

    /**
     * Получение компаний пользователя
     */
    private function getUserCompanies($userId)
    {
        if (!Loader::includeModule('iblock')) {
            return [];
        }

        $companies = [];
        
        // Ищем компании, где пользователь является руководителем
        $rsBossCompanies = CIBlockElement::GetList(
            ['NAME' => 'ASC'],
            [
                'IBLOCK_ID' => 57,
                'PROPERTY_OS_COMPANY_BOSS' => $userId,
                'ACTIVE' => 'Y'
            ],
            false,
            false,
            ['ID', 'NAME', 'CODE', 'DETAIL_PAGE_URL']
        );
        
        while ($arCompanyElement = $rsBossCompanies->GetNextElement()) {
            $arCompanyFields = $arCompanyElement->GetFields();
            $arCompanyProps = $arCompanyElement->GetProperties();
            
            $companies[] = [
                'ID' => $arCompanyFields['ID'],
                'NAME' => $arCompanyFields['NAME'],
                'DETAIL_PAGE_URL' => $arCompanyFields['DETAIL_PAGE_URL'],
                'ROLE' => 'boss',
                'ROLE_NAME' => 'Руководитель',
                'PROPERTIES' => $arCompanyProps
            ];
        }
        
        // Ищем компании, где пользователь является сотрудником
        $rsEmployeeCompanies = CIBlockElement::GetList(
            ['NAME' => 'ASC'],
            [
                'IBLOCK_ID' => 57,
                'PROPERTY_OS_COMPANY_USERS' => $userId,
                'ACTIVE' => 'Y'
            ],
            false,
            false,
            ['ID', 'NAME', 'CODE', 'DETAIL_PAGE_URL']
        );
        
        while ($arCompanyElement = $rsEmployeeCompanies->GetNextElement()) {
            $arCompanyFields = $arCompanyElement->GetFields();
            $arCompanyProps = $arCompanyElement->GetProperties();
            
            // Проверяем, не является ли пользователь уже руководителем этой компании
            $isAlreadyBoss = false;
            foreach ($companies as $company) {
                if ($company['ID'] == $arCompanyFields['ID']) {
                    $isAlreadyBoss = true;
                    break;
                }
            }
            
            if (!$isAlreadyBoss) {
                $companies[] = [
                    'ID' => $arCompanyFields['ID'],
                    'NAME' => $arCompanyFields['NAME'],
                    'DETAIL_PAGE_URL' => $arCompanyFields['DETAIL_PAGE_URL'],
                    'ROLE' => 'employee',
                    'ROLE_NAME' => 'Сотрудник',
                    'PROPERTIES' => $arCompanyProps
                ];
            }
        }
        
        return $companies;
    }

    /**
     * Получение всех доступных компаний для привязки
     */
    private function getAllAvailableCompanies($userId)
    {
        if (!Loader::includeModule('iblock')) {
            return [];
        }

        global $USER;
        $currentUserId = $USER->GetID();
        $companies = [];

        // Если текущий пользователь - админ, показываем все компании
        if ($USER->IsAdmin()) {
            $rsCompanies = CIBlockElement::GetList(
                ['NAME' => 'ASC'],
                [
                    'IBLOCK_ID' => 57,
                    'ACTIVE' => 'Y'
                ],
                false,
                false,
                ['ID', 'NAME']
            );
            
            while ($arCompany = $rsCompanies->GetNext()) {
                $companies[] = $arCompany;
            }
        } else {
            // Показываем только компании, где текущий пользователь - руководитель
            $rsCompanies = CIBlockElement::GetList(
                ['NAME' => 'ASC'],
                [
                    'IBLOCK_ID' => 57,
                    'PROPERTY_OS_COMPANY_BOSS' => $currentUserId,
                    'ACTIVE' => 'Y'
                ],
                false,
                false,
                ['ID', 'NAME']
            );
            
            while ($arCompany = $rsCompanies->GetNext()) {
                $companies[] = $arCompany;
            }
        }
        
        return $companies;
    }

    /**
     * Проверка прав доступа на редактирование
     */
    private function checkEditAccess($userId)
    {
        global $USER;
        
        if (!$USER->IsAuthorized()) {
            return false;
        }
        
        // Админы могут редактировать всех
        if ($USER->IsAdmin()) {
            return true;
        }
        
        $currentUserId = $USER->GetID();
        
        // Пользователь может редактировать свой профиль
        if ($currentUserId == $userId) {
            return true;
        }
        
        // Проверяем, является ли текущий пользователь руководителем компании, где userId является сотрудником
        if ($currentUserId && $userId != $currentUserId) {
            $targetUserCompanies = $this->getUserCompanies($userId);
            
            foreach ($targetUserCompanies as $company) {
                // Получаем руководителей компании
                $rsCompany = CIBlockElement::GetById($company['ID']);
                if ($companyElement = $rsCompany->GetNextElement()) {
                    $companyProps = $companyElement->GetProperties();
                    
                    $bossIds = $companyProps['OS_COMPANY_BOSS']['VALUE'] ?? [];
                    
                    if (!is_array($bossIds)) {
                        $bossIds = $bossIds ? [$bossIds] : [];
                    }
                    
                    // Проверяем, является ли текущий пользователь руководителем этой компании
                    if (in_array($currentUserId, $bossIds)) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }

    /**
     * Определение уровня доступа для редактирования
     */
    private function getEditAccessLevel($userId)
    {
        global $USER;
        
        if ($USER->IsAdmin()) {
            return 'admin';
        }
        
        if ($USER->GetID() == $userId) {
            return 'self';
        }
        
        // Проверяем, является ли руководителем
        $currentUserId = $USER->GetID();
        $targetUserCompanies = $this->getUserCompanies($userId);
        
        foreach ($targetUserCompanies as $company) {
            $rsCompany = CIBlockElement::GetById($company['ID']);
            if ($companyElement = $rsCompany->GetNextElement()) {
                $companyProps = $companyElement->GetProperties();
                
                $bossIds = $companyProps['OS_COMPANY_BOSS']['VALUE'] ?? [];
                
                if (!is_array($bossIds)) {
                    $bossIds = $bossIds ? [$bossIds] : [];
                }
                
                if (in_array($currentUserId, $bossIds)) {
                    return 'boss';
                }
            }
        }
        
        return 'none';
    }

    /**
     * Установка заголовка страницы
     */
    private function setPageTitle()
    {
        global $APPLICATION;
        
        if (!empty($this->arResult['USER'])) {
            $user = $this->arResult['USER'];
            
            $fullName = trim($user['NAME'] . ' ' . $user['LAST_NAME']);
            if (empty($fullName)) {
                $fullName = $user['LOGIN'];
            }
            
            $title = 'Редактирование: ' . $fullName;
            
            if ($this->arParams['TYPE'] === 'companies') {
                $title .= ' - Компании';
            } else {
                $title .= ' - Профиль';
            }
            
            $APPLICATION->SetPageProperty('title', $title);
            $APPLICATION->SetTitle($title);
        }
    }

    /**
     * Конфигурация AJAX действий
     */
    public function configureActions()
    {
        return [
            'saveProfile' => [
                'prefilters' => [
                    new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod(['POST']),
                ],
            ],
            'attachToCompany' => [
                'prefilters' => [
                    new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod(['POST']),
                ],
            ],
            'detachFromCompany' => [
                'prefilters' => [
                    new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod(['POST']),
                ],
            ],
            'changeRole' => [
                'prefilters' => [
                    new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod(['POST']),
                ],
            ],
        ];
    }

    /**
     * Сохранение профиля пользователя
     */
    public function saveProfileAction($userId, $fields)
    {
        global $USER;
        
        if (!$USER->IsAuthorized()) {
            return ['success' => false, 'error' => 'Не авторизован'];
        }
        
        $userId = intval($userId);
        
        // Проверяем права на редактирование
        if (!$this->checkEditAccess($userId)) {
            return ['success' => false, 'error' => 'Нет прав на редактирование'];
        }
        
        $accessLevel = $this->getEditAccessLevel($userId);
        
        // Фильтруем поля в зависимости от уровня доступа
        $allowedFields = [];
        
        if ($accessLevel === 'admin') {
            // Админ может редактировать все
            $allowedFields = ['NAME', 'LAST_NAME', 'WORK_POSITION', 'PERSONAL_MOBILE', 'WORK_PHONE', 'PERSONAL_PHONE', 'EMAIL', 'PERSONAL_PHOTO'];
        } elseif ($accessLevel === 'self') {
            // Сам пользователь может редактировать все кроме некоторых служебных полей
            $allowedFields = ['NAME', 'LAST_NAME', 'WORK_POSITION', 'PERSONAL_MOBILE', 'WORK_PHONE', 'PERSONAL_PHONE', 'EMAIL', 'PERSONAL_PHOTO'];
        } elseif ($accessLevel === 'boss') {
            // Руководитель может редактировать ограниченный набор полей (без email для безопасности)
            $allowedFields = ['NAME', 'LAST_NAME', 'WORK_POSITION', 'PERSONAL_MOBILE', 'WORK_PHONE', 'PERSONAL_PHONE', 'PERSONAL_PHOTO'];
        }
        
        $updateFields = [];
        foreach ($allowedFields as $fieldName) {
            if (isset($fields[$fieldName])) {
                // Обрабатываем загрузку фото
                if ($fieldName === 'PERSONAL_PHOTO' && is_array($fields[$fieldName])) {
                    // Это файл из $_FILES
                    // Используем CFile::MakeFileArray для правильной подготовки
                    $fileArray = [
                        'name' => $fields[$fieldName]['name'],
                        'size' => $fields[$fieldName]['size'],
                        'tmp_name' => $fields[$fieldName]['tmp_name'],
                        'type' => $fields[$fieldName]['type'],
                        'MODULE_ID' => 'main',
                        'old_file' => 0,
                        'del' => 'N'
                    ];
                    
                    // Получаем старое фото для удаления
                    $rsUser = CUser::GetByID($userId);
                    if ($arUser = $rsUser->Fetch()) {
                        if (!empty($arUser['PERSONAL_PHOTO'])) {
                            $fileArray['old_file'] = $arUser['PERSONAL_PHOTO'];
                            $fileArray['del'] = 'Y';
                        }
                    }
                    
                    // Передаем массив напрямую в CUser::Update
                    // CUser::Update сам вызовет CFile::SaveFile
                    $updateFields[$fieldName] = $fileArray;
                    
                } else {
                    $updateFields[$fieldName] = $fields[$fieldName];
                }
            }
        }
        
        if (empty($updateFields)) {
            return ['success' => false, 'error' => 'Нет данных для обновления'];
        }
        
        // Обновляем поля пользователя в Битрикс
        $user = new CUser;
        $result = $user->Update($userId, $updateFields);
        
        if (!$result) {
            return ['success' => false, 'error' => $user->LAST_ERROR ?: 'Ошибка при сохранении'];
        }

        // Синхронизация с B24 (только если у пользователя есть связь с B24)
        $userObject = $this->getUserData($userId);
        if (isset($userObject['UF_B24_USER_ID']) && !empty($userObject['UF_B24_USER_ID'])) {
            $b24Fields = [];
            
            // Формируем только те поля, которые были изменены
            if (isset($fields['NAME'])) {
                $b24Fields['NAME'] = $fields['NAME'];
            }
            if (isset($fields['LAST_NAME'])) {
                $b24Fields['LAST_NAME'] = $fields['LAST_NAME'];
            }
            if (isset($fields['WORK_POSITION'])) {
                $b24Fields['POST'] = $fields['WORK_POSITION'];
            }
            
            // Телефоны - собираем все доступные
            $phones = [];
            if (isset($fields['PERSONAL_PHONE']) && !empty($fields['PERSONAL_PHONE'])) {
                $phones[] = [
                    "VALUE" => $fields['PERSONAL_PHONE'],
                    "VALUE_TYPE" => "MOBILE"
                ];
            }
            if (isset($fields['WORK_PHONE']) && !empty($fields['WORK_PHONE'])) {
                $phones[] = [
                    "VALUE" => $fields['WORK_PHONE'],
                    "VALUE_TYPE" => "WORK"
                ];
            }
            if (isset($fields['PERSONAL_MOBILE']) && !empty($fields['PERSONAL_MOBILE'])) {
                $phones[] = [
                    "VALUE" => $fields['PERSONAL_MOBILE'],
                    "VALUE_TYPE" => "MOBILE"
                ];
            }
            if (!empty($phones)) {
                $b24Fields['PHONE'] = $phones;
            }
            
            // Email
            if (isset($fields['EMAIL']) && !empty($fields['EMAIL'])) {
                $b24Fields['EMAIL'] = [[
                    "VALUE" => $fields['EMAIL'],
                    "VALUE_TYPE" => "WORK"
                ]];
            }
            
            // Отправляем в B24 только если есть что обновлять
            if (!empty($b24Fields)) {
                try {
                    $b24Response = sendRequestB24("crm.contact.update", [
                        "id" => $userObject['UF_B24_USER_ID'],
                        "fields" => $b24Fields
                    ]);
                    
                    // Логируем результат (опционально)
                    if (isset($b24Response['error'])) {
                        // Ошибка синхронизации с B24, но данные на сайте уже сохранены
                        // Можно залогировать для мониторинга
                        error_log("B24 sync error for user {$userId}: " . print_r($b24Response['error'], true));
                    }
                } catch (\Exception $e) {
                    // Ошибка не критична - данные уже сохранены на сайте
                    error_log("B24 sync exception for user {$userId}: " . $e->getMessage());
                }
            }
        }

        return ['success' => true, 'message' => 'Профиль успешно обновлен'];
    }

    /**
     * Привязка пользователя к компании
     */
    public function attachToCompanyAction($userId, $companyId, $role = 'employee')
    {
        global $USER;
        
        if (!$USER->IsAuthorized()) {
            return ['success' => false, 'error' => 'Не авторизован'];
        }
        
        if (!Loader::includeModule('iblock')) {
            return ['success' => false, 'error' => 'Модуль iblock не подключен'];
        }
        
        $userId = intval($userId);
        $companyId = intval($companyId);
        
        // Проверяем права на редактирование
        if (!$this->checkEditAccess($userId)) {
            return ['success' => false, 'error' => 'Нет прав на редактирование'];
        }
        
        // Получаем компанию
        $rsCompany = CIBlockElement::GetById($companyId);
        if (!$companyElement = $rsCompany->GetNextElement()) {
            return ['success' => false, 'error' => 'Компания не найдена'];
        }
        
        $companyProps = $companyElement->GetProperties();
        
        // Определяем свойство для добавления
        $propertyCode = ($role === 'boss') ? 'OS_COMPANY_BOSS' : 'OS_COMPANY_USERS';
        
        // Получаем текущие значения
        $currentValues = $companyProps[$propertyCode]['VALUE'] ?? [];
        if (!is_array($currentValues)) {
            $currentValues = $currentValues ? [$currentValues] : [];
        }
        
        // Проверяем, не добавлен ли уже
        if (in_array($userId, $currentValues)) {
            return ['success' => false, 'error' => 'Пользователь уже привязан к компании'];
        }
        
        // Добавляем пользователя
        $currentValues[] = $userId;
        
        // Обновляем свойство
        CIBlockElement::SetPropertyValuesEx($companyId, 57, [
            $propertyCode => $currentValues
        ]);
        
        return ['success' => true, 'message' => 'Пользователь успешно привязан к компании'];
    }

    /**
     * Отвязка пользователя от компании
     */
    public function detachFromCompanyAction($userId, $companyId)
    {
        global $USER;
        
        if (!$USER->IsAuthorized()) {
            return ['success' => false, 'error' => 'Не авторизован'];
        }
        
        if (!Loader::includeModule('iblock')) {
            return ['success' => false, 'error' => 'Модуль iblock не подключен'];
        }
        
        $userId = intval($userId);
        $companyId = intval($companyId);
        
        // Проверяем права на редактирование
        if (!$this->checkEditAccess($userId)) {
            return ['success' => false, 'error' => 'Нет прав на редактирование'];
        }
        
        // Получаем компанию
        $rsCompany = CIBlockElement::GetById($companyId);
        if (!$companyElement = $rsCompany->GetNextElement()) {
            return ['success' => false, 'error' => 'Компания не найдена'];
        }
        
        $companyProps = $companyElement->GetProperties();
        
        // Удаляем из обоих свойств
        $updated = false;
        
        foreach (['OS_COMPANY_BOSS', 'OS_COMPANY_USERS'] as $propertyCode) {
            $currentValues = $companyProps[$propertyCode]['VALUE'] ?? [];
            if (!is_array($currentValues)) {
                $currentValues = $currentValues ? [$currentValues] : [];
            }
            
            $key = array_search($userId, $currentValues);
            if ($key !== false) {
                unset($currentValues[$key]);
                $currentValues = array_values($currentValues);
                
                CIBlockElement::SetPropertyValuesEx($companyId, 57, [
                    $propertyCode => $currentValues
                ]);
                
                $updated = true;
            }
        }
        
        if ($updated) {
            return ['success' => true, 'message' => 'Пользователь успешно отвязан от компании'];
        } else {
            return ['success' => false, 'error' => 'Пользователь не был привязан к компании'];
        }
    }

    /**
     * Смена роли пользователя в компании
     */
    public function changeRoleAction($userId, $companyId, $newRole)
    {
        global $USER;
        
        if (!$USER->IsAuthorized()) {
            return ['success' => false, 'error' => 'Не авторизован'];
        }
        
        if (!Loader::includeModule('iblock')) {
            return ['success' => false, 'error' => 'Модуль iblock не подключен'];
        }
        
        $userId = intval($userId);
        $companyId = intval($companyId);
        
        // Проверяем права на редактирование
        if (!$this->checkEditAccess($userId)) {
            return ['success' => false, 'error' => 'Нет прав на редактирование'];
        }
        
        // Получаем компанию
        $rsCompany = CIBlockElement::GetById($companyId);
        if (!$companyElement = $rsCompany->GetNextElement()) {
            return ['success' => false, 'error' => 'Компания не найдена'];
        }
        
        $companyProps = $companyElement->GetProperties();
        
        // Определяем откуда и куда перемещаем
        $fromProperty = ($newRole === 'boss') ? 'OS_COMPANY_USERS' : 'OS_COMPANY_BOSS';
        $toProperty = ($newRole === 'boss') ? 'OS_COMPANY_BOSS' : 'OS_COMPANY_USERS';
        
        // Удаляем из старого свойства
        $fromValues = $companyProps[$fromProperty]['VALUE'] ?? [];
        if (!is_array($fromValues)) {
            $fromValues = $fromValues ? [$fromValues] : [];
        }
        
        $key = array_search($userId, $fromValues);
        if ($key === false) {
            return ['success' => false, 'error' => 'Пользователь не найден в текущей роли'];
        }
        
        unset($fromValues[$key]);
        $fromValues = array_values($fromValues);
        
        // Добавляем в новое свойство
        $toValues = $companyProps[$toProperty]['VALUE'] ?? [];
        if (!is_array($toValues)) {
            $toValues = $toValues ? [$toValues] : [];
        }
        
        if (!in_array($userId, $toValues)) {
            $toValues[] = $userId;
        }
        
        // Обновляем оба свойства
        CIBlockElement::SetPropertyValuesEx($companyId, 57, [
            $fromProperty => $fromValues,
            $toProperty => $toValues
        ]);
        
        return ['success' => true, 'message' => 'Роль успешно изменена'];
    }
}

