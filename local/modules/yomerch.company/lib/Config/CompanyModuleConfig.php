<?php

namespace OnlineService\Site\Config;

final class CompanyModuleConfig
{
    public const COMPANY_IBLOCK_ID = 57;

    /**
     * Боевой маппинг: ID группы статуса (после UserGroups::searchGroup) -> ID группы для присвоения пользователю.
     *
     * @var array<int, int>
     */
    public const PROD_COMPANY_STATUS_GROUP_ID_MAP = [
        1014 => 94, // 20%
        1015 => 93, // 25%
        1016 => 70, // 30%
        1017 => 1047, // 32%
        1018 => 1048, // 35%
        1019 => 1049, // 37%
        1020 => 1050, // 38%
        1021 => 1051, // 40%
    ];

    /**
     * Боевой маппинг: ID группы пользователя -> процент скидки от оптовой базы на витрине.
     *
     * @var array<int, float>
     */
    public const PROD_COMPANY_DISCOUNT_PERCENT_BY_ASSIGNED_GROUP_ID = [
        94 => 20.0,
        93 => 25.0,
        70 => 30.0,
        1047 => 32.0,
        1048 => 35.0,
        1049 => 37.0,
        1050 => 38.0,
        1051 => 40.0,
    ];

    /**
     * Тестовый маппинг. Заполнить отдельной матрицей тестового портала при наличии отличий от боевого.
     *
     * @var array<int, int>
     */
    public const TEST_COMPANY_STATUS_GROUP_ID_MAP = [
        890 => 476, // 20%
        891 => 477, // 25%
        892 => 478, // 30%
        893 => 475, // 32%
        894 => 479, // 35%
        895 => 480, // 37%
        896 => 481, // 38%
        897 => 482, // 40%
    ];

    /**
     * Тестовый маппинг. Заполнить отдельной матрицей тестового портала при наличии отличий от боевого.
     *
     * @var array<int, float>
     */
    public const TEST_COMPANY_DISCOUNT_PERCENT_BY_ASSIGNED_GROUP_ID = [
        476 => 20.0,
        477 => 25.0,
        478 => 30.0,
        475 => 32.0,
        479 => 35.0,
        480 => 37.0,
        481 => 38.0,
        482 => 40.0,
    ];

    /**
     * Соответствие ID пользовательского профиля заказа -> поля компании/пользователя.
     *
     * @var array<int, string>
     */
    public const ORDER_CUSTOM_FIELD_IDS = [
        8 => 'OS_COMPANY_NAME',
        10 => 'OS_COMPANY_INN',
        12 => 'USER_NAME__USER_LASTNAME',
        13 => 'OS_COMPANY_EMAIL',
        14 => 'OS_COMPANY_PHONE',
    ];

    /**
     * @return array<int, int>
     */
    public static function getCompanyStatusGroupIdMap(): array
    {
        return self::isTestPortal()
            ? self::TEST_COMPANY_STATUS_GROUP_ID_MAP
            : self::PROD_COMPANY_STATUS_GROUP_ID_MAP;
    }

    /**
     * @return array<int, float>
     */
    public static function getCompanyDiscountPercentByAssignedGroupId(): array
    {
        return self::isTestPortal()
            ? self::TEST_COMPANY_DISCOUNT_PERCENT_BY_ASSIGNED_GROUP_ID
            : self::PROD_COMPANY_DISCOUNT_PERCENT_BY_ASSIGNED_GROUP_ID;
    }

    private static function isTestPortal(): bool
    {
        return \defined('B24_USE_TEST_PORTAL') ? (bool)\B24_USE_TEST_PORTAL : false;
    }
}
