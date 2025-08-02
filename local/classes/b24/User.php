<?php
    namespace OnlineService\B24;
    use OnlineService\B24\Request;
    class User extends Request{
        public ?int $contactId = null;
        public function __construct()
        {
        }

        public function getContactID($arFields,$returnAll = false){
            $arFields = array_merge($arFields,[
                "ACTION" => 'GET_CONTACT_ID',
                "SORT" => 'ID',
                "ORDER" => 'asc',
            ]);

            // найти пользователя в б24 по EMAIL
            $response = $this->sendRequest($arFields);

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

        public function isUserRegistered($arFields){
            return $this->getContactID([
                'EMAIL' => $arFields['EMAIL'],
                'PHONE' => $arFields['PERSONAL_PHONE']
            ],true);
        }

        /**
         * Получить ID пользователя на сайте по ID контакта в B24
         * 
         * @param int $b24ContactId ID контакта в B24
         * @return int|false ID пользователя на сайте или false если не найден
         */
        private function getUserIDByB24ID($b24ContactId){
            if (empty($b24ContactId)) {
                pre("Error: B24 Contact ID is required");
                return false;
            }

            // Ищем пользователя по полю UF_B24_USER_ID
            $rsUser = \CUser::GetList(
                array(), 
                $order = "asc", 
                array('UF_B24_USER_ID' => $b24ContactId),
                array('SELECT' => array('ID', 'UF_B24_USER_ID'))
            );

            if ($userObject = $rsUser->Fetch()) {
                return $userObject['ID'];
            } else {
                pre("User not found for B24 contact ID: " . $b24ContactId);
                return false;
            }
        }
        public function getUserObject($userId){

            $rsUser = \CUser::GetList(
                array(), 
                $order = "asc", 
                array('ID' => $userId),
                array('SELECT' => array('UF_B24_USER_ID'))
            );

            if( $userObject = $rsUser->Fetch() ){
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
                pre("Error: Contact ID is required");
                return false;
            }

            // Получаем данные пользователя из B24 для обновления
            $arFields = [
                'ACTION' => 'UPDATE_CONTACT',
                'ID' => $contactId
            ];

            /*$response = $this->sendRequest($arFields);

            if ($response['success'] == 1) {
                pre("Contact data retrieved from B24: " . print_r($response['data'], true));
                return $response['data'];
            } else {
                pre("Error getting contact data from B24: " . print_r($response, true));
                return false;
            }*/
        }

        public function OnAfterUserUpdateHandler($arFields){
            $userObject = $this->getUserObject($arFields['ID']);

            if( $userObject )
                $this->updateContact($userObject['CONTACT_ID']);

            return true;
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
            // Проверяем обязательные поля
            if (empty($fields['B24_ID'])) {
                pre("Error: B24 Contact ID is required for user update");
                return false;
            }

            $b24ID = $fields['B24_ID'];
            // Убираем ID из полей для обновления
            unset($fields['B24_ID']);

            $userId = $this->getUserIDByB24ID($b24ID);
            
            if (!$userId) {
                pre("Error: User not found for B24 contact ID: " . $b24ID);
                return false;
            }

            // Обновляем пользователя на сайте
            $user = new \CUser();
            $result = $user->Update($userId, $fields);

            if ($result) {
                pre("User updated successfully on site");
                return true;
            } else {
                pre("Error updating user on site: " . $user->LAST_ERROR);
                return false;
            }
        }
    }