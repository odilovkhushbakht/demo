<?php

namespace App\Modules\Notifications;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Modules\Notifications\Interfaces\NotificationMessageInterface;
use App\Modules\Notifications\Models\NotificationCategory;

class NotificationResidentialAddressMessage implements NotificationMessageInterface
{

    public static function generate($data): string
    {
        $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::RESIDENTIAL_ADDRESS_EXPIRY->value);

        $search = [
            '${fullName}',
            '${expiryDate}',
        ];

        $replace = [
            $data->fullname(),
            $data->residential_address_expiry_date,
        ];

        return str_replace($search, $replace, $notificationCategory->message_template);
    }
}
