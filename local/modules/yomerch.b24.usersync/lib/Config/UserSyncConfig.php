<?php

namespace OnlineService\B24\UserSync\Config;

final class UserSyncConfig
{
    public const ADMINISTRATORS_GROUP_ID = 1;
    public const MARKETING_AGENT_GROUP_ID = 12;
    public const DIRECTOR_GROUP_ID = 432;

    /** b_user field: основной менеджер */
    public const USER_PRIMARY_MANAGER_FIELD = 'UF_MANAGER';
    /** b_user field: второй менеджер */
    public const USER_SECONDARY_MANAGER_FIELD = 'UF_MANAGER2';

    /** crm.contact field: основной менеджер (стандартный) */
    public const CRM_PRIMARY_MANAGER_FIELD = 'ASSIGNED_BY_ID';
    /** legacy inbound alias для основного менеджера */
    public const CRM_PRIMARY_MANAGER_LEGACY_FIELD = 'ASSIGNED_MANAGER';

    /** crm.contact custom UF: второй менеджер */
    public const CRM_SECONDARY_MANAGER_FIELD = 'UF_CRM_1757682312';
    /** legacy inbound alias для второго менеджера */
    public const CRM_SECONDARY_MANAGER_LEGACY_FIELD = 'SECOND_MANAGER';
}
