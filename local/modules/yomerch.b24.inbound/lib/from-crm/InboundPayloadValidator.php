<?php
namespace OnlineService\Sync\FromCrm;

final class InboundPayloadValidator
{
    /**
     * @param array<string, mixed> $request
     * @return array{valid: bool, reason_code?: string}
     */
    public static function validate(array $request): array
    {
        $action = self::scalarValue($request['ACTION'] ?? null);
        if ($action === '') {
            return ['valid' => false, 'reason_code' => 'missing_action'];
        }

        switch ($action) {
            case 'UPDATE_GROUP':
                return self::validateUpdateGroup($request);
            case 'UPDATE_CONTACT':
                return self::requireScalarFields($request, ['B24_ID'], 'update_contact');
            case 'UPDATE_BATCH_USERS':
                return self::validateUpdateBatchUsers($request);
            case 'DELETE_CONTACT':
            case 'DELETE_COMPANY':
                return self::requireScalarFields($request, ['ID'], \strtolower($action));
            case 'UPDATE_COMPANY':
                return self::validateUpdateCompany($request);
            case 'SYNC_COMPANY_CONTACTS':
                return self::requireScalarFields($request, ['COMPANY_ID'], 'sync_company_contacts');
            case 'UPDATE_MANAGER':
                return self::requireScalarFields($request, ['ID'], 'update_manager');
            default:
                return ['valid' => true];
        }
    }

    /**
     * @param array<string, mixed> $request
     * @return array{valid: bool, reason_code?: string}
     */
    private static function validateUpdateGroup(array $request): array
    {
        $required = self::requireScalarFields($request, ['ID', 'ACTIVE', 'C_SORT', 'NAME'], 'update_group');
        if (!$required['valid']) {
            return $required;
        }

        $active = self::scalarValue($request['ACTIVE'] ?? null);
        if (!\in_array($active, ['Y', 'N'], true)) {
            return ['valid' => false, 'reason_code' => 'update_group_invalid_active'];
        }

        return ['valid' => true];
    }

    /**
     * @param array<string, mixed> $request
     * @return array{valid: bool, reason_code?: string}
     */
    private static function validateUpdateBatchUsers(array $request): array
    {
        if (!isset($request['CONTACT_IDS']) || !\is_array($request['CONTACT_IDS']) || $request['CONTACT_IDS'] === []) {
            return ['valid' => false, 'reason_code' => 'update_batch_users_missing_contact_ids'];
        }
        foreach ($request['CONTACT_IDS'] as $value) {
            if (!\is_scalar($value) || \trim((string)$value) === '') {
                return ['valid' => false, 'reason_code' => 'update_batch_users_invalid_contact_ids'];
            }
        }
        if (!isset($request['IS_MARKETING_AGENT']) || !\is_scalar($request['IS_MARKETING_AGENT'])) {
            return ['valid' => false, 'reason_code' => 'update_batch_users_missing_marketing_flag'];
        }

        return ['valid' => true];
    }

    /**
     * @param array<string, mixed> $request
     * @return array{valid: bool, reason_code?: string}
     */
    private static function validateUpdateCompany(array $request): array
    {
        $required = self::requireScalarFields($request, ['OS_COMPANY_B24_ID', 'OS_COMPANY_NAME', 'ACTIVE'], 'update_company');
        if (!$required['valid']) {
            return $required;
        }

        $active = self::scalarValue($request['ACTIVE'] ?? null);
        if (!\in_array($active, ['Y', 'N'], true)) {
            return ['valid' => false, 'reason_code' => 'update_company_invalid_active'];
        }

        if (isset($request['OS_COMPANY_USERS']) && !\is_array($request['OS_COMPANY_USERS'])) {
            return ['valid' => false, 'reason_code' => 'update_company_invalid_users_type'];
        }
        if (isset($request['CONTACT_IDS']) && !\is_array($request['CONTACT_IDS'])) {
            return ['valid' => false, 'reason_code' => 'update_company_invalid_contact_ids_type'];
        }

        return ['valid' => true];
    }

    /**
     * @param array<string, mixed> $request
     * @param list<string> $fields
     * @return array{valid: bool, reason_code?: string}
     */
    private static function requireScalarFields(array $request, array $fields, string $prefix): array
    {
        foreach ($fields as $field) {
            if (!isset($request[$field])) {
                return ['valid' => false, 'reason_code' => $prefix . '_missing_' . \strtolower($field)];
            }
            if (!\is_scalar($request[$field]) || \trim((string)$request[$field]) === '') {
                return ['valid' => false, 'reason_code' => $prefix . '_invalid_' . \strtolower($field)];
            }
        }

        return ['valid' => true];
    }

    /**
     * @param mixed $value
     */
    private static function scalarValue($value): string
    {
        if (!\is_scalar($value)) {
            return '';
        }

        return \trim((string)$value);
    }
}
