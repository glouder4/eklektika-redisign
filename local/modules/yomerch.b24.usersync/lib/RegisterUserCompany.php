<?php
namespace OnlineService\B24;
use OnlineService\B24\UserSync\Config\RegisterUserCompanyConfig;
use OnlineService\B24\UserSync\Config\UserSyncConfig;
use OnlineService\Site\Config\CompanyB24Config;
use OnlineService\Site\Config\CompanyModuleConfig;
use OnlineService\B24\User;
use OnlineService\B24\Request;
class RegisterUserCompany extends Request{
    private const REASON_EMAIL_CONFLICT_SITE = 'email_conflict_site';
    private const REASON_EMAIL_CONFLICT_CRM = 'email_conflict_crm';
    private const REASON_EMAIL_CONFLICT_BOTH = 'email_conflict_both';
    private const REASON_EMAIL_CHECK_UNAVAILABLE = 'email_check_unavailable';
    private const EMAIL_CONFLICT_MESSAGE = 'Пользователь с указанной почтой уже существует в системе. Вы можете <a href="/personal/profile/">авторизоваться</a> или <a href="/personal/profile/?forgot_password=yes">восстановить пароль</a>.';

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

    private function normalizeEmailForCompare($email): string
    {
        $email = trim((string)$email);
        if ($email === '') {
            return '';
        }

        return mb_strtolower($email, 'UTF-8');
    }

    /**
     * @return int[]
     */
    private function findSiteUserIdsByEmail(string $normalizedEmail): array
    {
        if ($normalizedEmail === '') {
            return [];
        }

        $result = [];
        $rsUsers = \CUser::GetList(
            $by = 'id',
            $order = 'asc',
            ['=EMAIL' => $normalizedEmail],
            ['SELECT' => ['ID', 'EMAIL']]
        );

        while ($user = $rsUsers->Fetch()) {
            $email = $this->normalizeEmailForCompare($user['EMAIL'] ?? '');
            if ($email !== '' && $email === $normalizedEmail) {
                $result[] = (int)($user['ID'] ?? 0);
            }
        }

        return array_values(array_unique(array_filter($result, static function (int $id): bool {
            return $id > 0;
        })));
    }

    private function hasCrmEmailConflict(string $normalizedEmail, ?bool &$transportAvailable = null): bool
    {
        if ($normalizedEmail === '') {
            $transportAvailable = true;

            return false;
        }

        $payload = [
            'type' => 'EMAIL',
            'values' => [$normalizedEmail],
        ];
        $response = $this->callB24Method('crm.duplicate.findbycomm', $payload, false);

        if (!is_array($response)) {
            $transportAvailable = false;

            return false;
        }
        if (array_key_exists('success', $response) && (int)$response['success'] === 0) {
            $transportAvailable = false;

            return false;
        }
        if (array_key_exists('error', $response) && !array_key_exists(0, $response)) {
            $transportAvailable = false;

            return false;
        }

        $transportAvailable = true;
        $contacts = [];
        if (isset($response['CONTACT']) && is_array($response['CONTACT'])) {
            $contacts = $response['CONTACT'];
        } elseif (isset($response['result']['CONTACT']) && is_array($response['result']['CONTACT'])) {
            $contacts = $response['result']['CONTACT'];
        } elseif (isset($response['RESULT']['CONTACT']) && is_array($response['RESULT']['CONTACT'])) {
            $contacts = $response['RESULT']['CONTACT'];
        }

        $companies = [];
        if (isset($response['COMPANY']) && is_array($response['COMPANY'])) {
            $companies = $response['COMPANY'];
        } elseif (isset($response['result']['COMPANY']) && is_array($response['result']['COMPANY'])) {
            $companies = $response['result']['COMPANY'];
        } elseif (isset($response['RESULT']['COMPANY']) && is_array($response['RESULT']['COMPANY'])) {
            $companies = $response['RESULT']['COMPANY'];
        }

        return !empty($contacts) || !empty($companies);
    }

