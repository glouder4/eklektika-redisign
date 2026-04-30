<?php
 
declare(strict_types=1);

namespace OnlineService\Sync\FromCrm;

use OnlineService\Site\Config\CompanyModuleConfig;

/**
 * Коды пользовательских полей CRM (Bitrix24), приходящие во входящем payload на сайт,
 * и привязка к полям сайта. Расширять по мере согласования с порталом.
 *
 * @see bitrix-docker/www/local/sync/docs/functional-contract.md
 */
final class CrmInboundUfMap
{
    // --- Контакт (crm.contact), ключи как в REST/исходящем Updater на сайт ---

    /** «Рекламный агент» на контакте → на сайте хранится в UF пользователя (опечатка в коде сайта сохранена). */
    public const CONTACT_ADVERTISING_AGENT_UF = 'UF_CRM_1775034008956';

    /** «Руководитель компании» (контакт CRM) → на сайте `b_user.UF_IS_DIRECTOR`. */
    public const CONTACT_IS_DIRECTOR_UF = 'UF_CRM_1777068292434';

    // --- Компания (crm.company), для обработчиков UPDATE_COMPANY / реквизитов (пока только константы) ---

    /** Это рекламный агент (legacy UF; при наличии {@see self::COMPANY_OS_IS_MARKETING_AGENT_UF} приоритет у него). */
    public const COMPANY_IS_ADVERTISING_AGENT_UF = 'UF_CRM_1774915252680';

    /**
     * CRM UF «маркетинговый агент» компании → на сайте свойство `OS_IS_MARKETING_AGENT` и элемент `ACTIVE` (Y/N).
     */
    public const COMPANY_OS_IS_MARKETING_AGENT_UF = 'UF_CRM_1675675211485';

    /** Является головной компанией холдинга */
    public const COMPANY_IS_HEAD_OF_HOLDING_UF = 'UF_CRM_1775030726726';

    /**
     * CRM UF (флажок) → свойство списка `OS_COMPANY_IS_HEAD_OF_HOLDING` на ИБ компаний.
     * При совпадении с {@see self::COMPANY_IS_HEAD_OF_HOLDING_UF} в запросе приоритет у поля, объявленного раньше в обработчике.
     */
    public const COMPANY_OS_COMPANY_IS_HEAD_OF_HOLDING_CHECKBOX_UF = 'UF_CRM_1758028888';

    /**
     * Холдинг (связь с головной компанией).
     * Внимание: при несовпадении с порталом проверьте полный код поля в CRM и поправьте константу.
     */
    public const COMPANY_HOLDING_UF = 'UF_CRM_1775032393';

    /**
     * CRM UF: значение списка скидки (1014…1021) → группа на сайте через {@see CompanyModuleConfig::getCompanyStatusGroupIdMap()}.
     */
    public const COMPANY_DISCOUNT_CRM_LIST_UF = 'UF_CRM_1771490556028';

    /** Легаси: то же назначение; если в теле есть {@see self::COMPANY_DISCOUNT_CRM_LIST_UF}, он имеет приоритет. */
    public const COMPANY_DISCOUNT_UF = 'UF_CRM_1777030197';

    /** Фирмы холдинга (множественное поле CRM компании). */
    public const COMPANY_HOLDING_COMPANIES_UF = 'UF_CRM_1777030108';

    /**
     * Множественная привязка «члены группы холдинга» на портале B24 перед inbound (может использоваться параллельно с {@see self::COMPANY_HOLDING_COMPANIES_UF}).
     * Состав списка на стороне CRM должен включать только компании с общей «головой» по полю связи филиала: {@see \OnlineService\Site\Config\CompanyB24Config::HEAD_COMPANY_B24_LINK_FIELD}.
     * Спецификация отбора членов группы см. `local/docs/bitrix24-external-developers/b24_company_holding_group_members_resolve.md`.
     */
    public const COMPANY_HOLDING_GROUP_MEMBERS_UF = 'UF_CRM_1776426878';

    /** ID элемента инфоблока на сайте (связь компании CRM ↔ карточка на сайте) */
    public const COMPANY_SITE_IBLOCK_ELEMENT_ID_UF = 'UF_CRM_1774915439581';

    /**
     * ID пользователей сайта (b_user.ID) в payload UPDATE_COMPANY с CRM.
     * Код поля совпадает с {@see \OnlineService\B24\UserSync\Config\RegisterUserCompanyConfig::CRM_CONTACT_SITE_USER_ID_FIELD} на контакте.
     */
    public const COMPANY_SITE_USER_IDS_UF = 'UF_CRM_1776075126830';

