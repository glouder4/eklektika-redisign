<?php
define("NO_KEEP_STATISTIC", true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use OnlineService\Site\Company;
use OnlineService\B24\User as B24User;
use OnlineService\B24\Request;

/**
 * Класс для обработки добавления нового сотрудника компании
 */
class AddNewPersonHandler extends Request
{
    private $requestData = [];
    private $newUserId = null;
    
    /**
     * Валидация данных формы добавления сотрудника
     * 
     * @param array $data Данные формы
     * @return array Массив ошибок (пустой если валидация прошла)
     */
    private function validateEmployeeData(array $data): array {
        $errors = [];
        
        // Валидация ID компании
        if (empty($data['head_company_b24_id'])) {
            $errors[] = 'Не указан ID головной компании в B24';
        }
        
        if (empty($data['head_company_element_id'])) {
            $errors[] = 'Не указан ID элемента компании';
        }
        
        // Валидация персональных данных
        if (empty($data['name'])) {
            $errors[] = 'Не указано имя сотрудника';
        }
        
        if (empty($data['last_name'])) {
            $errors[] = 'Не указана фамилия сотрудника';
        }
        
        // Валидация email
        if (empty($data['email'])) {
            $errors[] = 'Не указан email сотрудника';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Некорректный формат email';
        }
        
        // Валидация логина
        if (empty($data['login'])) {
            $errors[] = 'Не указан логин сотрудника';
        }
        
        // Валидация паролей
        if (empty($data['password'])) {
            $errors[] = 'Не указан пароль';
        } elseif (strlen($data['password']) < 6) {
            $errors[] = 'Пароль должен содержать минимум 6 символов';
        }
        
        if (empty($data['confirm_password'])) {
            $errors[] = 'Не указано подтверждение пароля';
        } elseif ($data['password'] !== $data['confirm_password']) {
            $errors[] = 'Пароли не совпадают';
        }
        
        return $errors;
    }
    
    /**
     * Проверка прав доступа пользователя к компании
     * 
     * @param int $userId ID текущего пользователя
     * @param int $companyId ID компании
     * @return bool
     */
    private function checkUserAccessToCompany(int $userId, int $companyId): bool {
        $b24User = new B24User();
        $b24User->userId = $userId;

        return $b24User->getUserCompany($userId, 'boss', $companyId) !== false;
    }
    
    /**
     * Проверка существования пользователя по email или логину
     * 
     * @param string $email Email для проверки
     * @param string $login Логин для проверки
     * @return array ['exists' => bool, 'field' => string|null]
     */
    private function checkUserExists(string $email, string $login): array {
        // Проверяем email
        $existingUser = CUser::GetList(
            ($by = "ID"), 
            ($order = "ASC"),
            ['=EMAIL' => $email],
            ['FIELDS' => ['ID', 'EMAIL']]
        )->Fetch();
        
        if ($existingUser) {
            return ['exists' => true, 'field' => 'email'];
        }
        
        // Проверяем логин
        $existingLogin = CUser::GetByLogin($login)->Fetch();
        if ($existingLogin) {
            return ['exists' => true, 'field' => 'login'];
        }
        
        return ['exists' => false, 'field' => null];
    }
    
    /**
     * Получение данных компании для заполнения полей пользователя
     * 
     * @param int $companyId ID компании
     * @return array Данные компании
     */
    private function getCompanyDataForUser(int $companyId): array {
        $company = new Company();
        $companyData = $company->getCompany($companyId);
        
        if (!$companyData) {
            return [];
        }
        
        return [
            'UF_INN' => $companyData['OS_COMPANY_INN'] ?? '',
            'UF_NAME_COMPANY' => $companyData['OS_COMPANY_NAME'] ?? '',
            'UF_SITE' => $companyData['OS_COMPANY_WEB_SITE'] ?? '',
            'UF_CITY' => $companyData['OS_COMPANY_CITY'] ?? ''
        ];
    }
    
    /**
     * Создание нового пользователя
     * 
     * @param array $userData Данные пользователя
     * @param array $companyData Данные компании для заполнения UF полей
     * @return array ['success' => bool, 'user_id' => int|null, 'error' => string|null]
     */
    private function createUser(array $userData, array $companyData = [],int $contactId,array $managers = []): array {
        $user = new CUser();
        
        // Получаем группы по умолчанию
        $defGroup = COption::GetOptionString("main", "new_user_registration_def_group", "");
        $groupIds = $defGroup ? explode(",", $defGroup) : [];
        
        $arFields = [
            "NAME" => $userData['name'],
            "LAST_NAME" => $userData['last_name'],
            "EMAIL" => $userData['email'],
            "LOGIN" => $userData['login'],
            "LID" => SITE_ID,
            "ACTIVE" => "Y",
            "GROUP_ID" => $groupIds,
            "UF_ADVERSTERING_AGENT" => 1,
            "PASSWORD" => $userData['password'],
            'UF_B24_USER_ID' => $contactId,
            "UF_MANAGER" => $managers['UF_MANAGER_ID'],
            "UF_MANAGER2" => $managers['UF_MANAGER2_ID'],
            "CONFIRM_PASSWORD" => $userData['confirm_password'],
            "WORK_POSITION" => $userData['work_position'] ?? ''
        ];
        
        // Добавляем пользовательские поля из данных компании
        if (!empty($companyData)) {
            $arFields = array_merge($arFields, $companyData);
        }
        
        $userId = $user->Add($arFields);
        
        if (!$userId) {
            return [
                'success' => false,
                'user_id' => null,
                'error' => $user->LAST_ERROR
            ];
        }
        
        return [
            'success' => true,
            'user_id' => $userId,
            'error' => null
        ];
    }
    
    /**
     * Добавление пользователя в компанию
     * 
     * @param int $userId ID пользователя
     * @param int $companyId ID компании
     * @return array ['success' => bool, 'error' => string|null]
     */
    private function addUserToCompany(int $userId, int $companyId): array {
        $company = new Company();
        
        // Получаем данные компании
        $companyData = $company->getCompany($companyId);
        
        if (!$companyData) {
            return [
                'success' => false,
                'error' => 'Компания не найдена'
            ];
        }
        
        // Получаем текущих пользователей
        $currentUsers = $companyData['OS_COMPANY_USERS'] ?? [];
        
        // Нормализуем в массив
        if (!is_array($currentUsers)) {
            $currentUsers = !empty($currentUsers) ? [$currentUsers] : [];
        }
        
        // Добавляем нового пользователя
        if (!in_array($userId, $currentUsers)) {
            $currentUsers[] = $userId;
        }
        
        // Обновляем компанию
        CIBlockElement::SetPropertyValues(
            $companyId,
            $company->getIblockId(),
            $currentUsers,
            'OS_COMPANY_USERS'
        );
        
        return [
            'success' => true,
            'error' => null
        ];
    }
    
    /**
     * Отправка уведомления новому пользователю
     * 
     * @param array $userData Данные пользователя
     * @return void
     */
    private function sendUserNotification(array $userData): void {
        $arEventFields = [
            "EMAIL" => $userData['email'],
            "LOGIN" => $userData['login'],
            "NAME" => $userData['name'],
            "LAST_NAME" => $userData['last_name'],
            "USER_ID" => $userData['user_id'],
            "WORK_POSITION" => $userData['work_position'] ?? ''
        ];
        
        $event = new CEvent();
        $event->SendImmediate("NEW_USER", SITE_ID, $arEventFields);
    }
    
    /**
     * Получение CODE элемента менеджера из инфоблока 53
     * 
     * @param int $elementId ID элемента из инфоблока 53
     * @return string|null CODE элемента или null
     */
    private function getManagerCode(int $elementId): ?string {
        if (empty($elementId)) {
            return null;
        }
        
        $rsElement = CIBlockElement::GetByID($elementId);
        if ($arElement = $rsElement->Fetch()) {
            return $arElement['XML_ID'] ?? null;
        }
        
        return null;
    }
    
    /**
     * Получение менеджеров руководителя для нового сотрудника
     * 
     * @param int $companyElementId ID элемента компании
     * @return array ['UF_MANAGER' => string|null, 'UF_MANAGER2' => string|null]
     */
    private function getManagersFromBoss(int $companyElementId): array {
        global $USER;
        
        $company = new Company();
        $companyData = $company->getCompany($companyElementId);
        
        if (!$companyData) {
            return ['UF_MANAGER' => null, 'UF_MANAGER2' => null];
        }
        
        $bossId = null;
        
        // Определяем, от кого брать менеджеров
        if ($USER->IsAdmin()) {
            // Если админ - берем первого руководителя компании
            $bosses = $companyData['OS_COMPANY_BOSS'] ?? [];
            if (!is_array($bosses)) {
                $bosses = $bosses ? [$bosses] : [];
            }
            $bossId = !empty($bosses) ? $bosses[0] : null;
        } else {
            // Если не админ - берем текущего пользователя
            $bossId = $USER->GetID();
        }
        
        if (!$bossId) {
            return ['UF_MANAGER' => null, 'UF_MANAGER2' => null];
        }
        
        // Получаем данные руководителя
        $rsUser = CUser::GetByID($bossId);
        $userData = $rsUser->Fetch();
        
        if (!$userData) {
            return ['UF_MANAGER' => null, 'UF_MANAGER2' => null];
        }
        
        // Получаем ID элементов менеджеров из полей пользователя
        $managerId = $userData['UF_MANAGER'] ?? null;
        $manager2Id = $userData['UF_MANAGER2'] ?? null;
        
        // Конвертируем ID элементов в CODE
        return [
            'UF_MANAGER_ID' => $managerId,
            'UF_MANAGER2_ID' => $manager2Id,
            'UF_MANAGER' => !is_null($managerId) ? $this->getManagerCode($managerId) : null,
            'UF_MANAGER2' => !is_null($manager2Id) ? $this->getManagerCode($manager2Id) : null
        ];
    }
    
    /**
     * Отправка данных в Bitrix24
     * 
     * @param array $requestData Данные запроса
     * @return int|false ID созданного контакта или false
     */
    private function sendToBitrix24($requestData,$managers) {
        $company = new Company();
        $companyData = $company->getCompany($requestData['head_company_element_id']);
        $companyId = $companyData['OS_COMPANY_B24_ID'] ?? null;


        // Данные для контакта
        $dataContact = [
            'fields' => [
                'NAME' => $requestData['name'],
                'LAST_NAME' => $requestData['last_name'],
                'POST' => $requestData['work_position'],
                'OPENED' => 'Y',
                'EMAIL' => [[
                    "VALUE" => $requestData['email'],
                    "VALUE_TYPE" => "WORK"
                ]],
                'UF_CRM_1698752707853' => 1
            ],
            'params' => []
        ];
        
        // Добавляем менеджеров, если они заполнены
        if (!empty($managers['UF_MANAGER'])) {
            $dataContact['fields']['ASSIGNED_BY_ID'] = $managers['UF_MANAGER'];
        }
        else{
            $dataContact['fields']['ASSIGNED_BY_ID'] = 3036;
        }
        
        if (!empty($managers['UF_MANAGER2'])) {
            $dataContact['fields']['UF_CRM_1757682312'] = $managers['UF_MANAGER2'];
        }

        $contactId = sendRequestB24("crm.contact.add", $dataContact);

        if (!empty($companyId) && !empty($contactId)) {
            // Добавить контакт в компанию
            $qrCompanyAddContact = [
                'fields' => ['COMPANY_ID' => $companyId],
                'id' => $contactId
            ];
            sendRequestB24("crm.contact.company.add", $qrCompanyAddContact);
        }

        return $contactId;
    }
    
    /**
     * Возврат JSON ответа
     * 
     * @param array $response Данные ответа
     * @return void
     */
    private function jsonResponse(array $response): void {
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    /**
     * Главный метод обработки запроса
     */
    public function process(): void {
        global $USER;
        
        header('Content-Type: application/json; charset=utf-8');
        
        // Проверка авторизации
        if (!$USER->IsAuthorized()) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Необходимо авторизоваться для выполнения этого действия'
            ]);
        }
        
        // Проверка метода запроса
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Неверный метод запроса'
            ]);
        }
        
        try {
            // Извлечение данных из POST
            $this->requestData = [
                'head_company_b24_id' => trim($_POST['head_company_id'] ?? ''),
                'head_company_element_id' => intval($_POST['head_company_element_id'] ?? 0),
                'name' => trim($_POST['REGISTER']['NAME'] ?? ''),
                'last_name' => trim($_POST['REGISTER']['LAST_NAME'] ?? ''),
                'email' => trim($_POST['REGISTER']['EMAIL'] ?? ''),
                'login' => trim($_POST['REGISTER']['LOGIN'] ?? ''),
                'work_position' => trim($_POST['REGISTER']['WORK_POSITION'] ?? ''),
                'password' => trim($_POST['REGISTER']['PASSWORD'] ?? ''),
                'confirm_password' => trim($_POST['REGISTER']['CONFIRM_PASSWORD'] ?? '')
            ];
            
            // Валидация данных
            $validationErrors = $this->validateEmployeeData($this->requestData);
            if (!empty($validationErrors)) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Ошибка валидации: ' . implode('; ', $validationErrors)
                ]);
            }
            
            // Проверка прав доступа
            if (!$this->checkUserAccessToCompany($USER->GetID(), $this->requestData['head_company_element_id'])) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => $USER->GetID().'У вас нет прав для добавления сотрудников в эту компанию'
                ]);
            }
            
            // Проверка существования пользователя
            $userExists = $this->checkUserExists($this->requestData['email'], $this->requestData['login']);
            if ($userExists['exists']) {
                $field = $userExists['field'] === 'email' ? 'email' : 'логином';
                $this->jsonResponse([
                    'success' => false,
                    'message' => "Пользователь с таким {$field} уже существует"
                ]);
            }



            // Получаем менеджеров от руководителя
            $managers = $this->getManagersFromBoss($this->requestData['head_company_element_id']);
            // СНАЧАЛА отправляем запрос в Bitrix24
            $contactId = $this->sendToBitrix24($this->requestData,$managers) ?? false;
            
            // Проверяем успешность ответа от B24
            if (!$contactId) {
                $errorMessage = $b24Response['error'] ?? 'Неизвестная ошибка Bitrix24';
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Ошибка при создании контакта в Bitrix24: ' . $errorMessage
                ]);
            }
            
            // Получение данных компании для заполнения UF полей пользователя
            $companyDataForUser = $this->getCompanyDataForUser($this->requestData['head_company_element_id']);
            
            // Создание пользователя с данными компании (только если B24 успешно)
            $createResult = $this->createUser($this->requestData, $companyDataForUser,$contactId,$managers);
            if (!$createResult['success']) {
                $this->jsonResponse([
                    'success' => false,
                    'message' => 'Ошибка создания пользователя: ' . $createResult['error']
                ]);
            }
            
            $this->newUserId = $createResult['user_id'];
            
            // Добавление пользователя в компанию
            $addToCompanyResult = $this->addUserToCompany($this->newUserId, $this->requestData['head_company_element_id']);
            if (!$addToCompanyResult['success']) {
                // Откатываем создание пользователя
                CUser::Delete($this->newUserId);
                
                $this->jsonResponse([
                    'success' => false,
                    'message' => $addToCompanyResult['error']
                ]);
            }
            
            // Отправка уведомления
            $this->sendUserNotification([
                'email' => $this->requestData['email'],
                'login' => $this->requestData['login'],
                'name' => $this->requestData['name'],
                'last_name' => $this->requestData['last_name'],
                'user_id' => $this->newUserId,
                'work_position' => $this->requestData['work_position']
            ]);
            
            // Успешный результат
            $this->jsonResponse([
                'success' => true,
                'message' => 'Сотрудник успешно добавлен в компанию',
                'data' => [
                    'user_id' => $this->newUserId,
                    'email' => $this->requestData['email'],
                    'login' => $this->requestData['login'],
                    'b24_response' => $b24Response
                ]
            ]);
            
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Произошла ошибка: ' . $e->getMessage()
            ]);
        }
    }
}

// ============================================================================
// ЗАПУСК ОБРАБОТЧИКА
// ============================================================================

$handler = new AddNewPersonHandler();
$handler->process();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