    private function resolveEmailConflictReasonCode(bool $siteConflict, bool $crmConflict): ?string
    {
        if ($siteConflict && $crmConflict) {
            return self::REASON_EMAIL_CONFLICT_BOTH;
        }
        if ($siteConflict) {
            return self::REASON_EMAIL_CONFLICT_SITE;
        }
        if ($crmConflict) {
            return self::REASON_EMAIL_CONFLICT_CRM;
        }

        return null;
    }

    private function ensureEmailUniquenessPrecheck(array &$arFields): bool
    {
        global $APPLICATION;

        $normalizedEmail = $this->normalizeEmailForCompare($arFields['EMAIL'] ?? '');
        if ($normalizedEmail === '') {
            return true;
        }
        $arFields['EMAIL'] = trim((string)$arFields['EMAIL']);

        $siteConflict = !empty($this->findSiteUserIdsByEmail($normalizedEmail));
        $crmTransportAvailable = null;
        $crmConflict = $this->hasCrmEmailConflict($normalizedEmail, $crmTransportAvailable);
        $reasonCode = $this->resolveEmailConflictReasonCode($siteConflict, $crmConflict);

        if ($reasonCode !== null) {
            $APPLICATION->ThrowException(self::EMAIL_CONFLICT_MESSAGE, $reasonCode);

            return false;
        }
        if (!$crmConflict && $crmTransportAvailable === false) {
            $APPLICATION->ThrowException(
                'Не удалось проверить уникальность email. Повторите попытку позже.',
                self::REASON_EMAIL_CHECK_UNAVAILABLE
            );

            return false;
        }

        return true;
    }

    private function normalizePhoneForCompare($phone): string
    {
        $digits = preg_replace('/\D+/', '', (string)$phone);
        if ($digits === null || $digits === '') {
            return '';
        }

        if (strlen($digits) === 11 && strpos($digits, '8') === 0) {
            $digits = '7' . substr($digits, 1);
        }

        return $digits;
    }

    private function extractFirstCrmMultiFieldValue($field): string
    {
        if (!is_array($field) || empty($field)) {
            return '';
        }

        $first = reset($field);
        if (is_array($first) && isset($first['VALUE']) && is_scalar($first['VALUE'])) {
            return trim((string)$first['VALUE']);
        }

        if (is_scalar($first)) {
            return trim((string)$first);
        }

        return '';
    }

    private function hasDirectIdentityMatch(array $response, string $inputEmail, string $inputPhone): bool
    {
        $responseEmail = $this->normalizeEmailForCompare($this->extractFirstCrmMultiFieldValue($response['EMAIL'] ?? []));
        $responsePhone = $this->normalizePhoneForCompare($this->extractFirstCrmMultiFieldValue($response['PHONE'] ?? []));

        if ($inputEmail !== '' && $responseEmail !== '' && $inputEmail === $responseEmail) {
            return true;
        }

        if ($inputPhone !== '' && $responsePhone !== '' && $inputPhone === $responsePhone) {
            return true;
        }

        return false;
    }

