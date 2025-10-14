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
        
        // Группируем компании по холдингам
        $holdingsData = $this->groupCompaniesByHoldings($companies);
        
        // Получаем дополнительную информацию
        $userGroups = CUser::GetUserGroup($userId);
        
        // Получаем менеджеров пользователя
        $managers = $this->getUserManagers($userId);
        
        // Получаем руководителей компаний пользователя
        $bosses = $this->getCompanyBosses($companies, $userId);
        
        return [
            'USER' => $arUser,
            'COMPANIES' => $companies,
            'HOLDINGS_DATA' => $holdingsData,
            'MANAGERS' => $managers,
            'BOSSES' => $bosses,
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
            ['ID', 'NAME', 'CODE', 'DETAIL_PAGE_URL']
        );
        
        while ($arCompanyElement = $rsBossCompanies->GetNextElement()) {
            $arCompanyFields = $arCompanyElement->GetFields();
            $arCompanyProps = $arCompanyElement->GetProperties();
            
            $companies[] = [
                'TYPE' => 'boss',
                'DATA' => array_merge($arCompanyFields, [
                    'PROPERTIES' => $arCompanyProps
                ])
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
                if ($company['DATA']['ID'] == $arCompanyFields['ID']) {
                    $isAlreadyBoss = true;
                    break;
                }
            }
            
            if (!$isAlreadyBoss) {
                $companies[] = [
                    'TYPE' => 'employee',
                    'DATA' => array_merge($arCompanyFields, [
                        'PROPERTIES' => $arCompanyProps
                    ])
                ];
            }
        }
        
        return $companies;
    }

    /**
     * Группировка компаний по холдингам
     */
    private function groupCompaniesByHoldings($companies)
    {
        $holdingsData = [];
        $processedHoldings = [];
        
        foreach ($companies as $companyData) {
            $company = $companyData['DATA'];
            $holdingKey = null;
            $headCompany = null;
            $childCompanies = [];
            
            // Проверяем, является ли компания головной холдинга
            if (!empty($company['PROPERTIES']['OS_COMPANY_IS_HEAD_OF_HOLDING']['VALUE']) && 
                ($company['PROPERTIES']['OS_COMPANY_IS_HEAD_OF_HOLDING']['VALUE'] === 'Y' || 
                 $company['PROPERTIES']['OS_COMPANY_IS_HEAD_OF_HOLDING']['VALUE'] === 'Да')) {
                
                $holdingKey = 'head_' . $company['ID'];
                
                if (in_array($holdingKey, $processedHoldings)) {
                    continue;
                }
                
                $headCompany = $company;
                
                // Получаем дочерние компании
                $rsChildCompanies = CIBlockElement::GetList(
                    [],
                    [
                        'IBLOCK_ID' => 57,
                        'PROPERTY_OS_HOLDING_OF' => $company['ID']
                    ],
                    false,
                    false,
                    ['ID']
                );
                
                while ($childCompany = $rsChildCompanies->GetNext()) {
                    $childCompanies[] = $childCompany['ID'];
                }
                
            } else if (!empty($company['PROPERTIES']['OS_HOLDING_OF']['VALUE'])) {
                
                $holdingId = $company['PROPERTIES']['OS_HOLDING_OF']['VALUE'];
                $holdingKey = 'head_' . $holdingId;
                
                if (in_array($holdingKey, $processedHoldings)) {
                    continue;
                }
                
                // Получаем головную компанию
                $rsHeadCompany = CIBlockElement::GetById($holdingId);
                if ($headCompanyElement = $rsHeadCompany->GetNextElement()) {
                    $headCompanyFields = $headCompanyElement->GetFields();
                    $headCompanyProps = $headCompanyElement->GetProperties();
                    $headCompany = array_merge($headCompanyFields, [
                        'PROPERTIES' => $headCompanyProps
                    ]);
                }
                
                // Получаем все дочерние компании
                $rsHoldingCompanies = CIBlockElement::GetList(
                    [],
                    [
                        'IBLOCK_ID' => 57,
                        'PROPERTY_OS_HOLDING_OF' => $holdingId
                    ],
                    false,
                    false,
                    ['ID']
                );
                
                while ($holdingCompany = $rsHoldingCompanies->GetNext()) {
                    $childCompanies[] = $holdingCompany['ID'];
                }
                
            } else {
                
                $holdingKey = 'standalone_' . $company['ID'];
                
                if (in_array($holdingKey, $processedHoldings)) {
                    continue;
                }
                
                $headCompany = $company;
            }
            
            if ($headCompany) {
                $holdingsData[] = [
                    'head_company' => $headCompany,
                    'child_companies' => $childCompanies
                ];
                $processedHoldings[] = $holdingKey;
            }
        }
        
        return $holdingsData;
    }

    /**
     * Получение руководителей компаний пользователя
     */
    private function getCompanyBosses($companies, $userId)
    {
        $bosses = [];
        $processedBossIds = [];
        
        foreach ($companies as $companyData) {
            $company = $companyData['DATA'];
            
            // Получаем руководителей компании
            $bossIds = $company['PROPERTIES']['OS_COMPANY_BOSS']['VALUE'] ?? [];
            
            if (!is_array($bossIds)) {
                $bossIds = $bossIds ? [$bossIds] : [];
            }
            
            foreach ($bossIds as $bossId) {
                // Исключаем самого пользователя и уже обработанных руководителей
                if ($bossId && $bossId != $userId && !in_array($bossId, $processedBossIds)) {
                    $rsUser = CUser::GetByID($bossId);
                    if ($boss = $rsUser->Fetch()) {
                        $bosses[] = $boss;
                        $processedBossIds[] = $bossId;
                    }
                }
            }
        }
        
        return $bosses;
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
        
        // Проверяем, является ли текущий пользователь руководителем компании, где userId является сотрудником
        $currentUserId = $USER->GetID();
        if ($currentUserId && $userId != $currentUserId) {
            // Получаем компании просматриваемого пользователя
            $targetUserCompanies = $this->getUserCompanies($userId);
            
            foreach ($targetUserCompanies as $companyData) {
                $company = $companyData['DATA'];
                
                // Получаем руководителей компании
                $bossIds = $company['PROPERTIES']['OS_COMPANY_BOSS']['VALUE'] ?? [];
                
                if (!is_array($bossIds)) {
                    $bossIds = $bossIds ? [$bossIds] : [];
                }
                
                // Проверяем, является ли текущий пользователь руководителем этой компании
                if (in_array($currentUserId, $bossIds)) {
                    return true;
                }
            }
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
