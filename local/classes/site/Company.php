<?php
    namespace OnlineService\Site;

    use OnlineService\B24\User;

    class Company{
        private int $iblock_id = 57;
        private static $codeProps = [
            "OS_COMPANY_IS_HEAD_OF_HOLDING",
            "OS_COMPANY_BOSS",
            "OS_HEAD_COMPANY_B24_ID",
            "OS_HOLDING_OF",
            "OS_COMPANY_INN",
            "OS_COMPANY_WEB_SITE",
            "OS_COMPANY_USERS",
            "OS_COMPANY_NAME",
            "OS_COMPANY_PHONE",
            "OS_COMPANY_EMAIL",
            "OS_COMPANY_B24_ID",
            'OS_COMPANY_CITY',
            'OS_IS_MARKETING_AGENT',
            "OS_IS_COMPANY_DISABLED",
            "OS_COMPANY_STATUS",
            'OS_REQUSITES_FILE'
        ];

        /**
         * Получить ID инфоблока компаний
         * @return int
         */
        public function getIblockId(): int {
            return $this->iblock_id;
        }

        public function createCompanyElement($params){
            /*$params = [
                'OS_COMPANY_INN'
                'OS_COMPANY_WEB_SITE'
                'OS_COMPANY_NAME'
                'OS_COMPANY_EMAIL'
                'OS_COMPANY_PHONE'
                'OS_COMPANY_B24_ID' - ID уже существующей компании
                'OS_COMPANY_CITY'
                'OS_REQUSITES_FILE',
                'USER_ID'
            ]; */


            // Ищем существующую компанию по OS_COMPANY_B24_ID
            $existingCompany = $this->getCompanyByB24ID($params['OS_COMPANY_B24_ID']);
            
            if ($existingCompany && !empty($existingCompany['ID'])) {
                // Компания найдена - дописываем пользователя в OS_COMPANY_USERS
                $companyId = $existingCompany['ID'];
                $currentUsers = $existingCompany['OS_COMPANY_USERS'] ?? [];
                
                // Если это массив, добавляем новый ID, иначе создаем массив
                if (is_array($currentUsers)) {
                    if (!in_array($params['USER_ID'], $currentUsers)) {
                        $currentUsers[] = $params['USER_ID'];
                    }
                } else {
                    $currentUsers = [$currentUsers, $params['USER_ID']];
                }
                
                // Обновляем свойство OS_COMPANY_USERS
                \CIBlockElement::SetPropertyValues(
                    $companyId,
                    $this->iblock_id,
                    $currentUsers,
                    'OS_COMPANY_USERS'
                );
                
                return $companyId;
            } else {
                // Компания не найдена - создаем новую
                $el = new \CIBlockElement;

                // Устанавливаем пользователя в OS_COMPANY_USERS для новой компании
                $params['OS_COMPANY_USERS'] = [$params['USER_ID']];

                $arLoadProductArray = [
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_TYPE" => 'personal',
                    "IBLOCK_ID" => $this->iblock_id,
                    "PROPERTY_VALUES" => $params,
                    "NAME" => $params["OS_COMPANY_NAME"],
                    "ACTIVE" => "N",
                    "CODE" => $params["OS_COMPANY_B24_ID"]
                ];

                if ($companyId = $el->Add($arLoadProductArray)) {
                    return $companyId;
                }
                
                return false;
            }
        }

        protected array $orderCustomFieldIds = [8 => "OS_COMPANY_NAME",10 => "OS_COMPANY_INN",12 => "USER_NAME__USER_LASTNAME",13 => "OS_COMPANY_EMAIL",14 => "OS_COMPANY_PHONE"];

        /**
         * Обновляет элемент компании в инфоблоке по B24_ID.
         *
         * @param array $params Массив параметров компании:
         *   - OS_COMPANY_B24_ID (string|int) — ID компании в B24 (обязательный)
         *   - OS_COMPANY_NAME (string) — Название компании
         *   - OS_COMPANY_IS_HEAD_OF_HOLDING (boolean) — Головная компания
         *   - OS_COMPANY_STATUS (string|int) — Статус компании
         *   - OS_COMPANY_USERS (array|int) — ID связанных контактов
         *   - OS_COMPANY_INN (string) — ИНН компании
         *   - OS_COMPANY_CITY (string) — Город компании
         *   - OS_COMPANY_WEB_SITE (string) — Сайт компании
         *   - OS_COMPANY_PHONE (string) — Телефон компании
         *   - OS_COMPANY_EMAIL (string) — Email компании
         *   и другие свойства, поддерживаемые инфоблоком.
         *
         * @return int|false ID обновлённой компании или false в случае ошибки
         */
        public function updateCompanyElement($params){
            // Находим компанию по B24_ID
            $b24_id = $params['OS_COMPANY_B24_ID'];
            $company = $this->getCompanyByB24ID($b24_id);

            if ($company && !empty($company['ID'])) {
                // Компания найдена - обновляем
                $companyId = $company['ID'];
                
                if (!empty($params['OS_COMPANY_STATUS'])) {
                    $params['OS_COMPANY_STATUS'] = (new UserGroups([]))->searchGroup($params['OS_COMPANY_STATUS'])['ID'];
                }

                if( $params['OS_COMPANY_USERS'] ){
                    foreach ($params['OS_COMPANY_USERS'] as $key => $b24_id){
                        $user = new User();
                        $userId = $user->getUserIDByB24ID($b24_id);

                        if( $userId ){
                            $params['OS_COMPANY_USERS'][$key] =  $userId;

                            $groups = [];
                            if( $params['OS_IS_MARKETING_AGENT']['VALUE'] ){
                                $groups[] = $user->getMarketingGroupId();
                            }
                            if ($params['OS_COMPANY_STATUS']){
                                $groups[] = $params['OS_COMPANY_STATUS'];
                            }

                            $user->addUserToGroups($userId,$groups);
                        }
                    }
                }

                if (!empty($params['OS_REQUSITES_FILE'])) {
                    $fileId = $this->processRequisitesFile($params['OS_REQUSITES_FILE']);
                    if ($fileId) {
                        $params['OS_REQUSITES_FILE'] = $fileId;
                    }
                }

                if( !empty($params['OS_HOLDING_OF']) && $params['OS_HOLDING_OF'] ){
                    $params['OS_HOLDING_OF'] = $this->getCompanyByB24ID($params['OS_HOLDING_OF']);
                }

                // Получаем текущие значения всех свойств компании
                $currentProps = [];
                foreach (self::$codeProps as $code) {
                    $propertyValues = \CIBlockElement::GetProperty(
                        $this->iblock_id,
                        $companyId,
                        [],
                        ["CODE" => $code]
                    );
                    
                    $values = [];
                    $isMultiple = false;
                    while ($prop = $propertyValues->GetNext()) {
                        $values[] = $prop["VALUE"];
                        if ($prop["MULTIPLE"] === "Y") {
                            $isMultiple = true;
                        }
                    }
                    
                    if ($isMultiple) {
                        $currentProps[$code] = $values;
                    } else {
                        $currentProps[$code] = count($values) > 0 ? $values[0] : null;
                    }
                }

                // Формируем массив свойств для обновления - объединяем текущие и новые значения
                $arProps = $currentProps; // Начинаем с текущих значений
                foreach (self::$codeProps as $code) {
                    if (isset($params[$code])) {
                        $arProps[$code] = $params[$code]; // Перезаписываем только переданные значения
                    }
                }

                $params['OS_COMPANY_B24_ID'] = $company['CODE'];

                $arUpdateArray = [
                    "PROPERTY_VALUES" => $arProps,
                    "NAME" => $params["OS_COMPANY_NAME"],
                    "ACTIVE" => $params['ACTIVE'],
                ];

                $el = new \CIBlockElement;
                if ($el->Update($companyId, $arUpdateArray)) {
                    return $companyId;
                } else {
                    return false;
                }
            } else {
                // Компания не найдена - создаем новую
                $companyId = $this->createCompanyFromUpdate($params);
                
                if (!$companyId) {
                    return false;
                }
                
                // После создания компания уже содержит все данные
                return $companyId;
            }
        }

        /**
         * Создает новую компанию на основе данных из updateCompanyElement
         * @param array $params - параметры компании
         * @return int|false - ID созданной компании или false
         */
        private function createCompanyFromUpdate($params){
            if (!\CModule::IncludeModule('iblock')) {
                return false;
            }

            $el = new \CIBlockElement;
            
            // Обрабатываем пользователей
            if (!empty($params['OS_COMPANY_USERS'])) {
                foreach ($params['OS_COMPANY_USERS'] as $key => $b24_id) {
                    $user = new User();
                    $userId = $user->getUserIDByB24ID($b24_id);
                    
                    if ($userId) {
                        $params['OS_COMPANY_USERS'][$key] = $userId;
                        
                        $groups = [];
                        if (!empty($params['OS_IS_MARKETING_AGENT']['VALUE'])) {
                            $groups[] = $user->getMarketingGroupId();
                        }
                        if (!empty($params['OS_COMPANY_STATUS'])) {
                            $statusId = (new UserGroups([]))->searchGroup($params['OS_COMPANY_STATUS'])['ID'];
                            if ($statusId) {
                                $groups[] = $statusId;
                            }
                        }
                        
                        if (!empty($groups)) {
                            $user->addUserToGroups($userId, $groups);
                        }
                    }
                }
            }
            
            // Обрабатываем файл реквизитов
            if (!empty($params['OS_REQUSITES_FILE'])) {
                $fileId = $this->processRequisitesFile($params['OS_REQUSITES_FILE']);
                if ($fileId) {
                    $params['OS_REQUSITES_FILE'] = $fileId;
                }
            }
            
            // Обрабатываем связь с холдингом
            if (!empty($params['OS_HOLDING_OF'])) {
                $holdingCompany = $this->getCompanyByB24ID($params['OS_HOLDING_OF']);
                if ($holdingCompany) {
                    $params['OS_HOLDING_OF'] = $holdingCompany['ID'];
                }
            }
            
            // Формируем массив свойств
            $arProps = [];
            foreach (self::$codeProps as $code) {
                if (isset($params[$code])) {
                    $arProps[$code] = $params[$code];
                }
            }
            
            $arFields = [
                'IBLOCK_ID' => $this->iblock_id,
                'IBLOCK_TYPE' => 'personal',
                'NAME' => $params['OS_COMPANY_NAME'] ?? 'Новая компания',
                'CODE' => $params['OS_COMPANY_B24_ID'],
                'ACTIVE' => $params['ACTIVE'] ?? 'N',
                'PROPERTY_VALUES' => $arProps
            ];
            
            $companyId = $el->Add($arFields);
            
            if ($companyId) {
                return $companyId;
            }
            
            return false;
        }

        /**
         * Обрабатывает файл реквизитов - скачивает и сохраняет в Bitrix
         * @param array $fileData - данные файла из B24
         * @return int|false - ID сохраненного файла или false
         */
        private function processRequisitesFile($fileData){
            if (empty($fileData)) {
                return false;
            }
            
            try {
                $downloadableUrl = URL_B24 . $fileData['SUBDIR'] . '/' . urlencode($fileData['FILE_NAME']);
                
                // Куда сохранить
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/os_requisites/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $originalName = $fileData['ORIGINAL_NAME'];
                $filePath = $uploadDir . $originalName;
                
                // Скачиваем файл
                $fileContent = file_get_contents($downloadableUrl);
                
                if ($fileContent === false) {
                    return false;
                }
                
                // Сохраняем на сервер
                if (file_put_contents($filePath, $fileContent)) {
                    // Загружаем файл в Битрикс
                    $fileArray = \CFile::MakeFileArray($filePath, false, $originalName);
                    
                    if ($fileArray && !isset($fileArray['error'])) {
                        // Сохраняем в систему Битрикс
                        $savedFileId = \CFile::SaveFile($fileArray, 'os_requisites');
                        
                        // Удаляем временный файл
                        unlink($filePath);
                        
                        if ($savedFileId) {
                            return $savedFileId;
                        }
                    }
                    
                    // Удаляем временный файл в случае ошибки
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            } catch (\Exception $e) {
                // Ошибка обработки файла
            }
            
            return false;
        }

        public function deleteCompanyElement($params){
            $b24_id = $params['ID'];
            $company = $this->getCompanyByB24ID($b24_id);
            if ($company && !empty($company['ID'])) {
                if (\CIBlockElement::Delete($company['ID'])) {
                    return true;
                }
            }
            return false;
        }

        public function getCompany($id){
            $rsCompany = \CIBlockElement::GetById($id);
            if($ob = $rsCompany->GetNextElement()) {
                $arProps = $ob->GetProperties();
                $arFields = $ob->GetFields();
                $arCompany["ID"] = $arFields["ID"];
                foreach (self::$codeProps as $code) {
                    $arCompany[$code] = $arProps[$code]["VALUE"];
                    // Для свойств типа "Список" также сохраняем VALUE_XML_ID
                    if (isset($arProps[$code]["VALUE_XML_ID"])) {
                        $arCompany[$code . "_XML_ID"] = $arProps[$code]["VALUE_XML_ID"];
                    }
                }

                return $arCompany;
            }
            return [];
        }

        public function getProfileValues($id){
            global $USER;
            $company = $this->getCompany($id);
            $user = \CUser::GetByID($USER->GetID())->Fetch();

            $response = [];

            foreach ($this->orderCustomFieldIds as $id => $fieldName){
                $response[$id] = $company[$fieldName];
            }
            $response[12] = $user['NAME'].' '.$user['LAST_NAME'];

            return $response;
        }

        public function getCompanyByB24ID($b24_id){
            $rsCompany = \CIBlockElement::GetList(
                [],
                ['CODE' => $b24_id],
                false,
                false,
                ['ID', 'NAME', 'PROPERTY_OS_COMPANY_B24_ID','CODE','XML_ID']
            );  
            
            if ($ob = $rsCompany->GetNextElement()) {
                $arFields = $ob->GetFields();
                $arCompany["ID"] = $arFields["ID"];
                
                // Загружаем свойства через GetPropertyValues для каждого свойства отдельно
                foreach (self::$codeProps as $code) {
                    $propertyValues = \CIBlockElement::GetProperty(
                        $this->iblock_id,
                        $arFields["ID"],
                        [],
                        ["CODE" => $code]
                    );
                    
                    $values = [];
                    $isMultiple = false;
                    while ($prop = $propertyValues->GetNext()) {
                        $values[] = $prop["VALUE"];
                        // Проверяем, является ли свойство множественным
                        if ($prop["MULTIPLE"] === "Y") {
                            $isMultiple = true;
                        }
                    }
                    
                    // Для множественных свойств всегда возвращаем массив
                    if ($isMultiple) {
                        $arCompany[$code] = $values; // Всегда массив для множественных свойств
                    } else {
                        // Для обычных свойств возвращаем первое значение или null
                        $arCompany[$code] = count($values) > 0 ? $values[0] : null;
                    }
                }
                
                return $arCompany;
            }
            
            return false;
        }

        public static function query($url,$params,$debug = false){
            $queryUrl = $url;

            $curl = curl_init();
            $queryData  = http_build_query($params);

            curl_setopt_array($curl, array(
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $queryUrl,
                CURLOPT_POSTFIELDS => $queryData,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 10,
            ));

            $result = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = curl_error($curl);
            $curlErrno = curl_errno($curl);

            curl_close($curl);

            if( $debug ){
                // Логируем детали запроса
                pre("=== CURL Request Details ===");
                pre("URL: " . $queryUrl);
                pre("Params: " . print_r($params, true));
                pre("HTTP Code: " . $httpCode);
                pre("CURL Error: " . $curlError);
                pre("CURL Errno: " . $curlErrno);
                pre("Raw Response: " . $result);
            }

            // Обработка ошибок CURL
            if ($curlErrno) {
                pre("CURL Error occurred: " . $curlError);
                return [
                    'success' => 0,
                    'error' => 'CURL Error: ' . $curlError,
                    'errno' => $curlErrno
                ];
            }

            // Обработка HTTP ошибок
            if ($httpCode !== 200) {
                if( $debug )
                    pre("HTTP Error: " . $httpCode);

                return [
                    'success' => 0,
                    'error' => 'HTTP Error: ' . $httpCode,
                    'response' => $result
                ];
            }

            // Парсим JSON ответ
            $decodedResult = json_decode($result, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                if( $debug ) {
                    pre("JSON Parse Error: " . json_last_error_msg());
                    pre("Raw response that failed to parse: " . $result);
                }
                return [
                    'success' => 0,
                    'error' => 'JSON Parse Error: ' . json_last_error_msg(),
                    'raw_response' => $result
                ];
            }
            if( $debug ) {
                pre("=== Parsed Response ===");
                pre($decodedResult);
            }

            return $decodedResult;
        }

        /**
         * Синхронизация всех контактов (руководители + сотрудники) между головной компанией и всеми дочерними
         */
        public function syncCompanyContacts($params) {
            try {
                $headCompanyId = $params['COMPANY_ID'] ?? null;
                
                if (!$headCompanyId) {
                    return json_encode(['success' => false, 'error' => 'Не указан ID головной компании']);
                }

                // Получаем данные головной компании
                $headCompany = $this->getCompany($headCompanyId);
                
                // Проверяем, является ли компания головной (используем VALUE_XML_ID как в шаблоне)
                $isHeadOfHolding = $headCompany['OS_COMPANY_IS_HEAD_OF_HOLDING_XML_ID'] ?? $headCompany['OS_COMPANY_IS_HEAD_OF_HOLDING'] ?? '';
                if (!$headCompany || !in_array($isHeadOfHolding, ['Y', 'YES', '1', true])) {
                    return json_encode(['success' => false, 'error' => 'Компания не является головной. Значение: ' . $isHeadOfHolding]);
                }

                // Получаем всех руководителей головной компании
                $headCompanyManagers = $headCompany['OS_COMPANY_BOSS'] ?? [];
                if (!is_array($headCompanyManagers)) {
                    $headCompanyManagers = $headCompanyManagers ? [$headCompanyManagers] : [];
                }

                // Получаем все дочерние компании
                $childCompanies = $this->getChildCompanies($headCompanyId);
                
                // Собираем всех уникальных руководителей из ВСЕХ компаний холдинга
                $allManagers = $headCompanyManagers;
                
                foreach ($childCompanies as $childCompany) {
                    $childCompanyData = $this->getCompany($childCompany['ID']);
                    
                    // Собираем руководителей дочерней компании
                    $childManagers = $childCompanyData['OS_COMPANY_BOSS'] ?? [];
                    if (!is_array($childManagers)) {
                        $childManagers = $childManagers ? [$childManagers] : [];
                    }
                    
                    // Добавляем в общий список (с проверкой на уникальность)
                    foreach ($childManagers as $manager) {
                        if (!empty($manager) && !in_array($manager, $allManagers)) {
                            $allManagers[] = $manager;
                        }
                    }
                }
                
                $updatedCompanies = 0;
                $errors = [];
                $debugInfo = [];

                $debugInfo[] = "Головная компания ID: {$headCompanyId}";
                $debugInfo[] = "Найдено дочерних компаний: " . count($childCompanies);
                $debugInfo[] = "ИТОГО уникальных руководителей: " . count($allManagers);

                // Обновляем руководителей во всех дочерних компаниях (общим списком!)
                foreach ($childCompanies as $childCompany) {
                    $debugInfo[] = "Обновляем компанию: {$childCompany['NAME']} (ID: {$childCompany['ID']})";
                    $result = $this->updateCompanyManagers($childCompany['ID'], $allManagers);
                    if ($result) {
                        $updatedCompanies++;
                        $debugInfo[] = "✓ Компания {$childCompany['NAME']} обновлена успешно";
                    } else {
                        $errors[] = "Ошибка обновления компании {$childCompany['NAME']} (ID: {$childCompany['ID']})";
                        $debugInfo[] = "✗ Ошибка обновления компании {$childCompany['NAME']}";
                    }
                }

                // Также обновляем саму головную компанию (общим списком!)
                $this->updateCompanyManagers($headCompanyId, $allManagers);
                $updatedCompanies++;

                return json_encode([
                    'success' => true,
                    'message' => "Синхронизация завершена. Обновлено компаний: {$updatedCompanies}",
                    'updated_companies' => $updatedCompanies,
                    'errors' => $errors,
                    'managers_count' => count($allManagers),
                    'debug_info' => $debugInfo
                ]);

            } catch (Exception $e) {
                return json_encode(['success' => false, 'error' => 'Ошибка синхронизации: ' . $e->getMessage()]);
            }
        }

        /**
         * Получить все дочерние компании холдинга
         */
        private function getChildCompanies($headCompanyId) {
            $headCompany = $this->getCompany($headCompanyId);
            if (!$headCompany) {
                return [];
            }

            // Ищем все компании, у которых OS_HOLDING_OF указывает на головную компанию (по ID элемента)
            $rsCompanies = \CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID' => $this->iblock_id,
                    'PROPERTY_OS_HOLDING_OF' => $headCompanyId,
                    'ACTIVE' => 'Y'
                ],
                false,
                false,
                ['ID', 'NAME', 'CODE', 'PROPERTY_OS_HOLDING_OF']
            );

            $childCompanies = [];
            while ($ob = $rsCompanies->GetNextElement()) {
                $arFields = $ob->GetFields();
                $arProps = $ob->GetProperties();
                $childCompanies[] = [
                    'ID' => $arFields['ID'],
                    'NAME' => $arFields['NAME'],
                    'CODE' => $arFields['CODE'],
                    'OS_HOLDING_OF' => $arProps['OS_HOLDING_OF']['VALUE'] ?? null
                ];
            }

            return $childCompanies;
        }

        /**
         * Обновить руководителей компании
         */
        private function updateCompanyManagers($companyId, $managers) {
            try {
                // Убираем пустые значения
                $managers = array_filter($managers, function($manager) {
                    return !empty($manager);
                });

                // Обновляем свойство OS_COMPANY_BOSS
                \CIBlockElement::SetPropertyValues($companyId, $this->iblock_id, $managers, 'OS_COMPANY_BOSS');

                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        /**
         * Обновить профиль компании через веб-интерфейс
         * 
         * @param int $companyId - ID компании
         * @param array $data - данные для обновления:
         *   - OS_COMPANY_NAME (string) - название компании
         *   - OS_COMPANY_INN (string) - ИНН
         *   - OS_COMPANY_CITY (string) - город
         *   - OS_COMPANY_PHONE (string) - телефон
         *   - OS_COMPANY_EMAIL (string) - email
         *   - OS_COMPANY_WEB_SITE (string) - сайт
         * @param array|null $uploadedFile - данные загруженного файла из $_FILES
         * @param bool $deleteRequisites - флаг удаления файла реквизитов
         * 
         * @return array - результат операции ['success' => bool, 'message' => string, 'data' => array]
         */
        public function updateCompanyProfile($companyId, $data, $uploadedFile = null, $deleteRequisites = false) {
            if (!\CModule::IncludeModule('iblock')) {
                return [
                    'success' => false,
                    'message' => 'Ошибка подключения модуля инфоблоков'
                ];
            }

            // Проверяем существование компании
            $company = $this->getCompany($companyId);
            if (!$company) {
                return [
                    'success' => false,
                    'message' => 'Компания не найдена'
                ];
            }

            // Валидация обязательных полей
            $requiredFields = [
                'OS_COMPANY_NAME' => 'Название компании',
                'OS_COMPANY_INN' => 'ИНН',
                'OS_COMPANY_CITY' => 'Город',
                'OS_COMPANY_WEB_SITE' => 'Сайт'
            ];

            $errors = [];
            foreach ($requiredFields as $field => $fieldName) {
                if (empty($data[$field])) {
                    $errors[] = $fieldName;
                }
            }

            if (!empty($errors)) {
                return [
                    'success' => false,
                    'message' => 'Не заполнены обязательные поля: ' . implode(', ', $errors)
                ];
            }

            // Валидация email
            if (!empty($data['OS_COMPANY_EMAIL'])) {
                if (!filter_var($data['OS_COMPANY_EMAIL'], FILTER_VALIDATE_EMAIL)) {
                    return [
                        'success' => false,
                        'message' => 'Некорректный формат email'
                    ];
                }
            }

            // Обработка файла реквизитов
            $fileId = null;
            if ($uploadedFile && $uploadedFile['error'] === UPLOAD_ERR_OK) {
                $fileResult = $this->processUploadedRequisitesFile($uploadedFile);
                if (!$fileResult['success']) {
                    return $fileResult;
                }
                $fileId = $fileResult['file_id'];
            }

            // Обработка удаления файла
            if ($deleteRequisites && !empty($company['OS_REQUSITES_FILE'])) {
                \CFile::Delete($company['OS_REQUSITES_FILE']);
                $data['OS_REQUSITES_FILE'] = '';
            } elseif ($fileId) {
                // Удаляем старый файл только если новый успешно загружен
                if (!empty($company['OS_REQUSITES_FILE'])) {
                    \CFile::Delete($company['OS_REQUSITES_FILE']);
                }
                $data['OS_REQUSITES_FILE'] = $fileId;
            }

            // Начинаем обновление
            $el = new \CIBlockElement();

            // Обновляем название элемента
            $arUpdateFields = [
                'NAME' => $data['OS_COMPANY_NAME']
            ];

            if (!$el->Update($companyId, $arUpdateFields)) {
                return [
                    'success' => false,
                    'message' => 'Ошибка обновления компании: ' . $el->LAST_ERROR
                ];
            }

            // Обновляем свойства
            $fieldsToUpdate = [
                'OS_COMPANY_NAME',
                'OS_COMPANY_INN',
                'OS_COMPANY_CITY',
                'OS_COMPANY_PHONE',
                'OS_COMPANY_EMAIL',
                'OS_COMPANY_WEB_SITE'
            ];

            foreach ($fieldsToUpdate as $field) {
                if (isset($data[$field])) {
                    \CIBlockElement::SetPropertyValueCode($companyId, $field, $data[$field]);
                }
            }

            // Обновляем файл реквизитов, если был изменен
            if (isset($data['OS_REQUSITES_FILE'])) {
                \CIBlockElement::SetPropertyValueCode($companyId, 'OS_REQUSITES_FILE', $data['OS_REQUSITES_FILE']);
            }

            // Получаем обновленные данные для ответа
            $rsElement = \CIBlockElement::GetByID($companyId);
            $companyCode = $companyId;
            if ($arElement = $rsElement->Fetch()) {
                $companyCode = $arElement['CODE'] ?? $companyId;
            }

            // Синхронизируем данные с Bitrix24
            $b24SyncSuccess = false;
            if (!empty($company['OS_COMPANY_B24_ID'])) {
                // Если файл не был изменен, но существует - добавляем его в данные для синхронизации
                if (!isset($data['OS_REQUSITES_FILE']) && !empty($company['OS_REQUSITES_FILE'])) {
                    $data['OS_REQUSITES_FILE'] = $company['OS_REQUSITES_FILE'];
                }
                
                $b24Result = $this->sendToBitrix24($company['OS_COMPANY_B24_ID'], $data);
                $b24SyncSuccess = !empty($b24Result);
            } 

            return [
                'success' => true,
                'message' => 'Данные компании успешно обновлены',
                'data' => [
                    'company_id' => $companyId,
                    'company_code' => $companyCode,
                    'b24_synced' => $b24SyncSuccess
                ]
            ];
        }

        /**
         * Обработать загруженный файл реквизитов
         * 
         * @param array $uploadedFile - данные из $_FILES
         * @return array - ['success' => bool, 'message' => string, 'file_id' => int|null]
         */
        private function processUploadedRequisitesFile($uploadedFile) {
            // Проверка размера файла (10 МБ)
            $maxFileSize = 10 * 1024 * 1024;
            if ($uploadedFile['size'] > $maxFileSize) {
                return [
                    'success' => false,
                    'message' => 'Размер файла превышает 10 МБ'
                ];
            }

            // Проверка расширения файла
            $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
            $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                return [
                    'success' => false,
                    'message' => 'Недопустимый формат файла. Разрешены: ' . implode(', ', $allowedExtensions)
                ];
            }

            // Подготавливаем файл для загрузки
            $arFile = $uploadedFile;
            $arFile['MODULE_ID'] = 'iblock';
            
            $fileId = \CFile::SaveFile($arFile, 'company_requisites');
            
            if (!$fileId) {
                return [
                    'success' => false,
                    'message' => 'Ошибка сохранения файла'
                ];
            }

            return [
                'success' => true,
                'file_id' => $fileId
            ];
        }

        /**
         * Проверить права пользователя на редактирование компании
         * 
         * @param int $companyId - ID компании
         * @param int $userId - ID пользователя
         * @return array - ['has_access' => bool, 'message' => string]
         */
        public function checkEditPermission($companyId, $userId) {
            global $USER;

            // Админы могут редактировать любую компанию
            if ($USER->IsAdmin()) {
                return [
                    'has_access' => true
                ];
            }

            // Получаем данные компании
            $company = $this->getCompany($companyId);
            if (!$company) {
                return [
                    'has_access' => false,
                    'message' => 'Компания не найдена'
                ];
            }

            // Проверяем, является ли пользователь руководителем компании
            $bosses = $company['OS_COMPANY_BOSS'] ?? [];
            if (!is_array($bosses)) {
                $bosses = $bosses ? [$bosses] : [];
            }

            if (in_array($userId, $bosses)) {
                return [
                    'has_access' => true
                ];
            }

            return [
                'has_access' => false,
                'message' => 'У вас нет прав для редактирования этой компании'
            ];
        }

        /**
         * Отправить обновленные данные компании в Bitrix24
         *
         * @param int $companyId - ID компании в Bitrix (из CODE элемента)
         * @param array $data - данные компании для отправки:
         *   - OS_COMPANY_NAME (string) - название компании
         *   - OS_COMPANY_INN (string) - ИНН
         *   - OS_COMPANY_CITY (string) - город
         *   - OS_COMPANY_PHONE (string) - телефон
         *   - OS_COMPANY_EMAIL (string) - email
         *   - OS_COMPANY_WEB_SITE (string) - сайт
         *   - OS_REQUSITES_FILE (int) - ID файла реквизитов в Bitrix
         * @param bool $debug - режим отладки
         * 
         * @return array|false - результат отправки или false при ошибке
         */
        private function sendToBitrix24($companyId, $data, $debug = false) {
            if (empty($companyId)) {
                return false;
            }

            // Маппинг полей сайта на поля Bitrix24
            $b24Fields = [];
            
            // Название компании
            if (!empty($data['OS_COMPANY_NAME'])) {
                $b24Fields['TITLE'] = $data['OS_COMPANY_NAME'];
            }
            
            // ИНН (UF_CRM_1669208589 - пример, может отличаться)
            if (!empty($data['OS_COMPANY_INN'])) {
                $b24Fields['UF_CRM_INN'] = $data['OS_COMPANY_INN'];
            }
            
            // Город/Адрес
            if (!empty($data['OS_COMPANY_CITY'])) {
                $b24Fields['UF_CRM_1669208295583'] = $data['OS_COMPANY_CITY']; // Адрес
            }
            
            // Телефон
            if (!empty($data['OS_COMPANY_PHONE'])) {
                $b24Fields['PHONE'] = [
                    [
                        'VALUE' => $data['OS_COMPANY_PHONE'],
                        'VALUE_TYPE' => 'WORK'
                    ]
                ];
            }
            
            // Email
            if (!empty($data['OS_COMPANY_EMAIL'])) {
                $b24Fields['EMAIL'] = [
                    [
                        'VALUE' => $data['OS_COMPANY_EMAIL'],
                        'VALUE_TYPE' => 'WORK'
                    ]
                ];
            }
            
            // Сайт
            if (!empty($data['OS_COMPANY_WEB_SITE'])) {
                $b24Fields['WEB'] = [
                    [
                        'VALUE' => $data['OS_COMPANY_WEB_SITE'],
                        'VALUE_TYPE' => 'WORK'
                    ]
                ];
            }

            // Файл реквизитов (как в RegisterUserCompany.php)
            if (!empty($data['OS_REQUSITES_FILE'])) {
                $fileId = $data['OS_REQUSITES_FILE'];
                
                // Получаем информацию о файле из Bitrix
                $fileInfo = \CFile::GetFileArray($fileId);
                
                if ($fileInfo && !empty($fileInfo['SRC'])) {
                    $filePath = $_SERVER['DOCUMENT_ROOT'] . $fileInfo['SRC'];
                    
                    // Проверяем существование файла
                    if (file_exists($filePath)) {
                        // Читаем содержимое файла
                        $fileContent = file_get_contents($filePath);
                        
                        if ($fileContent !== false) {
                            // Кодируем в base64 и передаем в B24 (как в RegisterUserCompany.php)
                            $b24Fields['UF_CRM_1755643990423'] = [
                                'fileData' => [
                                    $fileInfo['ORIGINAL_NAME'],
                                    base64_encode($fileContent)
                                ]
                            ];
                        }
                    }
                }
            }

            // Отправляем запрос в Bitrix24
            try {
                $result = sendRequestB24('crm.company.update', [
                    'id'     => $companyId,
                    'fields' => $b24Fields,
                ], $debug);

                return $result;
            } catch (\Exception $e) {
                // Логируем ошибку, но не прерываем процесс
                error_log('Bitrix24 company update error: ' . $e->getMessage());
                return false;
            }
        }
    }