<?php declare (strict_types = 1);

namespace App\Shared\Domain\Enum\Exception;

use App\Shared\Domain\Enum\EnumInterface;

class ExceptionEnum implements EnumInterface
{
    const CLIENT_USER_NOT_FOUND = 'client_user.not_found';
    const CLIENT_USER_WITH_TOKEN_NOT_FOUND = 'client_user.with_token_not_found';
    const CLIENT_USER_EMAIL_NOT_FOUND = 'client_user.email_not_found';
    const CLIENT_USER_INVALID_EMAIL = 'client_user.invalid_email';
    const CLIENT_USER_NOT_UNIQUE_EMAIL = 'client_user.not_unique_email';
    const CLIENT_USER_CREATION_FAILED = 'client_user.creation_failed';

    const CLIENT_NOT_FOUND = 'client.not_found';
    const CLIENT_INSTALLATION_FAILED = 'client.installation_failed';
    const CLIENT_INVALID_SUB_DOMAIN = 'client.invalid_sub_domain';
    const SECOND_MAIL_SENT = 'client.second_mail_sent';

    const DATABASE_CREATION_FAILED = 'database.creation_failed';
    const DATABASE_GRANT_ALL_PRIVILEGES_FAILED = 'database.grant_all_privileges_failed';

    const USER_NOT_FOUND = 'user.not_found';

    const DIR_NOT_FOUND = 'dir.not_found';

    const UPDATE_APK_IS_UP_TO_DATE = 'update.apk_is_up_to_date';
    const UPDATE_APK_VERSION_IS_DELIGHTED = 'update.apk_version_is_delighted';

    const REQUEST_BODY_PARAM_CONVERTER_SYMFONY_SERIALIZER_FAILURE = 'request_body_param_converter.symfony_serializer_failure';

    const AGREEMENTS_NOT_FOUND = 'agreement.not_found';

    const DECIMAL_INVALID_VALUE = 'decimal_invalid_value';

    const LICENSE_MIGRATION_EXECUTED = 'license.license_migration_executed';
    const LICENSE_NOT_FOUND = 'license.not_found';
    const LICENSE_SET_NOT_FOUND = 'license.set_not_found';
    const LICENSE_ADDON_NOT_FOUND = "license.addon_not_found";
    const LICENSE_ADDITIONAL_DEVICE_NOT_FOUND = "license.additional_device_not_found";
    const LICENSE_TOTAL_PRICE_MISMATCH = "license.total_price_mismatch";
    const LICENSE_UNHANDLED_PAYMENT_METHOD = "license.unhandled_payment_method";
    const LICENSE_PAYMENT_ERROR = "license.payment_error";
    const LICENSE_ORDER_NOT_FOUND = "license.order_not_found";
    const LICENSE_ASSIGNED_ERROR = "license.license_already_assigned";
    const LICENSE_ADDITIONAL_DEVICE_UPDATE_ERROR = "license.devices_update_error";
    const LICENSE_NO_FULL_PERIODS = "license.no_full_periods";
    const LICENSE_UPGRADE_ERROR = "license.upgrade_error";

    public static function getTypes(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }
}
