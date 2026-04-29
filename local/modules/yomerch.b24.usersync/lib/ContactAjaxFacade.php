<?php

namespace OnlineService\B24\UserSync;

use OnlineService\B24\User;

/**
 * Публичная граница для экшенов `local/classes/ajax.php`, связанных с контактом CRM.
 * Контракт ответов для фронта сохраняется как у делегируемого класса User.
 */
final class ContactAjaxFacade
{
    public static function updateContact(array $request)
    {
        $user = new User();

        return $user->update($request);
    }

    public static function updateBatchUsers(array $request)
    {
        $user = new User();

        return $user->updateBatch($request);
    }

    public static function deleteContact(array $request)
    {
        $user = new User();

        return $user->delete($request);
    }
}
