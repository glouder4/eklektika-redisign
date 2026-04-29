<?php

namespace OnlineService\B24\UserSync;

use OnlineService\Events\SyncEventHandlers;

/**
 * Регистрация событий main для синхронизации пользователя сайта с контактом CRM.
 * Перенесено из local/classes/events.php (модуль yomerch.b24.usersync).
 */
final class UserSyncBootstrap
{
    public static function register(): void
    {
        \AddEventHandler('main', 'OnBeforeUserDelete', [SyncEventHandlers::class, 'onBeforeUserDelete']);
        \AddEventHandler('main', 'OnBeforeUserAdd', [SyncEventHandlers::class, 'onBeforeUserAdd']);
        \AddEventHandler('main', 'OnAfterUserAdd', [SyncEventHandlers::class, 'onAfterUserAdd']);
        \AddEventHandler('main', 'OnAfterUserUpdate', [SyncEventHandlers::class, 'onAfterUserUpdate']);
    }
}