    /** crm.company: основной телефон (UF) → свойство элемента {@see \OnlineService\Site\Company} `LEGAN_MAIN_PHONE` при UPDATE_COMPANY. */
    public const COMPANY_CRM_MAIN_PHONE_UF = 'UF_CRM_1777069666894';

    /** crm.company: мобильный телефон (UF) → `LEGAN_MOBILE_PHONE` при UPDATE_COMPANY. */
    public const COMPANY_CRM_MOBILE_PHONE_UF = 'UF_CRM_1777069676348';

    /** crm.company: город → `OS_COMPANY_CITY` (+ зеркало `LEGAN_ENTITY_CITY`) при UPDATE_COMPANY. */
    public const COMPANY_CRM_CITY_UF = 'UF_CRM_1775034571084';

    /** crm.company: веб-сайт → `OS_COMPANY_WEB_SITE` (+ зеркало `LEGAN_ENTITY_WWW`) при UPDATE_COMPANY. */
    public const COMPANY_CRM_WEB_SITE_UF = 'UF_CRM_1777119084064';

    /** crm.company: сфера деятельности → `OS_COMPANY_ACTIVITY` (+ зеркало `LEGAN_ENTITY_ACTIVITY`) при UPDATE_COMPANY. */
    public const COMPANY_CRM_ACTIVITY_UF = 'UF_CRM_1777119807943';

    /** crm.company: юридический адрес → `OS_COMPANY_JUR_ADDRESS` (+ зеркало `LEGAN_ENTITY_ADRESS`) при UPDATE_COMPANY. */
    public const COMPANY_CRM_JUR_ADDRESS_UF = 'UF_CRM_1777120939583';

    /**
     * Сырое значение для синхронизации группы рекламного агента (до нормализации в int для b_user).
     */
    public static function peekMarketingAgentRawValue(array $fields): mixed
    {
        if (\array_key_exists('IS_MARKETING_AGENT', $fields)) {
            $v = $fields['IS_MARKETING_AGENT'];
            if (self::isMarketingPayloadAbsent($v)) {
                return null;
            }

            return $v;
        }
        if (\array_key_exists(self::CONTACT_ADVERTISING_AGENT_UF, $fields)) {
            $v = $fields[self::CONTACT_ADVERTISING_AGENT_UF];
            // Пустая строка с CRM = «не менять маркетинг», а не «выключить агента»
            if (self::isMarketingPayloadAbsent($v)) {
                return null;
            }

            return $v;
        }
        if (\array_key_exists('UF_ADVERSTERING_AGENT', $fields)) {
            return $fields['UF_ADVERSTERING_AGENT'];
        }

        return null;
    }

    /** null / пустая строка / только пробелы — не считаем явным значением для синхронизации агента */
    private static function isMarketingPayloadAbsent(mixed $v): bool
    {
        if ($v === null) {
            return true;
        }
        if (\is_string($v) && \trim($v) === '') {
            return true;
        }

        return false;
    }

    /**
     * Публичная обёртка для канала CRM → сайт и зеркального to-crm (сборка POST на портале).
     */
    public static function marketingInboundSignalAbsent(mixed $v): bool
    {
        return self::isMarketingPayloadAbsent($v);
    }

    /**
     * Явное «да» для маркетингового признака (совпадает с нормализацией во входящем payload).
     */
    public static function marketingInboundSignalTrue(mixed $v): bool
    {
        return self::crmValueIsTruthy($v);
    }

    /**
     * Явное «нет» (отдельно от «неизвестно»: нечисловые id вариантов списка CRM не считаем false).
     */
    public static function marketingInboundSignalFalse(mixed $v): bool
    {
        return $v === false || $v === 0 || $v === '0' || $v === 'N' || $v === 'n'
            || $v === 'Нет' || $v === 'нет' || $v === 'off' || $v === 'OFF';
    }

