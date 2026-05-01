<?php
    namespace OnlineService\B24;
    use OnlineService\B24\UserSync\Config\UserSyncConfig;
    use OnlineService\B24\Request;
    use OnlineService\Site\Config\CompanyModuleConfig;
    class User extends Request{
        private const INBOUND_SITE_USER_ID_UF = 'UF_CRM_3804624445748';
        public ?int $contactId = null;
        private ?string $lastUpdateFailReason = null;
        private ?string $lastDeleteFailReason = null;
        /** @var array<string,mixed> */
        private array $lastUpdateMeta = [];

        public int $userId;
        
        // Константы для ID групп
        /** Администраторы сайта — при любом обновлении групп сохраняем членство, если оно было */
        public int $ADMINISTRATORS_GROUP_ID = UserSyncConfig::ADMINISTRATORS_GROUP_ID;
        public int $MARKETING_AGENT_GROUP_ID = UserSyncConfig::MARKETING_AGENT_GROUP_ID;
        public int $DIRECTOR_GROUP_ID = UserSyncConfig::DIRECTOR_GROUP_ID;
        public function __construct()
        {
        }

        public function getContactID($arFields,$returnAll = false,$debug = false){
            $arFields = array_merge($arFields,[
                "ACTION" => 'GET_CONTACT_ID',
                "SORT" => 'ID',
                "ORDER" => 'asc',
            ]);

            // найти пользователя в б24 по EMAIL
            $response = $this->sendRequest($arFields,$debug);

            if( $response['success'] == 1 ){
                return ($returnAll) ? $response['data'] : $response['data']['ID'];
            }

            return [];
        }

        private function deleteContact($contactId){
            $arFields = [
                'ACTION' => "DELETE_CONTACT",
                'ID' => $contactId
            ];


            // найти пользователя в б24 по EMAIL
            $response = $this->sendRequest($arFields,false);

            if( !$response['success'] ){
                global $APPLICATION;
                $APPLICATION->ThrowException($response);

                return false;
            }
        }


        public function OnBeforeUserDeleteHandler($userId){
            $userObject = $this->getUserObject($userId);

            if( $userObject )
                $this->deleteContact($userObject['CONTACT_ID']);
        }

        public function isUserRegistered($arFields,$debug){
            return $this->getContactID([
                'EMAIL' => $arFields['EMAIL'],
                'PHONE' => $arFields['PERSONAL_PHONE']
            ],true,$debug);
        }

        /**
         * Получить ID пользователя на сайте по ID контакта в B24
         * 
         * @param int $b24ContactId ID контакта в B24
         * @return int|false ID пользователя на сайте или false если не найден
         */
        public function getUserIDByB24ID($b24ContactId){
            if ($b24ContactId === null || $b24ContactId === false) {
                return false;
            }
            if (\is_string($b24ContactId)) {
                $b24ContactId = \trim($b24ContactId);
            }
            if ($b24ContactId === '' || $b24ContactId === '0' || $b24ContactId === 0) {
                return false;
            }

            // Ищем по UF_B24_USER_ID (= CRM CONTACT.ID); Bitrix иногда хранит как int, запрос — строкой или наоборот.
            $variants = [$b24ContactId];
            if (\is_string($b24ContactId) && \ctype_digit($b24ContactId)) {
                $variants[] = (int)$b24ContactId;
            } elseif (\is_int($b24ContactId) && $b24ContactId > 0) {
                $variants[] = (string)$b24ContactId;
            }
            $variants = \array_values(\array_unique($variants, \SORT_REGULAR));

            foreach ($variants as $v) {
                $rsUser = \CUser::GetList(
                    [],
                    'asc',
                    ['UF_B24_USER_ID' => $v],
                    ['SELECT' => ['ID', 'UF_B24_USER_ID']]
                );
                if ($userObject = $rsUser->Fetch()) {
                    return $userObject['ID'];
                }
            }

            return false;
        }
        public function getUserObject($userId){

            $rsUser = \CUser::GetList(
                array(), 
                $order = "asc", 
                array('ID' => $userId),
                array('SELECT' => array('UF_B24_USER_ID'))
            );

            if( $userObject = $rsUser->Fetch() ){
                $this->userId = $userObject['ID'];
                $ID = $userObject['ID'];
                $email = $userObject['EMAIL'];
                $phone = $userObject['PERSONAL_PHONE'];
                $b24UserId = $userObject['UF_B24_USER_ID'];

                // Если у пользователя уже есть UF_B24_USER_ID, используем его
                if (!empty($b24UserId)) {
                    $userObject['CONTACT_ID'] = $b24UserId;
                    return $userObject;
                }

                // Иначе ищем контакт в B24 по email/телефону
                $contactId = $this->getContactID([
                    'ID' => $ID,
                    'EMAIL' => $email,
                    'PHONE' => $phone
                ]);

                $userObject['CONTACT_ID'] = $contactId;

                return $userObject;
            }

            return false;
        }
        /**
         * Обновление контакта в B24 по ID контакта
         * 
         * @param int $contactId ID контакта в B24
         * @return array|false Результат обновления или false при ошибке
         */
        public function updateContact($contactId){
            if (empty($contactId)) {
                return false;
            }

            // Получаем данные пользователя из B24 для обновления
            $arFields = [
                'ACTION' => 'UPDATE_CONTACT',
                'ID' => $contactId
            ];

            /*$response = $this->sendRequest($arFields);

            if ($response['success'] == 1) {
                return $response['data'];
            } else {
                return false;
            }*/
        }

        public function OnAfterUserUpdateHandler($arFields){
            $userObject = $this->getUserObject($arFields['ID']);
            if( isset($arFields['UF_ADVERSTERING_AGENT']) )
                $this->updateMarketingAgentPriceType($arFields['UF_ADVERSTERING_AGENT']);

            //if( $userObject )
                //$this->updateContact($userObject['CONTACT_ID']);

            return true;
        }

        /**
         * Получить список ID пользователей в группе
         * @param int $groupId ID группы
         * @return array Массив ID пользователей
         */
        public function getUsersInGroup($groupId){
            $userIds = array();
            
            // Получаем список пользователей в группе
            $rsUsers = \CUser::GetList(
                array('ID' => 'ASC'),
                array('ASC'),
                array('GROUPS_ID' => $groupId),
                array('SELECT' => array('ID'))
            );
            
            while ($user = $rsUsers->Fetch()) {
                $userIds[] = $user['ID'];
            }
            
            return $userIds;
        }

        /**
         * Получить список групп пользователя
         * @param int $userId ID пользователя
         * @return array Массив ID групп пользователя
         */
        public function getUserGroups($userId){
            $userId = (int)$userId;
            if ($userId <= 0) {
                return [];
            }

            // Важно: поле GROUPS_ID из CUser::GetByID не гарантирует полный список членства;
            // источник истины — CUser::GetUserGroup (как в addUserToGroups / removeUserFromGroupsByIds).
            $ids = \CUser::GetUserGroup($userId);
            if (!is_array($ids)) {
                $ids = $ids !== null && $ids !== '' && $ids !== false
                    ? [(int)$ids]
                    : [];
            } else {
                $ids = array_map('intval', $ids);
            }

            return $this->normalizeUserGroupIds($ids);
        }

        /**
         * Добавить пользователя в группу
         * @param int $userId ID пользователя
         * @param int $groupId ID группы
         * @return bool Результат операции
         */
        public function addUserToGroup($userId, $groupId){
            $userId = (int)$userId;
            $groupId = (int)$groupId;
            if ($userId <= 0 || $groupId <= 0) {
                return false;
            }

            $cur = $this->normalizeUserGroupIds(\CUser::GetUserGroup($userId));
            if (in_array($groupId, $cur, true)) {
                return (bool)(new \CUser())->Update($userId, [
                    'UF_ADVERSTERING_AGENT' => 1,
                    'ACTIVE' => 'Y',
                ]);
            }

            $hadAdministratorsGroup = in_array($this->ADMINISTRATORS_GROUP_ID, $cur, true);
            $new = $this->normalizeUserGroupIds(array_merge($cur, [$groupId]));
            if ($hadAdministratorsGroup && !in_array($this->ADMINISTRATORS_GROUP_ID, $new, true)) {
                $new[] = $this->ADMINISTRATORS_GROUP_ID;
            }

            \CUser::SetUserGroup($userId, $new);

            return (bool)(new \CUser())->Update($userId, [
                'UF_ADVERSTERING_AGENT' => 1,
                'ACTIVE' => 'Y',
            ]);
        }
        public function addUserToGroups($userId, $groupIds, $userObj = null){
            $userId = (int)$userId;
            if ($userId <= 0) {
                return false;
            }

            $currentGroups = \CUser::GetUserGroup($userId);
            if (!is_array($currentGroups)) {
                $currentGroups = $currentGroups !== null && $currentGroups !== '' && $currentGroups !== false
                    ? [(int)$currentGroups]
                    : [];
            } else {
                $currentGroups = array_map('intval', $currentGroups);
            }

            $hadAdministratorsGroup = in_array($this->ADMINISTRATORS_GROUP_ID, $currentGroups, true);

            $toAdd = [];
            foreach ((array)$groupIds as $gid) {
                $gid = (int)$gid;
                if ($gid > 0) {
                    $toAdd[] = $gid;
                }
            }

            if ($toAdd === []) {
                return true;
            }

            $userGroups = array_values(array_unique(array_merge($currentGroups, $toAdd)));
            if ($hadAdministratorsGroup && !in_array($this->ADMINISTRATORS_GROUP_ID, $userGroups, true)) {
                $userGroups[] = $this->ADMINISTRATORS_GROUP_ID;
            }

            $arFields = array(
                'GROUP_ID' => $userGroups
            );

            if (in_array($this->MARKETING_AGENT_GROUP_ID, $userGroups, true)) {
                $arFields['UF_ADVERSTERING_AGENT'] = 1;
                $arFields['ACTIVE'] = 'Y';
            }

            $result = (new \CUser)->Update($userId, $arFields);
            if ($result) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Убрать пользователя из перечисленных групп (только GROUP_ID), без изменения UF/ACTIVE.
         * Для снятия скидочных групп компании; не путать с {@see removeUserFromGroup} (там побочные поля).
         *
         * Группы скидки компании ({@see CompanyModuleConfig::getCompanyDiscountProtectedSiteGroupIds()}) по умолчанию
         * **не снимаются** — только через {@see \OnlineService\Site\Company::applyB24CompanyGroupsToUser()} ($allowCompanyDiscountGroupRemoval = true).
         *
         * @param list<int|mixed> $groupIdsToRemove
         */
        public function removeUserFromGroupsByIds(int $userId, array $groupIdsToRemove, bool $allowCompanyDiscountGroupRemoval = false): bool
        {
            $userId = (int)$userId;
            if ($userId <= 0) {
                return false;
            }

            $remove = [];
            foreach ($groupIdsToRemove as $g) {
                $g = (int)$g;
                if ($g > 0) {
                    $remove[$g] = true;
                }
            }
            unset($remove[$this->ADMINISTRATORS_GROUP_ID]);
            if (!$allowCompanyDiscountGroupRemoval) {
                foreach (CompanyModuleConfig::getCompanyDiscountProtectedSiteGroupIds() as $dg) {
                    unset($remove[$dg]);
                }
            }
            if ($remove === []) {
                return true;
            }

            $current = \CUser::GetUserGroup($userId);
            if (!is_array($current)) {
                $current = $current !== null && $current !== '' && $current !== false
                    ? [(int)$current]
                    : [];
            } else {
                $current = array_map('intval', $current);
            }

            $hadAdministratorsGroup = in_array($this->ADMINISTRATORS_GROUP_ID, $current, true);

            $new = [];
            foreach ($current as $g) {
                if (!isset($remove[$g])) {
                    $new[] = $g;
                }
            }

            if ($hadAdministratorsGroup && !in_array($this->ADMINISTRATORS_GROUP_ID, $new, true)) {
                $new[] = $this->ADMINISTRATORS_GROUP_ID;
            }

            if ($new === $current) {
                return true;
            }

            \CUser::SetUserGroup($userId, $new);

            return true;
        }

        /**
         * Удалить пользователя из группы
         * @param int $userId ID пользователя
         * @param int $groupId ID группы
         * @return bool Результат операции
         */
        public function removeUserFromGroup($userId, $groupId){
            if ((int)$groupId === $this->ADMINISTRATORS_GROUP_ID) {
                return true;
            }

            $user = new \CUser();
            
            // Получаем текущие группы пользователя
            $rsUser = \CUser::GetByID($userId);
            $userData = $rsUser->Fetch();
            
            if (!$userData) {
                return false;
            }
            
            // Удаляем группу из списка групп пользователя
            $userGroups = $userData['GROUPS_ID'];
            if (is_array($userGroups)) {
                $userGroups = array_diff($userGroups, array($groupId));
            } else {
                $userGroups = array();
            }
            
            $arFields = array(
                'GROUP_ID' => $userGroups,
                'UF_ADVERSTERING_AGENT' => 0,
                'ACTIVE' => 'N'
            );
            
            $result = $user->Update($userId, $arFields);
            
            if ($result) {
                return true;
            } else {
                return false;
            }
        }

        private function updateMarketingAgentPriceType($status, $userId = null){
            // Получаем информацию о группе рекламных агентов
            $rsGroup = \CGroup::GetByID($this->MARKETING_AGENT_GROUP_ID);
            $groupData = $rsGroup->Fetch();

            if( is_null($userId) ){
                $userId = $this->userId;
            }
            
            if (!$groupData) {
                return false;
            }
            
            // Получаем текущий список пользователей в группе
            $currentUserIds = $this->getUsersInGroup($this->MARKETING_AGENT_GROUP_ID);
            
            // Определяем, нужно ли добавить или удалить пользователя из группы
            $isUserInGroup = in_array($userId, $currentUserIds);
            $shouldBeInGroup = ($status === 'Y' || $status === true || $status === 1 || $status === "1");
            
            if ($shouldBeInGroup && !$isUserInGroup) {
                // Добавляем пользователя в группу
                return $this->addUserToGroup($userId, $this->MARKETING_AGENT_GROUP_ID);
                
            } elseif (!$shouldBeInGroup && $isUserInGroup) {
                // Снятие агента: только группа агента через SetUserGroup; затем отдельно UF/ACTIVE без GROUP_ID
                // (не removeUserFromGroup — там смешаны группы и поля в одном CUser::Update).
                if (!$this->removeUserFromGroupsByIds((int)$userId, [$this->MARKETING_AGENT_GROUP_ID])) {
                    return false;
                }
                $u = new \CUser();
                return (bool)$u->Update((int)$userId, [
                    'ACTIVE' => 'N',
                    'UF_ADVERSTERING_AGENT' => 0,
                ]);
                
            } else {
                return true;
            }
        }

        private function getManagerID($manager_xml_id){
            // Ищем элемент по внешнему коду (XML_ID)
            $arFilter = [
                'IBLOCK_ID' => 53,
                'XML_ID' => $manager_xml_id
            ];

            $rsElement = \CIBlockElement::GetList(
                ['SORT' => 'ASC'],
                $arFilter,
                false,
                false,
                ['ID', 'NAME', 'XML_ID', 'IBLOCK_ID']
            );

            if ($managerElement = $rsElement->GetNext()) {
                return $managerElement['ID'];
            }

            return false;
        }


        /**
         * Обновление пользователя на сайте по ID контакта в B24
         * 
         * @param array $fields Поля для обновления:
         * - 'ID' => ID контакта в B24 (обязательно)
         * - 'NAME' => Имя
         * - 'LAST_NAME' => Фамилия  
         * - 'SECOND_NAME' => Отчество
         * - 'EMAIL' => Email
         * - 'PERSONAL_PHONE' => Телефон
         * - 'WORK_POSITION' => Должность
         * - 'PERSONAL_BIRTHDAY' => Дата рождения
         * 
         * @return bool Результат обновления
         */
        public function update($fields){
            $this->lastUpdateFailReason = null;
            $legacyId = isset($fields['ID']) && \is_scalar($fields['ID']) ? (string)$fields['ID'] : '';
            $siteUserInboundId = isset($fields[self::INBOUND_SITE_USER_ID_UF]) && \is_scalar($fields[self::INBOUND_SITE_USER_ID_UF])
                ? \trim((string)$fields[self::INBOUND_SITE_USER_ID_UF])
                : '';
            $requestedCompanyB24Id = isset($fields['OS_COMPANY_B24_ID']) && \is_scalar($fields['OS_COMPANY_B24_ID'])
                ? \trim((string)$fields['OS_COMPANY_B24_ID'])
                : '';
            $this->lastUpdateMeta = [
                'lookup_b24_id' => null,
                'legacy_id' => $legacyId,
                'resolved_user_id' => null,
                'resolution_source' => null,
                'updated' => false,
                'changed_fields_count' => 0,
                'changed_fields' => [],
                'diff_is_unknown' => false,
                'no_effect_reason' => null,
                'resolved_entities' => [
                    'crm_contact_b24_id' => null,
                    'legacy_request_id' => $legacyId,
                    'site_user_request_id' => $siteUserInboundId !== '' ? $siteUserInboundId : null,
                    'site_user_id' => null,
                    'request_company_b24_id' => $requestedCompanyB24Id !== '' ? $requestedCompanyB24Id : null,
                ],
                'id_resolution' => [
                    'source' => null,
                    'status' => 'failed',
                    'used_fallback' => false,
                    'reason_code' => 'update_contact_missing_identifier',
                ],
            ];
            $resolved = $this->resolveUpdateContactIdentity($fields);
            $this->lastUpdateMeta['id_resolution'] = $resolved['id_resolution'];
            $this->lastUpdateMeta['resolution_source'] = isset($resolved['id_resolution']['source']) && \is_string($resolved['id_resolution']['source'])
                ? $resolved['id_resolution']['source']
                : null;
            $this->lastUpdateMeta['lookup_b24_id'] = $resolved['lookup_b24_id'];
            $this->lastUpdateMeta['resolved_entities']['crm_contact_b24_id'] = $resolved['lookup_b24_id'];
            // Убираем inbound-ID поля, чтобы не пытаться обновлять их как поля пользователя.
            unset($fields['B24_ID'], $fields[self::INBOUND_SITE_USER_ID_UF]);
            if (!$resolved['ok']) {
                $this->lastUpdateFailReason = (string)($resolved['id_resolution']['reason_code'] ?? 'update_contact_user_not_found');
                $this->lastUpdateMeta['resolved_user_id'] = null;
                $this->lastUpdateMeta['updated'] = false;
                $this->lastUpdateMeta['resolved_entities']['site_user_id'] = null;
                return false;
            }
            $this->userId = (int)$resolved['site_user_id'];
            $this->applyInboundManagerMapping(
                $fields,
                UserSyncConfig::USER_PRIMARY_MANAGER_FIELD,
                UserSyncConfig::CRM_PRIMARY_MANAGER_FIELD,
                UserSyncConfig::CRM_PRIMARY_MANAGER_LEGACY_FIELD
            );
            $this->applyInboundManagerMapping(
                $fields,
                UserSyncConfig::USER_SECONDARY_MANAGER_FIELD,
                UserSyncConfig::CRM_SECONDARY_MANAGER_FIELD,
                UserSyncConfig::CRM_SECONDARY_MANAGER_LEGACY_FIELD
            );
            
            $this->lastUpdateMeta['resolved_user_id'] = (int)$this->userId;
            $this->lastUpdateMeta['resolved_entities']['site_user_id'] = (int)$this->userId;

            // Обновляем пользователя на сайте
            $user = new \CUser();

            if (($fields['ACTION'] ?? '') === 'UPDATE_CONTACT' && array_key_exists('UF_IS_DIRECTOR', $fields)) {
                if ($this->isCrmDirectorFlagOn($fields['UF_IS_DIRECTOR'])) {
                // Получаем компанию пользователя
                $rsCompany = \CIBlockElement::GetList(
                    [],
                    [
                        'IBLOCK_ID' => 57,
                        'PROPERTY_OS_COMPANY_USERS' => $this->userId,
                        'ACTIVE' => 'Y'
                    ],
                    false,
                    false,
                    ['ID', 'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING', 'PROPERTY_OS_HOLDING_OF']
                );

                $userCompany = $rsCompany->GetNext();
                $companyIds = [];

                if ($userCompany) {
                    // Проверяем, является ли компания головной холдинга
                    if (!empty($userCompany['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE']) &&
                        ($userCompany['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'] === 'Y' ||
                            $userCompany['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'] === 'Да')) {

                        // Сценарий 1: Головная компания - получаем все компании холдинга
                        $rsHoldingCompanies = \CIBlockElement::GetList(
                            [],
                            [
                                'IBLOCK_ID' => 57,
                                'PROPERTY_OS_HOLDING_OF' => $userCompany['ID'],
                                'ACTIVE' => 'Y'
                            ],
                            false,
                            false,
                            ['ID']
                        );  

                        while ($holdingCompany = $rsHoldingCompanies->GetNext()) {
                            $companyIds[] = $holdingCompany['ID'];
                        }

                        // Добавляем саму головную компанию
                        $companyIds[] = $userCompany['ID'];

                    } else if (!empty($userCompany['PROPERTY_OS_HOLDING_OF_VALUE'])) {

                        // Сценарий 2: Обычная компания - получаем все компании того же холдинга
                        $holdingId = $userCompany['PROPERTY_OS_HOLDING_OF_VALUE'];

                        // Получаем все компании этого холдинга
                        $rsHoldingCompanies = \CIBlockElement::GetList(
                            [],
                            [
                                'IBLOCK_ID' => 57,
                                'PROPERTY_OS_HOLDING_OF' => $holdingId,
                                'ACTIVE' => 'Y'
                            ],
                            false,
                            false,
                            ['ID']
                        );

                        while ($holdingCompany = $rsHoldingCompanies->GetNext()) {
                            $companyIds[] = $holdingCompany['ID'];
                        }

                        // Добавляем головную компанию холдинга
                        $companyIds[] = $holdingId;

                    } else {
                        // Если нет связей с холдингом - только своя компания
                        $companyIds[] = $userCompany['ID'];
                    }
                }

                if( $companyIds ){
                    // Обновляем руководителя у всех
                    foreach ($companyIds as $companyId){
                        $el = new \CIBlockElement;
                        $companyUpdated = $el->SetPropertyValues($companyId, 57,[$this->userId],"OS_COMPANY_BOSS");
                    }
                }
                
                // Добавляем пользователя в группу руководителей (ID: 432)
                $cur = $this->normalizeUserGroupIds(\CUser::GetUserGroup($this->userId));
                if (!in_array($this->DIRECTOR_GROUP_ID, $cur, true)) {
                    \CUser::SetUserGroup($this->userId, $this->normalizeUserGroupIds(array_merge($cur, [$this->DIRECTOR_GROUP_ID])));
                }
                } else {
                // Убираем из группы руководителей только если CRM явно прислала UF_IS_DIRECTOR (частичный payload без ключа не трогает 432).
                $cur = $this->normalizeUserGroupIds(\CUser::GetUserGroup($this->userId));
                if (in_array($this->DIRECTOR_GROUP_ID, $cur, true)) {
                    $new = array_values(array_diff($cur, [$this->DIRECTOR_GROUP_ID]));
                    $new = $this->ensureCompanyDiscountGroupsPreserved($cur, $new);
                    \CUser::SetUserGroup($this->userId, $new);
                }
                }
            }

            // Внешний payload (B24 → ajax) не должен перезаписывать членство в группах напрямую.
            unset($fields['GROUP_ID'], $fields['GROUPS_ID']);

            $currentUser = \CUser::GetByID((int)$this->userId)->Fetch();
            $changedFields = [];
            $diffIsUnknown = !\is_array($currentUser);
            if (\is_array($currentUser)) {
                foreach ($fields as $fieldKey => $newValue) {
                    if (!$this->isUserFieldComparableForEffect((string)$fieldKey)) {
                        continue;
                    }
                    $oldValue = $currentUser[$fieldKey] ?? null;
                    if (!$this->isSameUserFieldValue($oldValue, $newValue)) {
                        $changedFields[] = (string)$fieldKey;
                    }
                }
            } else {
                // Не удалось прочитать текущего пользователя: diff неизвестен, но update всё равно обязателен.
                $this->lastUpdateMeta['diff_is_unknown'] = true;
                $this->lastUpdateMeta['changed_fields_count'] = null;
            }
            $changedFields = \array_values(\array_unique($changedFields));
            $this->lastUpdateMeta['changed_fields'] = $changedFields;
            if (!$diffIsUnknown) {
                $this->lastUpdateMeta['changed_fields_count'] = \count($changedFields);
            }
            if (!$diffIsUnknown && $this->lastUpdateMeta['changed_fields_count'] <= 0) {
                $this->lastUpdateMeta['updated'] = false;
                $this->lastUpdateMeta['no_effect_reason'] = 'update_contact_no_effect';
                return true;
            }

            $result = $user->Update($this->userId, $fields);

            if ($result) {
                $this->lastUpdateMeta['updated'] = true;
                $this->lastUpdateMeta['no_effect_reason'] = null;
                return true;
            } else {
                $this->lastUpdateFailReason = 'update_contact_cuser_update_failed';
                $this->lastUpdateMeta['updated'] = false;
                return false;
            }
        }

        /**
         * Dual-read inbound manager compatibility:
         * primary key + legacy fallback key; missing keys do not touch UF_MANAGER/UF_MANAGER2.
         */
        private function applyInboundManagerMapping(
            array &$fields,
            string $userManagerField,
            string $primaryInboundField,
            string $legacyInboundField
        ): void {
            $hasPrimary = array_key_exists($primaryInboundField, $fields);
            $hasLegacy = array_key_exists($legacyInboundField, $fields);

            if (!$hasPrimary && !$hasLegacy) {
                return;
            }

            $rawManagerValue = $hasPrimary ? $fields[$primaryInboundField] : $fields[$legacyInboundField];
            $resolvedManagerId = $this->resolveManagerElementId($rawManagerValue);

            // Без валидного маппинга не перезаписываем текущий UF-менеджер.
            if ($resolvedManagerId !== null) {
                $fields[$userManagerField] = $resolvedManagerId;
            } else {
                $this->logManagerMappingIssue($userManagerField, $rawManagerValue, $primaryInboundField, $legacyInboundField);
            }

            unset($fields[$primaryInboundField], $fields[$legacyInboundField]);
        }

        private function logManagerMappingIssue(
            string $userManagerField,
            mixed $rawManagerValue,
            string $primaryInboundField,
            string $legacyInboundField
        ): void {
            if (!class_exists('\CEventLog')) {
                return;
            }

            \CEventLog::Add([
                'SEVERITY' => 'WARNING',
                'AUDIT_TYPE_ID' => 'USERSYNC_MANAGER_MAPPING_INVALID',
                'MODULE_ID' => 'main',
                'ITEM_ID' => (string)$this->userId,
                'DESCRIPTION' => sprintf(
                    'Inbound manager mapping failed: target=%s, primary=%s, legacy=%s, value=%s',
                    $userManagerField,
                    $primaryInboundField,
                    $legacyInboundField,
                    is_scalar($rawManagerValue) ? (string)$rawManagerValue : json_encode($rawManagerValue, JSON_UNESCAPED_UNICODE)
                ),
            ]);
        }

        private function resolveManagerElementId(mixed $rawManagerValue): ?int
        {
            if ($rawManagerValue === null) {
                return null;
            }

            if (is_string($rawManagerValue)) {
                $rawManagerValue = trim($rawManagerValue);
                if ($rawManagerValue === '') {
                    return null;
                }
            }

            $managerId = $this->getManagerID($rawManagerValue);
            if ($managerId === false) {
                return null;
            }

            return (int)$managerId;
        }

        public function updateBatch($fields){
            // Проверяем обязательные поля
            if (empty($fields['CONTACT_IDS'])) {
                return false;
            }

            foreach ($fields['CONTACT_IDS'] as $b24Id){
                $userId = $this->getUserIDByB24ID($b24Id);

                if( $userId )
                    $this->updateMarketingAgentPriceType($fields['IS_MARKETING_AGENT'],$userId);
            }
        }

        /**
         * Returns CRM contact ID (UF_B24_USER_ID) for a site user.
         */
        private function getUserB24IdBySiteUserId(int $siteUserId): ?string
        {
            if ($siteUserId <= 0) {
                return null;
            }
            $rsUser = \CUser::GetList(
                [],
                'asc',
                ['ID' => $siteUserId],
                ['SELECT' => ['ID', 'UF_B24_USER_ID']]
            );
            $userObject = $rsUser->Fetch();
            if (!\is_array($userObject)) {
                return null;
            }
            if (!isset($userObject['UF_B24_USER_ID'])) {
                return null;
            }
            return \trim((string)$userObject['UF_B24_USER_ID']);
        }

        /**
         * Deterministic inbound ID resolution chain:
         * B24_ID -> UF_CRM_3804624445748 -> legacy ID.
         *
         * @param array<string,mixed> $fields
         * @return array{
         *   ok: bool,
         *   site_user_id: int|null,
         *   lookup_b24_id: string|null,
         *   id_resolution: array{source:?string,status:string,used_fallback:bool,reason_code:string}
         * }
         */
        private function resolveUpdateContactIdentity(array $fields): array
        {
            $b24Id = $this->normalizeInboundContactId($fields['B24_ID'] ?? null);
            $siteUserUfId = $this->normalizeInboundPositiveInt($fields[self::INBOUND_SITE_USER_ID_UF] ?? null);
            $legacySiteUserId = $this->normalizeInboundPositiveInt($fields['ID'] ?? null);

            $baseResolution = [
                'source' => null,
                'status' => 'failed',
                'used_fallback' => false,
                'reason_code' => 'update_contact_missing_identifier',
            ];

            if ($b24Id !== null) {
                $uid = (int)$this->getUserIDByB24ID($b24Id);
                if ($uid > 0) {
                    return [
                        'ok' => true,
                        'site_user_id' => $uid,
                        'lookup_b24_id' => $b24Id,
                        'id_resolution' => [
                            'source' => 'b24_id',
                            'status' => 'resolved',
                            'used_fallback' => false,
                            'reason_code' => 'update_contact_ok',
                        ],
                    ];
                }
            }

            $fallbackCandidates = [];
            if ($siteUserUfId !== null && $this->siteUserExists($siteUserUfId)) {
                $fallbackCandidates['uf_crm_3804624445748'] = $siteUserUfId;
            }
            if ($legacySiteUserId !== null && $this->siteUserExists($legacySiteUserId)) {
                $fallbackCandidates['legacy_id'] = $legacySiteUserId;
            }

            if (\count($fallbackCandidates) > 1) {
                $uniqueSiteUsers = \array_values(\array_unique(\array_values($fallbackCandidates)));
                if (\count($uniqueSiteUsers) > 1) {
                    return [
                        'ok' => false,
                        'site_user_id' => null,
                        'lookup_b24_id' => $b24Id,
                        'id_resolution' => [
                            'source' => null,
                            'status' => 'ambiguous',
                            'used_fallback' => true,
                            'reason_code' => 'update_contact_ambiguous_fallback',
                        ],
                    ];
                }
            }

            if (isset($fallbackCandidates['uf_crm_3804624445748'])) {
                return [
                    'ok' => true,
                    'site_user_id' => $fallbackCandidates['uf_crm_3804624445748'],
                    'lookup_b24_id' => $b24Id,
                    'id_resolution' => [
                        'source' => 'uf_crm_3804624445748',
                        'status' => 'resolved',
                        'used_fallback' => true,
                        'reason_code' => 'update_contact_ok_site_user_fallback',
                    ],
                ];
            }
            if (isset($fallbackCandidates['legacy_id'])) {
                return [
                    'ok' => true,
                    'site_user_id' => $fallbackCandidates['legacy_id'],
                    'lookup_b24_id' => $b24Id,
                    'id_resolution' => [
                        'source' => 'legacy_id',
                        'status' => 'resolved',
                        'used_fallback' => true,
                        'reason_code' => 'update_contact_ok_legacy_fallback',
                    ],
                ];
            }

            if ($b24Id !== null || $siteUserUfId !== null || $legacySiteUserId !== null) {
                $baseResolution['reason_code'] = 'update_contact_user_not_found';
            }

            return [
                'ok' => false,
                'site_user_id' => null,
                'lookup_b24_id' => $b24Id,
                'id_resolution' => $baseResolution,
            ];
        }

        /**
         * @param mixed $value
         */
        private function normalizeInboundContactId($value): ?string
        {
            if (!\is_scalar($value)) {
                return null;
            }
            $normalized = \trim((string)$value);
            if ($normalized === '' || $normalized === '0') {
                return null;
            }

            return $normalized;
        }

        /**
         * @param mixed $value
         */
        private function normalizeInboundPositiveInt($value): ?int
        {
            if (!\is_scalar($value)) {
                return null;
            }
            $normalized = \trim((string)$value);
            if ($normalized === '' || $normalized === '0' || !\ctype_digit($normalized)) {
                return null;
            }
            $id = (int)$normalized;
            if ($id <= 0) {
                return null;
            }

            return $id;
        }

        private function siteUserExists(int $siteUserId): bool
        {
            if ($siteUserId <= 0) {
                return false;
            }
            $user = \CUser::GetByID($siteUserId)->Fetch();

            return \is_array($user) && isset($user['ID']) && (int)$user['ID'] > 0;
        }

        public function delete($fields){
            $this->lastDeleteFailReason = null;

            $candidates = $this->collectInboundDeleteContactLookupCandidates($fields);
            if ($candidates === []) {
                $this->lastDeleteFailReason = 'delete_contact_missing_identifier';

                return false;
            }

            $this->userId = 0;
            foreach ($candidates as $crmContactId) {
                $uid = $this->getUserIDByB24ID($crmContactId);
                if ($uid) {
                    $this->userId = (int)$uid;
                    break;
                }
            }
            if ($this->userId <= 0) {
                $this->lastDeleteFailReason = 'delete_contact_user_not_found';

                return false;
            }

            $deleted = (bool)\CUser::Delete($this->userId);
            if (!$deleted) {
                $this->lastDeleteFailReason = 'delete_contact_cuser_delete_failed';
            }

            return $deleted;
        }

        /**
         * Кандидаты CRM CONTACT.ID для поиска `b_user.UF_B24_USER_ID` при DELETE_CONTACT.
         * Сначала канонический `B24_ID`, затем легаси `ID` — на сайте в UF может быть
         * другой из двух идентификаторов, чем тот, что пришёл в приоритетном поле.
         *
         * @param array<string, mixed> $fields
         * @return list<string>
         */
        private function collectInboundDeleteContactLookupCandidates(array $fields): array
        {
            $out = [];
            foreach (['B24_ID', 'ID'] as $key) {
                if (!isset($fields[$key])) {
                    continue;
                }
                $v = $fields[$key];
                if (!\is_scalar($v)) {
                    continue;
                }
                $s = \trim((string)$v);
                if ($s === '' || $s === '0') {
                    continue;
                }
                if (!\in_array($s, $out, true)) {
                    $out[] = $s;
                }
            }

            return $out;
        }

        public function getMarketingGroupId(){
            return $this->MARKETING_AGENT_GROUP_ID;
        }

        public function getLastUpdateFailReason(): ?string
        {
            return $this->lastUpdateFailReason;
        }

        /**
         * @return array<string,mixed>
         */
        public function getLastUpdateMeta(): array
        {
            return $this->lastUpdateMeta;
        }

        public function getLastDeleteFailReason(): ?string
        {
            return $this->lastDeleteFailReason;
        }

        /**
         * Получить головную компанию холдинга, где пользователь является руководителем
         * 
         * @param int|null $userId ID пользователя (если не указан, используется текущий)
         * @return array|false Данные головной компании или false если не найдена
         */
        public function getHeadCompany($userId = null) {
            if ($userId === null) {
                $userId = $this->userId;
            }

            if (empty($userId)) {
                return false;
            }

            // Ищем головную компанию холдинга, где пользователь является руководителем
            $filter = [
                'IBLOCK_ID' => 57,
                'PROPERTY_OS_COMPANY_BOSS' => $userId,
                'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING' => 31520, // Константа для головной компании холдинга
                'ACTIVE' => 'Y'
            ];

            // Получаем головную компанию холдинга пользователя
            $rsCompany = \CIBlockElement::GetList(
                [],
                $filter,
                false,
                false,
                [
                    'ID', 
                    'NAME',
                    'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING', 
                    'PROPERTY_OS_HOLDING_OF',
                    'PROPERTY_OS_COMPANY_B24_ID',
                    'PROPERTY_OS_HEAD_COMPANY_B24_ID'
                ]
            );

            if ($company = $rsCompany->GetNext()) {
                return $company;
            }

            return false;
        }

        /**
         * Получить любую компанию пользователя (руководитель или сотрудник)
         * 
         * @param int|null $userId ID пользователя (если не указан, используется текущий)
         * @param string $userRole Роль пользователя: 'boss' - руководитель, 'user' - обычный пользователь
         * @return array|false Данные компании или false если не найдена
         */
        public function getUserCompany($userId = null, $userRole = 'boss', $companyId = null) {
            if ($userId === null) {
                $userId = $this->userId;
            }

            if (empty($userId)) {
                return false;
            }

            // Определяем фильтр в зависимости от роли
            $filter = [
                'IBLOCK_ID' => 57,
                'ACTIVE' => 'Y'
            ];

            if ($userRole === 'boss') {
                $filter['PROPERTY_OS_COMPANY_BOSS'] = $userId;
            } else {
                $filter['PROPERTY_OS_COMPANY_USERS'] = $userId;
            }

            if (!is_null($companyId)) {
                $companyId = (int)$companyId;

                if ($companyId <= 0) {
                    return false;
                }

                $filter['ID'] = $companyId;
            }

            // Получаем компанию пользователя
            $rsCompany = \CIBlockElement::GetList(
                [],
                $filter,
                false,
                false,
                [
                    'ID', 
                    'NAME',
                    'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING', 
                    'PROPERTY_OS_HOLDING_OF',
                    'PROPERTY_OS_COMPANY_B24_ID',
                    'PROPERTY_OS_HEAD_COMPANY_B24_ID'
                ]
            );

            if ($company = $rsCompany->GetNext()) {
                return $company;
            }

            return false;
        }

        /**
         * Проверить, является ли пользователь руководителем головной компании холдинга
         * 
         * @param int|null $userId ID пользователя (если не указан, используется текущий)
         * @return bool true если пользователь руководитель головной компании холдинга
         */
        public function isCompanyBoss($userId = null) {
            $company = $this->getHeadCompany($userId);
            return $company !== false;
        }

        /**
         * Получить ID головной компании холдинга для пользователя
         * 
         * @param int|null $userId ID пользователя (если не указан, используется текущий)
         * @return int|false ID головной компании холдинга или false если не найдена
         */
        public function getHeadCompanyId($userId = null) {
            $company = $this->getHeadCompany($userId);
            
            if (!$company) {
                return false;
            }

            // Если это головная компания холдинга
            if (!empty($company['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE']) && 
                ($company['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'] === 'Y' || 
                 $company['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'] === 'Да')) {
                
                return $company['PROPERTY_OS_HEAD_COMPANY_B24_ID_VALUE'] ?: $company['PROPERTY_OS_COMPANY_B24_ID_VALUE'];
            }
            
            // Если это дочерняя компания в холдинге
            if (!empty($company['PROPERTY_OS_HOLDING_OF_VALUE'])) {
                return $company['PROPERTY_OS_HOLDING_OF_VALUE'];
            }

            // Если нет связей с холдингом - возвращаем ID самой компании
            return $company['PROPERTY_OS_COMPANY_B24_ID_VALUE'];
        }

        /**
         * Трактовка UF_IS_DIRECTOR из CRM (как у рекламного агента): не использовать «PHP truthy» для строк вроде 'N'.
         */
        private function isCrmDirectorFlagOn(mixed $v): bool
        {
            return $v === true || $v === 1 || $v === '1' || $v === 'Y' || $v === 'y' || $v === 'Да';
        }

        private function isUserFieldComparableForEffect(string $fieldName): bool
        {
            if ($fieldName === '') {
                return false;
            }

            if (\in_array($fieldName, ['ACTION', 'ID', 'XML_ID', 'OS_COMPANY_B24_ID', 'OS_HEAD_COMPANY_B24_ID'], true)) {
                return false;
            }

            return true;
        }

        private function isSameUserFieldValue(mixed $oldValue, mixed $newValue): bool
        {
            $normalize = static function (mixed $v): string {
                if (\is_bool($v)) {
                    return $v ? '1' : '0';
                }
                if (\is_int($v) || \is_float($v)) {
                    return (string)$v;
                }
                if (\is_string($v)) {
                    return \trim($v);
                }
                if ($v === null) {
                    return '';
                }

                return \json_encode($v, \JSON_UNESCAPED_UNICODE | \JSON_INVALID_UTF8_SUBSTITUTE) ?: '';
            };

            return $normalize($oldValue) === $normalize($newValue);
        }

        /**
         * @param array<int|string|mixed> $ids
         * @return list<int>
         */
        private function normalizeUserGroupIds(array $ids): array
        {
            $out = [];
            foreach ($ids as $id) {
                $id = (int)$id;
                if ($id > 0) {
                    $out[$id] = $id;
                }
            }

            return array_values($out);
        }

        /**
         * Не терять скидочные группы компании (ID из CompanyModuleConfig) при пересборке списка для SetUserGroup.
         *
         * @param list<int> $before
         * @param list<int> $after
         * @return list<int>
         */
        private function ensureCompanyDiscountGroupsPreserved(array $before, array $after): array
        {
            $discountIds = array_keys(CompanyModuleConfig::getCompanyDiscountPercentByAssignedGroupId());
            if ($discountIds === []) {
                return $this->normalizeUserGroupIds($after);
            }

            $afterMap = array_fill_keys($this->normalizeUserGroupIds($after), true);
            foreach ($this->normalizeUserGroupIds($before) as $gid) {
                if (!in_array($gid, $discountIds, true)) {
                    continue;
                }
                if (!isset($afterMap[$gid])) {
                    $after[] = $gid;
                    $afterMap[$gid] = true;
                }
            }

            return $this->normalizeUserGroupIds($after);
        }
    }