<?php
namespace OnlineService\B24;
use OnlineService\B24\UserSync\Config\RegisterUserCompanyConfig;
use OnlineService\B24\UserSync\Config\UserSyncConfig;
use OnlineService\Site\Config\CompanyModuleConfig;
use OnlineService\B24\User;
use OnlineService\B24\Request;
class RegisterUserCompany extends Request{
    public function __construct()
    {
    }

    public function isUserRegistered($arFields,$debug = false){
        // найти пользователя в б24 по EMAIL
        $b24User = new \OnlineService\B24\User();

        $userObject = $b24User->isUserRegistered($arFields,$debug);

        // если такой пользователь есть, то вывести предупреждение
        if ($userObject && !empty($userObject)) {
            return $userObject;
        }

        return false;
    }

    private function createCompanyElement($params){
        $company = new \OnlineService\Site\Company();
        $company->createCompanyElement($params);
    }

    private function callB24Method($method, array $params, $debug = false)
    {
        return \OnlineService\B24\RestClient::callRestMethod($method, $params, (bool) $debug);
    }

    private function getConfiguredFieldValue(array $arFields, $fieldName)
    {
        return $arFields[$fieldName] ?? null;
    }

    private function isCompanyRegistrationType(array $arFields): bool
    {
        $userType = (string)($arFields['UF_TYPE'] ?? '');

        return $userType === '5' || $userType === '6';
    }

    private function normalizeInn($inn): string
    {
        return preg_replace('/\D+/', '', (string)$inn);
    }