    /**
     * Перед {@see \OnlineService\Site\Company::updateCompanyElement()}: перенос CRM UF маркетинг-агента в
     * `OS_IS_MARKETING_AGENT` и выравнивание `ACTIVE` по явному да/нет (см. {@see self::marketingInboundSignalTrue} /
     * {@see self::marketingInboundSignalFalse}). Пустое значение UF = не менять `ACTIVE`/свойство с этой ветки.
     *
     * @param array<string, mixed> $request
     */
    public static function applyCompanyInboundCrmUfToSiteProperties(array &$request): void
    {
        $chosenKey = null;
        $raw = null;
        foreach ([self::COMPANY_OS_IS_MARKETING_AGENT_UF, self::COMPANY_IS_ADVERTISING_AGENT_UF] as $k) {
            if (\array_key_exists($k, $request)) {
                $chosenKey = $k;
                $raw = $request[$k];
                break;
            }
        }
        if ($chosenKey === null) {
            return;
        }
        if (self::isMarketingPayloadAbsent($raw)) {
            unset($request[$chosenKey]);

            return;
        }
        if (self::marketingInboundSignalTrue($raw) || self::idLikeEquals($raw, CompanyModuleConfig::OS_IS_MARKETING_AGENT_ENUM_YES)
            || CompanyModuleConfig::inboundMarketingAgentPayloadMeansYesByListId($raw)) {
            $on = true;
        } elseif (self::marketingInboundSignalFalse($raw)) {
            $on = false;
        } else {
            unset($request[$chosenKey]);

            return;
        }

        $request['ACTIVE'] = $on ? 'Y' : 'N';
        // Свойство списка (L): для CIBlockElement::Update — массив VALUE = ID enum (см. CompanyModuleConfig).
        $request['OS_IS_MARKETING_AGENT'] = $on
            ? ['VALUE' => CompanyModuleConfig::OS_IS_MARKETING_AGENT_ENUM_YES]
            : false;
        unset($request[$chosenKey]);
    }

    /**
     * Перед {@see \OnlineService\Site\Company::updateCompanyElement()}: UF «головная компания холдинга» →
     * свойство списка `OS_COMPANY_IS_HEAD_OF_HOLDING` (тип L, enum «Да» — {@see CompanyModuleConfig::OS_COMPANY_IS_HEAD_OF_HOLDING_ENUM_YES}).
     *
     * @param array<string, mixed> $request
     */
    public static function applyCompanyInboundHeadOfHoldingUfToSiteProperties(array &$request): void
    {
        $chosenKey = null;
        $raw = null;
        foreach ([self::COMPANY_OS_COMPANY_IS_HEAD_OF_HOLDING_CHECKBOX_UF, self::COMPANY_IS_HEAD_OF_HOLDING_UF] as $k) {
            if (\array_key_exists($k, $request)) {
                $chosenKey = $k;
                $raw = $request[$k];
                break;
            }
        }
        if ($chosenKey === null) {
            return;
        }

        $yes = CompanyModuleConfig::OS_COMPANY_IS_HEAD_OF_HOLDING_ENUM_YES;
        if (\is_array($raw)) {
            $v = $raw['VALUE'] ?? $raw['~VALUE'] ?? null;
            if ($v === null || (\is_string($v) && \trim($v) === '')) {
                unset($request[$chosenKey]);

                return;
            }
        } else {
            if (self::isMarketingPayloadAbsent($raw)) {
                unset($request[$chosenKey]);

                return;
            }
            $v = $raw;
        }

        $pid = CompanyModuleConfig::tryParseInboundMarketingAgentListEnumId($v);
        if (self::marketingInboundSignalTrue($v) || $pid === $yes || self::idLikeEquals($v, $yes)) {
            $request['OS_COMPANY_IS_HEAD_OF_HOLDING'] = ['VALUE' => $yes];
            unset($request['OS_HOLDING_OF']);
        } elseif (self::marketingInboundSignalFalse($v) || $v === 0 || $v === '0') {
            $request['OS_COMPANY_IS_HEAD_OF_HOLDING'] = false;
        } else {
            unset($request[$chosenKey]);

            return;
        }
        unset($request[$chosenKey]);
    }

