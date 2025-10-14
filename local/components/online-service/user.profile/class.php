<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class OnlineServiceUserProfileComponent extends CBitrixComponent implements Controllerable
{
    /**
     * Инициализация компонента
     */
    public function onPrepareComponentParams($arParams)
    {
        // Приводим параметры к нужным типам
        $arParams['USER_ID'] = intval($arParams['USER_ID']);
        
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
        $this->arResult = $this->getUserData($userId);
        
        // Проверяем права доступа
        if (!$this->checkAccess($this->arResult)) {
            ShowError('Доступ запрещен');
            return;
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
        global $USER;
        
        // Получаем основную информацию о пользователе
        $rsUser = CUser::GetByID($userId);
        if (!$arUser = $rsUser->Fetch()) {
            return false;
        }

        // Получаем информацию о компаниях пользователя
        $companies = $this->getUserCompanies($userId);
        
        // Получаем дополнительную информацию
        $userGroups = CUser::GetUserGroup($userId);
        
        // Получаем менеджеров пользователя
        $managers = $this->getUserManagers($userId);
        
        return [
            'USER' => $arUser,
            'COMPANIES' => $companies,
            'MANAGERS' => $managers,
            'USER_GROUPS' => $userGroups,
            'IS_CURRENT_USER' => ($USER->GetID() == $userId),
            'CAN_EDIT' => $this->canEditProfile($userId)
        ];
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
                'IBLOCK_ID' => 57, // ID инфоблока компаний
                'PROPERTY_OS_COMPANY_BOSS' => $userId,
                'ACTIVE' => 'Y'
            ],
            false,
            false,
            ['ID', 'NAME', 'CODE', 'DETAIL_PAGE_URL', 'PROPERTY_OS_COMPANY_NAME', 'PROPERTY_OS_IS_MARKETING_AGENT']
        );
        
        while ($arCompany = $rsBossCompanies->GetNext()) {
            $companies[] = [
                'TYPE' => 'boss',
                'DATA' => $arCompany
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
            ['ID', 'NAME', 'CODE', 'DETAIL_PAGE_URL', 'PROPERTY_OS_COMPANY_NAME', 'PROPERTY_OS_IS_MARKETING_AGENT']
        );
        
        while ($arCompany = $rsEmployeeCompanies->GetNext()) {
            // Проверяем, не является ли пользователь уже руководителем этой компании
            $isAlreadyBoss = false;
            foreach ($companies as $company) {
                if ($company['DATA']['ID'] == $arCompany['ID']) {
                    $isAlreadyBoss = true;
                    break;
                }
            }
            
            if (!$isAlreadyBoss) {
                $companies[] = [
                    'TYPE' => 'employee',
                    'DATA' => $arCompany
                ];
            }
        }
        
        return $companies;
    }

    /**
     * Получение менеджеров пользователя
     */
    private function getUserManagers($userId)
    {
        $managers = [];
        
        if (!Loader::includeModule('iblock')) {
            return [];
        }
        
        // Получаем значения пользовательских полей
        $rsUser = CUser::GetByID($userId);
        if ($arUser = $rsUser->Fetch()) {
            // Собираем ID менеджеров из обоих полей
            $managerIds = [];
            
            // UF_MANAGER может быть массивом или одиночным значением
            if (!empty($arUser['UF_MANAGER'])) {
                if (is_array($arUser['UF_MANAGER'])) {
                    $managerIds = array_merge($managerIds, $arUser['UF_MANAGER']);
                } else {
                    $managerIds[] = $arUser['UF_MANAGER'];
                }
            }
            
            // UF_MANAGER2 может быть массивом или одиночным значением
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

    /**
     * Проверка прав доступа
     */
    private function checkAccess($arResult)
    {
        global $USER;
        
        if (!$arResult || !$arResult['USER']) {
            return false;
        }
        
        // Админы могут видеть всех
        if ($USER->IsAdmin()) {
            return true;
        }
        
        // Пользователь может видеть свой профиль
        if ($USER->GetID() == $arResult['USER']['ID']) {
            return true;
        }
        
        // Проверяем, есть ли общие компании
        $currentUserId = $USER->GetID();
        $currentUserCompanies = $this->getUserCompanies($currentUserId);
        
        foreach ($arResult['COMPANIES'] as $userCompany) {
            foreach ($currentUserCompanies as $currentCompany) {
                if ($userCompany['DATA']['ID'] == $currentCompany['DATA']['ID']) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Проверка права на редактирование профиля
     */
    private function canEditProfile($userId)
    {
        global $USER;
        
        // Админы могут редактировать всех
        if ($USER->IsAdmin()) {
            return true;
        }
        
        // Пользователь может редактировать свой профиль
        if ($USER->GetID() == $userId) {
            return true;
        }
        
        return false;
    }

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

    /**
     * AJAX действия для компонента
     */
    public function configureActions()
    {
        return [
            'updateProfile' => [
                'prefilters' => [
                    new ActionFilter\Authentication(),
                    new ActionFilter\HttpMethod(['POST']),
                ],
            ],
        ];
    }

    /**
     * Обновление профиля пользователя
     */
    public function updateProfileAction($fields)
    {
        global $USER;
        
        if (!$USER->IsAuthorized()) {
            return ['success' => false, 'error' => 'Не авторизован'];
        }
        
        $userId = $USER->GetID();
        
        // Проверяем права на редактирование
        if (!$this->canEditProfile($userId)) {
            return ['success' => false, 'error' => 'Нет прав на редактирование'];
        }
        
        // Обновляем поля пользователя
        $user = new CUser;
        $result = $user->Update($userId, $fields);
        
        if ($result) {
            return ['success' => true, 'message' => 'Профиль обновлен'];
        } else {
            return ['success' => false, 'error' => $user->LAST_ERROR];
        }
    }
}