    private function resolveCrmManagerIdFromUserField($managerElementId): ?int
    {
        $managerElementId = (int)$managerElementId;
        if ($managerElementId <= 0) {
            return null;
        }

        $rsElement = \CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => 53,
                'ID' => $managerElementId,
            ],
            false,
            false,
            ['ID', 'XML_ID']
        );

        $managerElement = $rsElement->GetNext();
        if (!$managerElement) {
            return null;
        }

        $xmlId = trim((string)($managerElement['XML_ID'] ?? ''));
        if ($xmlId === '' || !ctype_digit($xmlId)) {
            return null;
        }

        return (int)$xmlId;
    }

    /**
     * Outbound core policy for manager mapping.
     *
     * @return array{ASSIGNED_BY_ID:int,UF_CRM_1757682312?:int}
     */
    private function buildOutboundManagerFields(array $arFields): array
    {
        $managerFields = [
            UserSyncConfig::CRM_PRIMARY_MANAGER_FIELD => RegisterUserCompanyConfig::ASSIGNED_BY_ID,
        ];

        if (array_key_exists(UserSyncConfig::USER_PRIMARY_MANAGER_FIELD, $arFields)) {
            $primaryManagerCrmId = $this->resolveCrmManagerIdFromUserField($arFields[UserSyncConfig::USER_PRIMARY_MANAGER_FIELD]);
            if ($primaryManagerCrmId !== null) {
                $managerFields[UserSyncConfig::CRM_PRIMARY_MANAGER_FIELD] = $primaryManagerCrmId;
            }
        }

        if (array_key_exists(UserSyncConfig::USER_SECONDARY_MANAGER_FIELD, $arFields)) {
            $secondaryManagerCrmId = $this->resolveCrmManagerIdFromUserField($arFields[UserSyncConfig::USER_SECONDARY_MANAGER_FIELD]);
            if ($secondaryManagerCrmId !== null) {
                $managerFields[UserSyncConfig::CRM_SECONDARY_MANAGER_FIELD] = $secondaryManagerCrmId;
            }
        }

        return $managerFields;
    }

    private function hasDuplicateInnInLocal(string $inn): bool
    {
        $rsCompany = \CIBlockElement::GetList(
            [],
            [
                'IBLOCK_ID' => CompanyModuleConfig::COMPANY_IBLOCK_ID,
                'ACTIVE' => 'Y',
                'PROPERTY_OS_COMPANY_INN' => $inn,
            ],
            false,
            ['nTopCount' => 1],
            ['ID']
        );

        return (bool)$rsCompany->Fetch();
    }

    private function hasDuplicateInnInB24(string $inn, ?bool &$transportAvailable = null): bool
    {
        $dataRequisite = [
            'fields' => [],
            'params' => [],
            'select' => ['ID'],
            'filter' => ['RQ_INN' => $inn],
        ];

        $matches = $this->callB24Method('crm.requisite.list', $dataRequisite, false);

        if (!is_array($matches)) {
            $transportAvailable = false;

            return false;
        }

        if (array_key_exists('success', $matches) && (int)$matches['success'] === 0) {
            $transportAvailable = false;

            return false;
        }

        if (array_key_exists('error', $matches) && !array_key_exists(0, $matches)) {
            $transportAvailable = false;

            return false;
        }

        $transportAvailable = true;

        if (!array_key_exists(0, $matches)) {
            return false;
        }

        return !empty($matches);
    }

    private function ensureInnDuplicatePrecheck(array &$arFields): bool
    {
        global $APPLICATION;

        if (!$this->isCompanyRegistrationType($arFields)) {
            return true;
        }

        $inn = $this->normalizeInn($arFields['UF_INN'] ?? '');
        $arFields['UF_INN'] = $inn;
        if ($inn === '') {
            $APPLICATION->ThrowException(
                'Для юридического лица или рекламного агента поле "ИНН организации" обязательно для заполнения.',
                'required_inn'
            );

            return false;
        }

        if ($this->hasDuplicateInnInLocal($inn)) {
            $APPLICATION->ThrowException(
                'Компания с указанным ИНН уже зарегистрирована. Проверьте ИНН или войдите в существующий аккаунт.',
                'duplicate_inn'
            );

            return false;
        }

        $transportAvailable = null;
        if (!$this->hasDuplicateInnInB24($inn, $transportAvailable)) {
            if ($transportAvailable === false) {
                $APPLICATION->ThrowException(
                    'Не удалось проверить уникальность ИНН. Попробуйте повторить регистрацию позже.',
                    'inn_check_unavailable'
                );

                return false;
            }

            return true;
        }

        $APPLICATION->ThrowException(
            'Компания с указанным ИНН уже зарегистрирована. Проверьте ИНН или войдите в существующий аккаунт.',
            'duplicate_inn'
        );

        return false;
    }

    private function createB24Company($arFields){
        global $APPLICATION;
        $outboundManagerFields = $this->buildOutboundManagerFields($arFields);

        $companyId = false;
        $reqFile = [];
        $file = [];
        if( !empty($arFields['UF_REQ']) && !empty($arFields['UF_REQ']['name']) ){
            $file = $arFields['UF_REQ'];

            // Сохраняем в систему Битрикс
            $savedFileId = \CFile::SaveFile($file, 'os_requisites');
            $fileInfo = \CFile::GetFileArray($savedFileId);

            if ($file['error'] === 0) {
                $fileName = $file['name'];
                $filePath = $file['tmp_name'];

                // Читаем содержимое файла
                $fileContent = file_get_contents($filePath);

                if ($fileContent !== false) {
                    // Кодируем в base64
                    $fileData = [
                        $fileName,
                        base64_encode($fileContent),
                    ];

                    // Передаём в поле Bitrix24
                    $arFields[RegisterUserCompanyConfig::getRequisitesFileField()] = [
                        'fileData' => $fileData
                    ];
                }
            }
			else{
                // Вывести подробную ошибку
                $errorMessage = 'Ошибка загрузки файла реквизитов: ';
                switch ($file['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                        $errorMessage .= 'Размер файла превышает максимально допустимый размер, указанный в php.ini.';
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMessage .= 'Размер файла превышает максимально допустимый размер, указанный в форме.';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errorMessage .= 'Файл был загружен только частично.';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errorMessage .= 'Файл не был загружен.';
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errorMessage .= 'Отсутствует временная папка для загрузки файла.';
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errorMessage .= 'Не удалось записать файл на диск.';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $errorMessage .= 'Загрузка файла была остановлена расширением PHP.';
                        break;
                    default:
                        $errorMessage .= 'Неизвестная ошибка (код: ' . $file['error'] . ').';
                        break;
                }
                $APPLICATION->ThrowException($errorMessage);
                return false;
            }
        }

        // данные для контакта
        $dataContact = [
            'fields' => [
                'NAME' => $arFields['NAME'],
                'SECOND_NAME' => $arFields['SECOND_NAME'],
                'LAST_NAME' => $arFields['LAST_NAME'],
                'POST' => $arFields['WORK_POSITION'],
                'BIRTHDATE' => $arFields['PERSONAL_BIRTHDAY'],
                'OPENED' => 'Y',
                UserSyncConfig::CRM_PRIMARY_MANAGER_FIELD => $outboundManagerFields[UserSyncConfig::CRM_PRIMARY_MANAGER_FIELD],
                RegisterUserCompanyConfig::CRM_CONTACT_CITY_FIELD => $arFields['UF_CITY'],
                'PHONE' => [[
                    "VALUE" => $arFields['PERSONAL_PHONE'],
                    "VALUE_TYPE" => "WORK"
                ]],
                'EMAIL' => [ [
                    "VALUE" => $arFields['EMAIL'],
                    "VALUE_TYPE" => "WORK"
                ]]
            ],
            'params' => []
        ];
        if (isset($outboundManagerFields[UserSyncConfig::CRM_SECONDARY_MANAGER_FIELD])) {
            $dataContact['fields'][UserSyncConfig::CRM_SECONDARY_MANAGER_FIELD] = $outboundManagerFields[UserSyncConfig::CRM_SECONDARY_MANAGER_FIELD];
        }

        // если это компания или рекламынй агент
        if ($arFields['UF_TYPE'] == '5' || $arFields['UF_TYPE'] == '6') {
            // проверить заполненность ИНН и названия компании
            if (empty($arFields['UF_INN']) && empty($arFields['UF_NAME_COMPANY'])) {
                $APPLICATION->ThrowException('Вы регистрируйтесь как рекламный агент или юридическое лицо. Поля "Название компании", "ИНН организации" обязательно для заполнения!');
                return false;
            } else {
                // если это рекламный агент
                if ($arFields['UF_ADVERSTERING_AGENT'] == 'on') {
                    $dataContact['fields'][RegisterUserCompanyConfig::CRM_CONTACT_NOTE_FIELD] = RegisterUserCompanyConfig::REGISTRATION_NOTE_AD_AGENT;
                }
                $dataRequisite = [
                    'fields' => [],
                    'params' => [],
                    'select' => [
                        'ID',
                        'RQ_INN',
                        'ENTITY_ID'
                    ],
                    'filter' => [
                        'RQ_INN' => $arFields['UF_INN']
                    ]
                ];
                // найти реквизит по ИНН
                $dataRequisite = $this->callB24Method("crm.requisite.list", $dataRequisite, false);

                if (!empty($dataRequisite)) {
                    $dataContact['fields']['COMPANY_ID'] = $dataRequisite[0]['ENTITY_ID'];
                    $companyId = $dataRequisite[0]['ENTITY_ID'];

                    $companyElementParamss = [
                        'OS_COMPANY_INN' => $arFields['UF_INN'],
                        'OS_COMPANY_WEB_SITE' => $arFields['UF_SITE'],
                        'OS_COMPANY_NAME' => $arFields['UF_NAME_COMPANY'],
                        'OS_COMPANY_EMAIL' => $arFields['EMAIL'],
                        'OS_COMPANY_PHONE' => $arFields['PERSONAL_PHONE'],
                        'OS_COMPANY_B24_ID' => $companyId,
                        'OS_COMPANY_CITY' => $arFields['UF_CITY'],
                        'OS_REQUSITES_FILE' => $this->getConfiguredFieldValue($arFields, RegisterUserCompanyConfig::getRequisitesFileField())
                    ];
                    if( isset($arFields['USER_ID']) ){
                        $companyElementParamss['USER_ID'] = $arFields['USER_ID'];
                        $dataContact['fields'][RegisterUserCompanyConfig::CRM_CONTACT_SITE_USER_ID_FIELD] = $arFields['USER_ID'];
                    }

                    $this->createCompanyElement($companyElementParamss);
                } else {
                    /*Создание компании*/
                    $qrCompanyInfo = [
                        'fields' => [
                            'TITLE' => $arFields['UF_NAME_COMPANY'],
                            'PHONE' => [[
                                'VALUE' => $arFields['PERSONAL_PHONE'],
                                'VALUE_TYPE' => "WORK"
                            ]],
                            'EMAIL' => [[
                                'VALUE' => $arFields['EMAIL'],
                                'VALUE_TYPE' => "WORK"
                            ]],
                            'WEB' => [[
                                'VALUE' => $arFields['UF_SITE'],
                                "VALUE_TYPE" => "WORK"
                            ]],
                            RegisterUserCompanyConfig::CRM_COMPANY_SPHERE_FIELD => $arFields['UF_SPERE'],
                            RegisterUserCompanyConfig::CRM_COMPANY_JUR_ADDRESS_FIELD => $arFields['UF_JUR_ADDRESS'],
                            RegisterUserCompanyConfig::CRM_COMPANY_CITY_FIELD => $arFields['UF_CITY'],
                            RegisterUserCompanyConfig::CRM_REQUISITES_FILE_FIELD => $this->getConfiguredFieldValue($arFields, RegisterUserCompanyConfig::getRequisitesFileField()),
                            'COMPANY_TYPE' => 'CUSTOMER',
                            UserSyncConfig::CRM_PRIMARY_MANAGER_FIELD => $outboundManagerFields[UserSyncConfig::CRM_PRIMARY_MANAGER_FIELD],
                        ]
                    ];

                    $companyId = $this->callB24Method("crm.company.add", $qrCompanyInfo);
					
                    if (!empty($companyId)) {
                        $qrCompany['id'] = $companyId;
                        $dataCompany = $this->callB24Method("crm.company.get", $qrCompany);

                        /*Добавление реквизита к компании*/
                        $qrRequisite = [
                            'fields' => [
                                'ENTITY_ID' => $dataCompany['ID'],
                                'ENTITY_TYPE_ID' => '4',
                                'NAME' => 'Реквизит с формы сайта',
                                'PRESET_ID' => 1
                            ]
                        ];
                        $requisiteId = $this->callB24Method("crm.requisite.add", $qrRequisite);

                        /*Обновление реквизитов у компании*/
                        $qrRequisites = array(
                            'id' => $requisiteId,
                            'fields' => [
                                'ENTITY_ID' => $dataCompany['ENTITY_ID'],
                                'ENTITY_TYPE_ID' => '4',
                                'RQ_INN' => $arFields['UF_INN'],
                                'RQ_KPP' => $arFields['UF_KPP'],
                                'RQ_COMPANY_FULL_NAME' => $arFields['UF_NAME_COMPANY']
                            ]
                        );
                        $this->callB24Method("crm.requisite.update", $qrRequisites);

                        $companyElementParamss = [
                            'OS_COMPANY_INN' => $arFields['UF_INN'],
                            'OS_COMPANY_WEB_SITE' => $arFields['UF_SITE'],
                            'OS_COMPANY_NAME' => $arFields['UF_NAME_COMPANY'],
                            'OS_COMPANY_EMAIL' => $arFields['EMAIL'],
                            'OS_COMPANY_PHONE' => $arFields['PERSONAL_PHONE'],
                            'OS_COMPANY_B24_ID' => $dataCompany['ID'],
                            'OS_COMPANY_CITY' => $arFields['UF_CITY'],
                            'OS_REQUSITES_FILE' => $this->getConfiguredFieldValue($arFields, RegisterUserCompanyConfig::getRequisitesFileField())
                        ];
                        if( isset($arFields['USER_ID']) ){
                            $companyElementParamss['USER_ID'] = $arFields['USER_ID'];
                            $dataContact['fields'][RegisterUserCompanyConfig::CRM_CONTACT_SITE_USER_ID_FIELD] = $arFields['USER_ID'];
                        }
                        $dataContact['fields']['COMPANY_ID'] = $dataCompany['ID'];

                        $this->createCompanyElement($companyElementParamss);


                        /*\OnlineService\Site\Company::updateB24Company([
                            'ID' => $companyId,
                            'UF_CRM_1755643990423' => $reqFile
                        ]);*/
                    }
                }
            }
        }

        $contactId = $this->callB24Method("crm.contact.add", $dataContact);

        if (!empty($companyId) && !empty($contactId)) {
            // добавить контакт в компанию
            $qrCompanyAddContact = [
                'fields' => ['COMPANY_ID' => $companyId],
                'id' => $contactId
            ];
            $this->callB24Method("crm.contact.company.add", $qrCompanyAddContact);
        }

        return true;
    } 


    public function OnBeforeUserRegisterHandler(&$arFields) {
        global $APPLICATION;

        $arFields['ACTIVE'] = 'N';
        if (!$this->ensureInnDuplicatePrecheck($arFields)) {
            return false;
        }

        $response = $this->isUserRegistered($arFields);

        if( !$response ){
            if ($arFields['PASSWORD'] == $arFields['CONFIRM_PASSWORD']) {

                //$createResult = $this->createB24Company($arFields);
                /*if ($createResult === false) {
                    // Если createB24Company вернул false, значит была ошибка
                    // Исключение уже было выброшено в createB24Company
                    return false;
                }*/
                $arFields['UF_ADVERSTERING_AGENT'] = "";
                return $arFields;
            }
            $APPLICATION->ThrowException('Указанные пароли не совпадают.');
            return false;
        }
        else{
            // Определяем какое поле использовать для сообщения об ошибке
            if (isset($response['PHONE']) && !empty($response['PHONE']) || isset($response['EMAIL']) && !empty($response['EMAIL'])) {
                $APPLICATION->ThrowException('Пользователь с указанными почтой или телефоном уже существует в системе. Вы можете <a href="/personal/profile/">авторизоваться</a> или <a href="/personal/profile/?forgot_password=yes">восстановить пароль</a>','already_registered');
            } else {
                $APPLICATION->ThrowException('Что-то пошло не так.','already_registered');
            }

            return false;
        }
    }

    public function OnAfterUserRegisterHandler(&$arFields) {
        // если регистрация успешна то
        if($arFields["USER_ID"]>0)
        {
            $response = $this->isUserRegistered($arFields,false);

            if( !$response ){
                $createResult = $this->createB24Company($arFields);

                $response = $this->isUserRegistered($arFields,false);
            }

            if( $response ){
                $contactId = $response['ID'];

                // Обновляем пользователя, записываем $contactId в UF_B24_USER_ID
                $user = new \CUser;
                $user->Update($arFields["USER_ID"], ["ACTIVE" => "N","UF_B24_USER_ID" => $contactId]);

                /*$event = new \CEvent;
                $event->SendImmediate("NEW_USER", SITE_ID, $arFields);*/

                unset($arFields["PASSWORD"]);
                unset($arFields["CONFIRM_PASSWORD"]);

                \Bitrix\Main\Mail\Event::send([
                    'EVENT_NAME' => 'NEW_USER_CONFIRM',
                    'LID' => 's1', // ID вашего сайта
                    'C_FIELDS' => $arFields,
                ]);
            }
        }
    }
    
}