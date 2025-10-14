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
        
        return [
            'USER' => $arUser,
            'COMPANIES' => $companies,
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