    private function isDuplicateConfirmed(array $arFields, array $response, array &$diagnostics = []): bool
    {
        $inputEmail = $this->normalizeEmailForCompare($arFields['EMAIL'] ?? '');
        $inputPhone = $this->normalizePhoneForCompare($arFields['PERSONAL_PHONE'] ?? '');
        $responseHasEmail = !empty($response['EMAIL']);
        $responseHasPhone = !empty($response['PHONE']);

        $diagnostics = [
            'response_has_email' => $responseHasEmail,
            'response_has_phone' => $responseHasPhone,
            'response_has_id' => isset($response['ID']) && (is_scalar($response['ID']) || $response['ID'] === null),
            'fallback_used' => false,
            'reason' => null,
        ];

        if ($this->hasDirectIdentityMatch($response, $inputEmail, $inputPhone)) {
            $diagnostics['reason'] = 'direct_match';
            return true;
        }

        if ($responseHasEmail || $responseHasPhone) {
            $diagnostics['reason'] = 'direct_mismatch';
            return false;
        }

        $contactId = isset($response['ID']) ? (int)$response['ID'] : 0;
        if ($contactId <= 0) {
            $diagnostics['reason'] = 'no_contact_id_for_fallback';
            return false;
        }

        $diagnostics['fallback_used'] = true;

        $contact = $this->callB24Method('crm.contact.get', ['id' => $contactId], false);
        if (!is_array($contact) || empty($contact)) {
            $diagnostics['reason'] = 'fallback_contact_get_failed';
            return false;
        }

        if ($this->hasDirectIdentityMatch($contact, $inputEmail, $inputPhone)) {
            $diagnostics['reason'] = 'fallback_match';
            return true;
        }

        $diagnostics['reason'] = 'fallback_unconfirmed';
        return false;
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
     * Пишет диагностику регистрации/синка в системный журнал Битрикс (CEventLog).
     */
    private static function logPostRegistrationSyncIssue(string $message, ?string $reason = null): void
    {
        if (!class_exists('\CEventLog')) {
            return;
        }

        $reason = is_string($reason) ? trim($reason) : '';
        $reasonTag = $reason !== '' ? '[reason=' . $reason . '] ' : '';

        \CEventLog::Add([
            'SEVERITY' => 'WARNING',
            'AUDIT_TYPE_ID' => 'YOMERCH_POST_REGISTRATION_SYNC_ISSUE',
            'MODULE_ID' => 'yomerch.b24.usersync',
            'ITEM_ID' => 'RegisterUserCompany',
            'DESCRIPTION' => '[RegisterUserCompany.post_registration] ' . $reasonTag . $message,
        ]);
    }

    private function isDebugGateEnabled(): bool
    {
        if (!isset($_GET['debug']) || !is_scalar($_GET['debug'])) {
            return false;
        }

        return trim((string)$_GET['debug']) === '1';
    }

    /**
     * @return string|null crm_* classification or null for non-CRM
     */
    private function classifyCrmError(?string $marker, array $crmResponse): ?string
    {
        if ($marker === '*CRM:getContactID') {
            return 'crm_get_contact_id';
        }

        if (isset($crmResponse['success']) && (int)$crmResponse['success'] === 0) {
            return 'crm_transport_error';
        }

        if (isset($crmResponse['error']) || isset($crmResponse['error_code'])) {
            return 'crm_response_error';
        }

        return null;
    }

    private function isPotentiallyDangerousPayloadKey(string $key): bool
    {
        $lowerKey = strtolower($key);
        $dangerousParts = ['raw', 'trace', 'request', 'stack', 'payload_dump', 'debug_dump'];
        foreach ($dangerousParts as $part) {
            if (strpos($lowerKey, $part) !== false) {
                return true;
            }
        }

        return false;
    }

    private function maskEmailLikeString(string $value): string
    {
        return (string)preg_replace_callback(
            '/([a-z0-9._%+\-]{1,64})@([a-z0-9.\-]+\.[a-z]{2,})/iu',
            static function (array $matches): string {
                $local = (string)$matches[1];
                $domain = (string)$matches[2];
                $first = substr($local, 0, 1);

                return ($first !== '' ? $first : '*') . '***@' . $domain;
            },
            $value
        );
    }

    private function maskPhoneLikeString(string $value): string
    {
        return (string)preg_replace_callback(
            '/(?<!\d)(\+?\d[\d\-\(\)\s]{8,}\d)(?!\d)/u',
            static function (array $matches): string {
                $rawPhone = (string)$matches[1];
                $digits = preg_replace('/\D+/', '', $rawPhone);
                if ($digits === null || strlen($digits) < 10 || strlen($digits) > 15) {
                    return $rawPhone;
                }

                return '***' . substr($digits, -4);
            },
            $value
        );
    }

    private function maskInnLikeString(string $value): string
    {
        return (string)preg_replace_callback(
            '/(?<!\d)(\d{10}|\d{12})(?!\d)/',
            static function (array $matches): string {
                $inn = (string)$matches[1];

                return str_repeat('*', max(strlen($inn) - 2, 1)) . substr($inn, -2);
            },
            $value
        );
    }

    private function hasTokenLikeShape(string $value): bool
    {
        if (preg_match('/^Bearer\s+[A-Za-z0-9\-\._~+\/]+=*$/', $value) === 1) {
            return true;
        }

        if (preg_match('/^[A-Za-z0-9\-_]+\.[A-Za-z0-9\-_]+\.[A-Za-z0-9\-_]+$/', $value) === 1) {
            return true;
        }

        return preg_match('/^[A-Za-z0-9\-_]{24,}$/', $value) === 1;
    }

    private function applyValueBasedMasking(string $value): string
    {
        $masked = $value;

        if (strpos($masked, '@') !== false) {
            $masked = $this->maskEmailLikeString($masked);
        }

        $masked = $this->maskPhoneLikeString($masked);
        $masked = $this->maskInnLikeString($masked);

        if ($this->hasTokenLikeShape(trim($masked))) {
            return '***';
        }

        return $masked;
    }

    private function buildSafeFallbackForDangerousValue($value): string
    {
        if (is_array($value)) {
            return '[masked dangerous payload array: ' . count($value) . ' keys]';
        }

        if (!is_scalar($value) && $value !== null) {
            return '[masked dangerous payload object]';
        }

        $stringValue = (string)$value;
        $length = strlen($stringValue);
        if ($length <= 0) {
            return '[masked dangerous payload]';
        }

        if ($length > 256) {
            return '[masked dangerous payload, len=' . $length . ']';
        }

        return '[masked dangerous payload]';
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    private function maskSensitiveValueByKey(string $key, $value)
    {
        if (!is_scalar($value) && $value !== null) {
            return $value;
        }

        $stringValue = (string)$value;
        $lowerKey = strtolower($key);

        if ($this->isPotentiallyDangerousPayloadKey($lowerKey)) {
            return $this->buildSafeFallbackForDangerousValue($value);
        }

        $alwaysMaskParts = ['password', 'passwd', 'secret', 'token', 'auth', 'authorization', 'sync_token', 'webhook'];
        foreach ($alwaysMaskParts as $part) {
            if (strpos($lowerKey, $part) !== false) {
                return '***';
            }
        }

        if (strpos($lowerKey, 'email') !== false) {
            if (strpos($stringValue, '@') === false) {
                return '***';
            }
            [$local, $domain] = explode('@', $stringValue, 2);
            if ($local === '') {
                return '***@' . $domain;
            }

            return substr($local, 0, 1) . '***@' . $domain;
        }

        if (strpos($lowerKey, 'phone') !== false || strpos($lowerKey, 'mobile') !== false) {
            $digits = preg_replace('/\D+/', '', $stringValue);
            if ($digits === '' || $digits === null) {
                return '***';
            }
            $suffix = substr($digits, -4);

            return '***' . $suffix;
        }

        if (strpos($lowerKey, 'inn') !== false || strpos($lowerKey, 'kpp') !== false) {
            if (strlen($stringValue) <= 2) {
                return '**';
            }

            return str_repeat('*', max(strlen($stringValue) - 2, 1)) . substr($stringValue, -2);
        }

        return $this->applyValueBasedMasking($stringValue);
    }

    /**
     * @param mixed $payload
     * @return mixed
     */
    private function maskSensitivePayload($payload)
    {
        if (!is_array($payload)) {
            return $payload;
        }

        $masked = [];
        foreach ($payload as $key => $value) {
            $stringKey = (string)$key;
            if (is_array($value)) {
                $masked[$key] = $this->maskSensitivePayload($value);
                continue;
            }

            $masked[$key] = $this->maskSensitiveValueByKey($stringKey, $value);
        }

        return $masked;
    }

    private function buildCrmDebugBlock(string $classification, array $crmResponse, ?string $marker = null, array $context = []): string
    {
        $payload = [
            'error_classification' => $classification,
            'crm_marker' => $marker,
            'crm_response' => $this->maskSensitivePayload($crmResponse),
            'context' => $this->maskSensitivePayload($context),
        ];

        return '<br><br><details class="crm-debug"><summary>CRM debug details</summary><pre>'
            . htmlspecialchars(
                json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE),
                ENT_QUOTES | ENT_SUBSTITUTE,
                'UTF-8'
            )
            . '</pre></details>';
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
                self::logPostRegistrationSyncIssue($errorMessage, 'requisites_file_upload_error');

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
                    . (string)($arFields['USER_ID'] ?? $arFields['ID'] ?? ''),
                    'company_required_fields_missing'
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
                                . (string)($arFields['USER_ID'] ?? $arFields['ID'] ?? ''),
                                'crm_company_add_empty_id'
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
                'crm.contact.add не вернул числовой ID контакта; ответ=' . json_encode($contactId, JSON_UNESCAPED_UNICODE),
                'crm_contact_add_invalid_response'
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
        if (!$this->ensureEmailUniquenessPrecheck($arFields)) {
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
            $duplicateDiagnostics = [];
            $isConfirmedDuplicate = is_array($response)
                && $this->isDuplicateConfirmed($arFields, $response, $duplicateDiagnostics);

            if (!$isConfirmedDuplicate) {
                $duplicateReason = is_string($duplicateDiagnostics['reason'] ?? null)
                    ? (string)$duplicateDiagnostics['reason']
                    : 'duplicate_unconfirmed';
                self::logPostRegistrationSyncIssue(
                    'OnBeforeUserRegister: duplicate response ignored as unconfirmed. diagnostics='
                    . json_encode($duplicateDiagnostics, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE)
                    . '; response='
                    . json_encode($this->maskSensitivePayload(is_array($response) ? $response : []), JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE),
                    $duplicateReason
                );

                if ($arFields['PASSWORD'] == $arFields['CONFIRM_PASSWORD']) {
                    $arFields['UF_ADVERSTERING_AGENT'] = "";
                    return $arFields;
                }
                $APPLICATION->ThrowException('Указанные пароли не совпадают.');
                return false;
            }

            $hasPhone = isset($response['PHONE']) && !empty($response['PHONE']);
            $hasEmail = isset($response['EMAIL']) && !empty($response['EMAIL']);
            if ($hasPhone || $hasEmail) {
                $APPLICATION->ThrowException('Пользователь с указанными почтой или телефоном уже существует в системе. Вы можете <a href="/personal/profile/">авторизоваться</a> или <a href="/personal/profile/?forgot_password=yes">восстановить пароль</a>','already_registered');
            } else {
                // Подтвержденный fallback по ID при ответе getContactID без EMAIL/PHONE.
                $baselineMessage = 'Пользователь с указанными почтой или телефоном уже существует в системе. Вы можете <a href="/personal/profile/">авторизоваться</a> или <a href="/personal/profile/?forgot_password=yes">восстановить пароль</a>.';
                $crmMarker = '*CRM:getContactID';
                $crmClassification = $this->classifyCrmError($crmMarker, is_array($response) ? $response : []);
                $message = $baselineMessage;
                if ($this->isDebugGateEnabled() && $crmClassification !== null) {
                    $message .= $this->buildCrmDebugBlock(
                        $crmClassification,
                        is_array($response) ? $response : [],
                        $crmMarker,
                        ['fallback' => $duplicateDiagnostics]
                    );
                }
                $APPLICATION->ThrowException(
                    $message,
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

                // После регистрации применяем скидочную группу из статуса компании (OS_COMPANY_STATUS),
                // куда пользователь был добавлен как сотрудник при создании/связывании компании.
                if (class_exists(\OnlineService\Site\Company::class)) {
                    \OnlineService\Site\Company::applyCompanyStatusGroupToSiteUser((int)$arFields["USER_ID"]);
                }

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