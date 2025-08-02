<?php
    // \OnlineService\B24\User
    AddEventHandler("main", "OnBeforeUserDelete", "OnBeforeUserDeleteHandler");
    function OnBeforeUserDeleteHandler($userId){
        $user = new \OnlineService\B24\User();

        $user->OnBeforeUserDeleteHandler($userId);
    }

    // \OnlineService\B24\RegisterUserCompany
    AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterHandler");

    AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");

    function OnBeforeUserRegisterHandler(&$arFields){
        pre("OnBeforeUserRegisterHandler called");
        $registerUserCompany = new \OnlineService\B24\RegisterUserCompany();

        $result = $registerUserCompany->OnBeforeUserRegisterHandler($arFields);
        pre("OnBeforeUserRegisterHandler result: " . var_export($result, true));
        return $result;
    }
    function OnAfterUserRegisterHandler(&$arFields){
        $registerUserCompany = new \OnlineService\B24\RegisterUserCompany();

        $registerUserCompany->OnAfterUserRegisterHandler($arFields);
    }