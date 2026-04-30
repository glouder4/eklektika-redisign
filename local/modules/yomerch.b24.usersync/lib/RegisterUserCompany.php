<?php
namespace OnlineService\B24;
use OnlineService\B24\UserSync\Config\RegisterUserCompanyConfig;
use OnlineService\B24\UserSync\Config\UserSyncConfig;
use OnlineService\Site\Config\CompanyB24Config;
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

    /**
     * @return int|false ID элемента инфоблока компании на сайте
     */
    private function createCompanyElement($params)
    {
        $company = new \OnlineService\Site\Company();

        return $company->createCompanyElement($params);
    }

    /**
     * После создания элемента каталога на сайте записывает его ID в CRM (legacy UF связи с элементом).
     */
    private function pushSiteElementIdToCrmCompany($crmCompanyId, int $siteElementId): void
    {
        $crmCompanyId = (int)$crmCompanyId;
        if ($crmCompanyId <= 0 || $siteElementId <= 0) {
            return;
        }

        $this->callB24Method('crm.company.update', [
            'id' => $crmCompanyId,
            'fields' => [
                RegisterUserCompanyConfig::CRM_COMPANY_EXTRA_FIELD => (string)$siteElementId,
            ],
        ]);
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

        // Дубликат ИНН в каталоге сайта: регистрацию не блокируем — {@see createB24Company()}
        // допишет пользователя в OS_COMPANY_USERS существующего элемента и привяжет контакт в CRM.

        $transportAvailable = null;
        $dupInB24 = $this->hasDuplicateInnInB24($inn, $transportAvailable);

        if (!$dupInB24 && $transportAvailable === false) {
            $APPLICATION->ThrowException(
                'Не удалось проверить уникальность ИНН. Попробуйте повторить регистрацию позже.',
                'inn_check_unavailable'
            );

            return false;
        }

        if (!$dupInB24) {
            return true;
        }

        // В CRM уже есть реквизит с этим ИНН, на сайте — ещё нет элемента компании с тем же ИНН:
        // регистрацию не блокируем; {@see createB24Company()} находит requisite по ИНН,
        // ставит COMPANY_ID существующей компании, создаёт только контакт и при необходимости
        // дописывает пользователя в элемент каталога через {@see Company::createCompanyElement()}.
        return true;
    }

    /**
     * Пишет в PHP error_log (префикс `[RegisterUserCompany.post_registration]`): диагностика регистрации и синка с CRM.
     * Смотреть на сервере файл из `php.ini` (`error_log`) или лог веб-сервера (Apache/Nginx/PhpStorm).
     */
    private static function logPostRegistrationSyncIssue(string $message): void
    {
        error_log('[RegisterUserCompany.post_registration] ' . $message);
    }

    /**
     * Создание контакта в CRM и при необходимости компании/элемента каталога.
     *
     * @return int|false ID нового контакта ({@see \OnlineService\B24\RestClient::callRestMethod()} → crm.contact.add result) или false при ошибке до/вместо создания контакта
     */
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
                self::logPostRegistrationSyncIssue($errorMessage);

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
                self::logPostRegistrationSyncIssue(
                    'Компания/агент: отсутствуют UF_INN и UF_NAME_COMPANY после регистрации пользователя USER_ID='
                    . (string)($arFields['USER_ID'] ?? $arFields['ID'] ?? '')
                );

                return false;
            } else {
                // если это рекламный агент
                if ($arFields['UF_ADVERSTERING_AGENT'] == 'on') {
                    $dataContact['fields'][RegisterUserCompanyConfig::CRM_CONTACT_NOTE_FIELD] = RegisterUserCompanyConfig::REGISTRATION_NOTE_AD_AGENT;
                }

                $siteCompanyForInn = new \OnlineService\Site\Company();
                $existingSiteByInn = $siteCompanyForInn->getActiveCompanyByInn($arFields['UF_INN']);
                $mergedIntoExistingElementByInn = false;
                if (
                    $existingSiteByInn && !empty($existingSiteByInn['OS_COMPANY_B24_ID'])
                    && isset($arFields['USER_ID']) && (int)$arFields['USER_ID'] > 0
                ) {
                    $crmCompanyId = $existingSiteByInn['OS_COMPANY_B24_ID'];
                    $dataContact['fields']['COMPANY_ID'] = $crmCompanyId;
                    $dataContact['fields'][RegisterUserCompanyConfig::CRM_CONTACT_SITE_USER_ID_FIELD] = $arFields['USER_ID'];
                    $this->createCompanyElement([
                        'OS_COMPANY_B24_ID' => $crmCompanyId,
                        'USER_ID' => $arFields['USER_ID'],
                    ]);
                    $companyId = $crmCompanyId;
                    $mergedIntoExistingElementByInn = true;
                }

                if (!$mergedIntoExistingElementByInn) {
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
                    $headCrmId = (int)$dataRequisite[0]['ENTITY_ID'];
                    $siteCompanySvc = new \OnlineService\Site\Company();
                    $headSiteCompany = $siteCompanySvc->getCompanyByB24ID($headCrmId);

                    if ($headSiteCompany && !empty($headSiteCompany['ID'])) {
                        $branchCompanyFields = [
                            'TITLE' => $arFields['UF_NAME_COMPANY'],
                            'PHONE' => [[
                                'VALUE' => $arFields['PERSONAL_PHONE'],
                                'VALUE_TYPE' => 'WORK',
                            ]],
                            'EMAIL' => [[
                                'VALUE' => $arFields['EMAIL'],
                                'VALUE_TYPE' => 'WORK',
                            ]],
                            'WEB' => [[
                                'VALUE' => $arFields['UF_SITE'],
                                'VALUE_TYPE' => 'WORK',
                            ]],
                            CompanyB24Config::BRANCH_CITY_FIELD => $arFields['UF_CITY'],
                            CompanyB24Config::HEAD_COMPANY_B24_LINK_FIELD => (string)$headCrmId,
                            RegisterUserCompanyConfig::CRM_COMPANY_SPHERE_FIELD => $arFields['UF_SPERE'],
                            RegisterUserCompanyConfig::CRM_COMPANY_JUR_ADDRESS_FIELD => $arFields['UF_JUR_ADDRESS'],
                            RegisterUserCompanyConfig::CRM_COMPANY_CITY_FIELD => $arFields['UF_CITY'],
                            RegisterUserCompanyConfig::CRM_REQUISITES_FILE_FIELD => $this->getConfiguredFieldValue($arFields, RegisterUserCompanyConfig::getRequisitesFileField()),
                            'COMPANY_TYPE' => 'CUSTOMER',
                            UserSyncConfig::CRM_PRIMARY_MANAGER_FIELD => $outboundManagerFields[UserSyncConfig::CRM_PRIMARY_MANAGER_FIELD],
                        ];
                        $childCrmId = $this->callB24Method('crm.company.add', ['fields' => $branchCompanyFields]);

                        if (!empty($childCrmId)) {
                            $qrCompany = ['id' => $childCrmId];
                            $dataCompany = $this->callB24Method('crm.company.get', $qrCompany);

                            $qrRequisite = [
                                'fields' => [
                                    'ENTITY_ID' => $dataCompany['ID'],
                                    'ENTITY_TYPE_ID' => '4',
                                    'NAME' => 'Реквизит с формы сайта',
                                    'PRESET_ID' => 1,
                                ],
                            ];
                            $requisiteId = $this->callB24Method('crm.requisite.add', $qrRequisite);

                            $qrRequisites = [
                                'id' => $requisiteId,
                                'fields' => [
                                    'ENTITY_ID' => $dataCompany['ENTITY_ID'],
                                    'ENTITY_TYPE_ID' => '4',
                                    'RQ_INN' => $arFields['UF_INN'],
                                    'RQ_KPP' => $arFields['UF_KPP'],
                                    'RQ_COMPANY_FULL_NAME' => $arFields['UF_NAME_COMPANY'],
                                ],
                            ];
                            $this->callB24Method('crm.requisite.update', $qrRequisites);

                            $companyId = $dataCompany['ID'];
                            $dataContact['fields']['COMPANY_ID'] = $companyId;

                            $companyElementParamss = [
                                'OS_COMPANY_INN' => $arFields['UF_INN'],
                                'OS_COMPANY_WEB_SITE' => $arFields['UF_SITE'],
                                'OS_COMPANY_NAME' => $arFields['UF_NAME_COMPANY'],
                                'OS_COMPANY_EMAIL' => $arFields['EMAIL'],
                                'OS_COMPANY_PHONE' => $arFields['PERSONAL_PHONE'],
                                'OS_COMPANY_B24_ID' => $dataCompany['ID'],
                                'OS_COMPANY_CITY' => $arFields['UF_CITY'],
                                'OS_REQUSITES_FILE' => $this->getConfiguredFieldValue($arFields, RegisterUserCompanyConfig::getRequisitesFileField()),
                                'OS_HOLDING_OF' => (int)$headSiteCompany['ID'],
                                'OS_HEAD_COMPANY_B24_ID' => $headCrmId,
                            ];
                            if (isset($arFields['USER_ID'])) {
                                $companyElementParamss['USER_ID'] = $arFields['USER_ID'];
                                $dataContact['fields'][RegisterUserCompanyConfig::CRM_CONTACT_SITE_USER_ID_FIELD] = $arFields['USER_ID'];
                            }

                            $siteElementId = (int)$this->createCompanyElement($companyElementParamss);
                            if ($siteElementId > 0) {
                                $this->pushSiteElementIdToCrmCompany((int)$companyId, $siteElementId);
                            }
                        } else {
                            self::logPostRegistrationSyncIssue(
                                'ИНН уже в CRM, филиал: crm.company.add не вернул ID. USER_ID='
                                . (string)($arFields['USER_ID'] ?? $arFields['ID'] ?? '')
                            );
                        }
                    } else {
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
                            'OS_REQUSITES_FILE' => $this->getConfiguredFieldValue($arFields, RegisterUserCompanyConfig::getRequisitesFileField()),
                        ];
                        if (isset($arFields['USER_ID'])) {
                            $companyElementParamss['USER_ID'] = $arFields['USER_ID'];
                            $dataContact['fields'][RegisterUserCompanyConfig::CRM_CONTACT_SITE_USER_ID_FIELD] = $arFields['USER_ID'];
                        }

                        $siteElementId = (int)$this->createCompanyElement($companyElementParamss);
                        if ($siteElementId > 0) {
                            $this->pushSiteElementIdToCrmCompany((int)$companyId, $siteElementId);
                        }
                    }
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
                            RegisterUserCompanyConfig::CRM_COMPANY_EXTRA_FIELD => $this->getConfiguredFieldValue($arFields, RegisterUserCompanyConfig::CRM_COMPANY_EXTRA_FIELD),
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

                        $siteElementId = (int)$this->createCompanyElement($companyElementParamss);
                        if ($siteElementId > 0) {
                            $this->pushSiteElementIdToCrmCompany((int)$dataCompany['ID'], $siteElementId);
                        }


                        /*\OnlineService\Site\Company::updateB24Company([
                            'ID' => $companyId,
                            'UF_CRM_1755643990423' => $reqFile
                        ]);*/
                    }
                }
                }
            }
        }

        // Новый контакт в CRM (не обновление существующего); дубль личности — {@see OnBeforeUserRegisterHandler()} (CRM) и ядро при CUser::Add (LOGIN/EMAIL).
        $contactId = $this->callB24Method("crm.contact.add", $dataContact);

        $crmContactNumericId = null;
        if (is_int($contactId) || (is_string($contactId) && $contactId !== '' && ctype_digit($contactId))) {
            $crmContactNumericId = (int)$contactId;
        }
        if ($crmContactNumericId === null || $crmContactNumericId <= 0) {
            self::logPostRegistrationSyncIssue(
                'crm.contact.add не вернул числовой ID контакта; ответ=' . json_encode($contactId, JSON_UNESCAPED_UNICODE)
            );

            return false;
        }

        if (!empty($companyId) && $crmContactNumericId > 0) {
            // добавить контакт в компанию
            $qrCompanyAddContact = [
                'fields' => ['COMPANY_ID' => $companyId],
                'id' => $crmContactNumericId
            ];
            $this->callB24Method("crm.contact.company.add", $qrCompanyAddContact);
        }

        return $crmContactNumericId;
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
            $hasPhone = isset($response['PHONE']) && !empty($response['PHONE']);
            $hasEmail = isset($response['EMAIL']) && !empty($response['EMAIL']);
            if ($hasPhone || $hasEmail) {
                $APPLICATION->ThrowException('Пользователь с указанными почтой или телефоном уже существует в системе. Вы можете <a href="/personal/profile/">авторизоваться</a> или <a href="/personal/profile/?forgot_password=yes">восстановить пароль</a>','already_registered');
            } else {
                // Дубликат в CRM, но ответ getContactID без EMAIL/PHONE — см. маркер *CRM в тексте и лог PHP
                self::logPostRegistrationSyncIssue(
                    'OnBeforeUserRegister: CRM сообщила о дубликате, но в ответе нет EMAIL/PHONE для текста ошибки. Ответ: '
                    . json_encode($response, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE)
                );
                $APPLICATION->ThrowException(
                    'Пользователь с указанными почтой или телефоном уже существует в системе. Вы можете <a href="/personal/profile/">авторизоваться</a> или <a href="/personal/profile/?forgot_password=yes">восстановить пароль</a>. '
                    . '*CRM:getContactID',
                    'already_registered'
                );
            }

            return false;
        }
    }

    public function OnAfterUserRegisterHandler(&$arFields) {
        global $APPLICATION;

        // если регистрация успешна то
        if($arFields["USER_ID"]>0)
        {
            $response = $this->isUserRegistered($arFields,false);

            if( !$response ){
                $createdContactId = $this->createB24Company($arFields);
                if ($createdContactId !== false && $createdContactId > 0) {
                    $response = ['ID' => $createdContactId];
                } else {
                    $response = $this->isUserRegistered($arFields,false);
                }
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

            // Синхронизация с CRM выполняется после успешного CUser::Add; остаточное ThrowException из createB24Company не должно «висеть» в APPLICATION.
            if ($APPLICATION instanceof \CMain && method_exists($APPLICATION, 'ResetException')) {
                $APPLICATION->ResetException();
            }
        }
    }
    
}