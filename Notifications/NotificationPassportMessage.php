<?php

namespace App\Modules\Notifications;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Modules\Notifications\Interfaces\NotificationMessageInterface;
use App\Modules\Notifications\Models\NotificationCategory;

class NotificationPassportMessage implements NotificationMessageInterface
{

    public static function generate($data): string
    {
        $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::PASSPORT_EXPIRY->value);

        $search = [
            '${fullName}',
            '${serialNumber}',
            '${expiryDate}',
        ];

        $replace = [
            $data->fullname(),
            $data->serial_number,
            $data->expiry_date,
        ];

        return str_replace($search, $replace, $notificationCategory->message_template);
    }
}