    /**
     * Перед {@see \OnlineService\Site\Company::updateCompanyElement()}: UF списка скидки CRM → {@see CompanyModuleConfig}
     * → числовой ID группы сайта в {@see \OnlineService\Site\Company::updateCompanyElement()} `OS_COMPANY_DISCOUNT_VALUE`.
     * Пустое значение при переданном ключе — снять скидочные группы у пользователей (ключ остаётся, см. {@see \OnlineService\Site\Company::applyB24CompanyGroupsToUser()}).
     *
     * @param array<string, mixed> $request
     */
    public static function applyCompanyInboundDiscountUfToSiteProperties(array &$request): void
    {
        $chosenKey = null;
        $raw = null;
        foreach ([self::COMPANY_DISCOUNT_CRM_LIST_UF, self::COMPANY_DISCOUNT_UF] as $k) {
            if (\array_key_exists($k, $request)) {
                $chosenKey = $k;
                $raw = $request[$k];
                break;
            }
        }
        if ($chosenKey === null) {
            return;
        }
        unset($request[$chosenKey]);

        if ($raw === false || $raw === null) {
            $request['OS_COMPANY_DISCOUNT_VALUE'] = false;

            return;
        }
        if (\is_string($raw) && \trim($raw) === '') {
            $request['OS_COMPANY_DISCOUNT_VALUE'] = false;

            return;
        }

        $inner = $raw;
        if (\is_array($raw)) {
            $inner = $raw['VALUE'] ?? $raw['~VALUE'] ?? null;
            if ($inner === null || (\is_string($inner) && \trim($inner) === '')) {
                $request['OS_COMPANY_DISCOUNT_VALUE'] = false;

                return;
            }
        }

        if ($inner === '0' || $inner === 0) {
            $request['OS_COMPANY_DISCOUNT_VALUE'] = false;

            return;
        }

        $crmId = CompanyModuleConfig::tryParseInboundMarketingAgentListEnumId($inner);
        if ($crmId === null || $crmId <= 0) {
            return;
        }

        $map = CompanyModuleConfig::getCompanyStatusGroupIdMap();
        if (!isset($map[$crmId])) {
            \error_log('[CrmInboundUfMap] unknown CRM discount list id for OS_COMPANY_DISCOUNT_VALUE: ' . (string)$crmId);

            return;
        }

        $request['OS_COMPANY_DISCOUNT_VALUE'] = (int)$map[$crmId];
    }

    /**
     * CRM часто шлёт ID варианта списка строкой или int.
     */
    private static function idLikeEquals($raw, int $id): bool
    {
        if (\is_int($raw)) {
            return $raw === $id;
        }
        if (\is_string($raw)) {
            $s = \trim($raw);
            if ($s === '') {
                return false;
            }
            if (\preg_match('/^-?\d+$/', $s) === 1) {
                return (int)$s === $id;
            }
        }

        return false;
    }

    /**
     * Подготовка payload пользователя перед CUser::Update:
     * - маппинг известных UF_CRM_* контакта → поля b_user;
     * - удаление всех оставшихся UF_CRM_* (в Б24 нет таких полей у пользователя — не валим Update).
     *
     * @param array<string, mixed> $fields
     */
    public static function prepareUserUpdatePayload(array &$fields): void
    {
        $crmAdv = self::CONTACT_ADVERTISING_AGENT_UF;
        if (\array_key_exists($crmAdv, $fields)) {
            $rawAdv = $fields[$crmAdv];
            if (self::isMarketingPayloadAbsent($rawAdv)) {
                unset($fields[$crmAdv]);
            } else {
                $fields['UF_ADVERSTERING_AGENT'] = self::toUserBoolInt($rawAdv);
                unset($fields[$crmAdv]);
            }
        }

        $crmDir = self::CONTACT_IS_DIRECTOR_UF;
        if (\array_key_exists($crmDir, $fields)) {
            $rawDir = $fields[$crmDir];
            if (self::isMarketingPayloadAbsent($rawDir)) {
                unset($fields[$crmDir]);
            } else {
                $fields['UF_IS_DIRECTOR'] = self::toUserBoolInt($rawDir);
                unset($fields[$crmDir]);
            }
        }

        foreach (\array_keys($fields) as $key) {
            if (\is_string($key) && \str_starts_with($key, 'UF_CRM_')) {
                unset($fields[$key]);
            }
        }
    }

    private static function toUserBoolInt(mixed $v): int
    {
        return self::crmValueIsTruthy($v) ? 1 : 0;
    }

    /**
     * 0/1 для {@see self::CONTACT_IS_DIRECTOR_UF} в `crm.contact.update` по значению `b_user.UF_IS_DIRECTOR`.
     */
    public static function userDirectorUfToCrmInt(mixed $v): int
    {
        return self::toUserBoolInt($v);
    }

    private static function crmValueIsTruthy(mixed $v): bool
    {
        return $v === true || $v === 1 || $v === '1' || $v === 'Y' || $v === 'y'
            || $v === 'Да' || $v === 'да' || $v === 'on' || $v === 'ON';
    }
}
