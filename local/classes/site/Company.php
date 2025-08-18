<?php
    namespace OnlineService\Site;

    use OnlineService\B24\User;

    class Company{
        private int $iblock_id = 57;
        private static $codeProps = [
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
        public function createCompanyElement($params){
            /*$companyElementParamss = [
                'OS_COMPANY_INN' => $arFields['UF_INN'],
                'OS_COMPANY_WEB_SITE' => $arFields['UF_SITE'],
                'OS_COMPANY_NAME' => $arFields['UF_NAME_COMPANY'],
                'OS_COMPANY_EMAIL' => $arFields['EMAIL'],
                'OS_COMPANY_PHONE' => $arFields['PERSONAL_PHONE'],
                'OS_COMPANY_B24_ID' => $dataCompany['ID'],
                'OS_COMPANY_CITY' => $arFields['UF_CITY']
            ];*/

            $el = new \CIBlockElement;

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
            } else {
                echo "Error: ".$el->LAST_ERROR;
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
                $el = new \CIBlockElement;
                $companyId = $company['ID'];

                $params['OS_COMPANY_STATUS'] =  (new UserGroups([]))->searchGroup($params['OS_COMPANY_STATUS'])['ID'];

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

                if( $params['OS_REQUSITES_FILE'] && !empty($params['OS_REQUSITES_FILE']) ){
                    $downloadableUrl = URL_B24.$params['OS_REQUSITES_FILE']['SUBDIR'].'/'.urlencode($params['OS_REQUSITES_FILE']['FILE_NAME']);

                    // Куда сохранить (на твоём сервере)
                    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/os_requisites/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    // Оригинальное имя файла (если нужно)
                    $originalName = $params['OS_REQUSITES_FILE']['ORIGINAL_NAME'];

                    $filePath = $uploadDir . $originalName;

                    // Скачиваем файл
                    $fileContent = file_get_contents($downloadableUrl);

                    if ($fileContent === false) {
                        pre($downloadableUrl);
                        die("Не удалось скачать файл. Проверь URL и доступ к B24.");
                    }

                    // Сохраняем на сервер
                    $content = file_put_contents($filePath, $fileContent);

                    if ($content && file_put_contents($filePath, $content)) {
                        // Путь к сохраненному файлу
                        $filePath = $filePath;
                        $fileName = $originalName;

                        // Загружаем файл в Битрикс
                        $fileArray = \CFile::MakeFileArray($filePath, false, $fileName);

                        if ($fileArray && !isset($fileArray['error'])) {

                            // Сохраняем в систему Битрикс
                            $savedFileId = \CFile::SaveFile($fileArray, 'os_requisites');

                            if ($savedFileId) {
                                // Формируем значение для свойства
                                $params['OS_REQUSITES_FILE'] = $savedFileId;
                            }

                            // Удаляем временный файл
                            unlink($filePath);
                        } else {
                            // Обработка ошибки
                            echo 'Ошибка загрузки файла: ' . ($fileArray['error'] ?? 'неизвестная ошибка');
                        }
                    }
                }


                // Формируем массив свойств для обновления
                $arProps = [];
                foreach (self::$codeProps as $code) {
                    if (isset($params[$code])) {
                        $arProps[$code] = $params[$code];
                    }
                }

                $arUpdateArray = [
                    "PROPERTY_VALUES" => $arProps,
                    "NAME" => $params["OS_COMPANY_NAME"],
                    "ACTIVE" => $params['ACTIVE']
                ];


                if ($el->Update($companyId, $arUpdateArray)) {
                    return $companyId;
                } else {
                    echo "Ошибка при обновлении компании: " . $el->LAST_ERROR;
                    return false;
                }
            } else {
                echo "Компания с B24_ID {$b24_id} не найдена";
                return false;
            }
        }

        public function deleteCompanyElement($params){
            $b24_id = $params['ID'];
            $company = $this->getCompanyByB24ID($b24_id);
            if ($company && !empty($company['ID'])) {
                if (\CIBlockElement::Delete($company['ID'])) {
                    return true;
                } else {
                    echo "Ошибка при удалении компании с ID: " . $company['ID'];
                    return false;
                }
            } else {
                echo "Компания с B24_ID {$b24_id} не найдена";
                return false;
            }
        }

        public function getCompany($id){
            $rsCompany = \CIBlockElement::GetById($id);
            $ob = $rsCompany->GetNextElement();
            $arProps = $ob->GetProperties();
            $arFields = $ob->GetFields();
            $arCompany["ID"] = $arFields["ID"];
            foreach (self::$codeProps as $code) {
                $arCompany[$code] = $arProps[$code]["VALUE"];
            }
            return $arCompany;
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
                ['PROPERTY_OS_COMPANY_B24_ID' => $b24_id],
                false,
                false,
                ['ID', 'NAME', 'PROPERTY_OS_COMPANY_B24_ID']
            );  
            $ob = $rsCompany->GetNextElement();
            $arProps = $ob->GetProperties();
            $arFields = $ob->GetFields();
            $arCompany["ID"] = $arFields["ID"];
            foreach (self::$codeProps as $code) {
                $arCompany[$code] = $arProps[$code]["VALUE"];
            }
            return $arCompany;
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
    }