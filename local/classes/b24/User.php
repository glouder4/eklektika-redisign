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


        public function updateContact(){

        }

        private function deleteContact($contactId){
            $arFields = [
                'ACTION' => "DELETE_CONTACT",
                'ID' => $contactId
            ];

            // найти пользователя в б24 по EMAIL
            $response = $this->sendRequest($arFields);

            if( !$response['success'] ){
                global $APPLICATION;
                $APPLICATION->ThrowException($response);

                return false;
            }
        }


        public function OnBeforeUserDeleteHandler($userId){
            $userObject = $this->getUserObject($userId);

            $email = $userObject['EMAIL'];
            $phone = $userObject['PERSONAL_PHONE'];

            $contactId = $this->getContactID([
                'EMAIL' => $email,
                'PHONE' => $phone
            ]);
            if( $contactId )
                $this->deleteContact($contactId);
        }

        private function getUserObject($userId){

            $rsUser = \CUser::GetList(array(), $order = "asc", array(
                'ID' => $userId
            ));

            return $rsUser->Fetch() ?? false;
        }
    }