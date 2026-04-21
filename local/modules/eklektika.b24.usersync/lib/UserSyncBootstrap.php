<?php

namespace OnlineService\B24\UserSync;

/**
 * Регистрация событий main для синхронизации пользователя сайта с контактом CRM.
 * Перенесено из local/classes/events.php (модуль eklektika.b24.usersync).
 */
final class UserSyncBootstrap
{
    public static function register(): void
    {
        \AddEventHandler('main', 'OnBeforeUserDelete', [self::class, 'handleBeforeUserDelete']);
        \AddEventHandler('main', 'OnBeforeUserRegister', [self::class, 'handleBeforeUserRegister']);
        \AddEventHandler('main', 'OnAfterUserRegister', [self::class, 'handleAfterUserRegister']);
        \AddEventHandler('main', 'OnAfterUserUpdate', [self::class, 'handleAfterUserUpdate']);
    }

    public static function handleBeforeUserDelete($userId): void
    {
        $user = new \OnlineService\B24\User();
        $user->OnBeforeUserDeleteHandler($userId);
    }

    /**
     * @return mixed
     */
    public static function handleBeforeUserRegister(&$arFields)
    {
        $registerUserCompany = new \OnlineService\B24\RegisterUserCompany();

        return $registerUserCompany->OnBeforeUserRegisterHandler($arFields);
    }

    public static function handleAfterUserRegister(&$arFields): void
    {
        $registerUserCompany = new \OnlineService\B24\RegisterUserCompany();
        $registerUserCompany->OnAfterUserRegisterHandler($arFields);
    }

    public static function handleAfterUserUpdate(&$arFields): void
    {
        if (!empty($arFields['RESULT'])) {
            $userObj = new \OnlineService\B24\User();
            $userObj->OnAfterUserUpdateHandler($arFields);
        }
    }
}
