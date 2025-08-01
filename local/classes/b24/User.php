<?php
    namespace OnlineService\B24;
    use OnlineService\B24\Request;
    class User extends Request{
        public ?int $contactId = null;
        public function __construct()
        {
        }

        public function getContactID($arFields){
            // найти пользователя в б24 по EMAIL
            $user = $this->newB24Rest("EMAIL", $arFields, "EMAIL");
            // найти пользователя в б24 по Телефону
            if (empty($arResult)){
                $user = $this->newB24Rest("PHONE", $arFields, "PERSONAL_PHONE");
            }

            return $user;
        }


        public function updateContact(){

        }


        public function OnBeforeUserDeleteHandler($userId){
            $userObject = $this->getUserObject($userId);

            $email = $userObject['$userObject'];
            $phone = $userObject['PERSONAL_PHONE'];

            $contactId = $this->getContactID([
                'EMAIL' => $email,
                'PHONE' => $phone
            ]);

            pre($contactId);
            die();
        }

        private function getUserObject($userId){

            $rsUser = \CUser::GetList(array(), $order = "asc", array(
                'ID' => $userId
            ));

            return $rsUser->Fetch() ?? false;
        }
    }