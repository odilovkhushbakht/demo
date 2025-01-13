<?php

namespace App\Modules\Notifications;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Modules\Notifications\Interfaces\NotificationMessageInterface;
use App\Modules\Notifications\Models\NotificationCategory;

class NotificationChildBirthDateMessage implements NotificationMessageInterface
{

    public static function generate($data): string
    {
        $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::BABY_IS_EIGHTEEN_MONTHS->value);

        $search = [
            '${fullName}',
            '${expiryDate}',
        ];

        $replace = [
            $data->orderEntry->user->fullname(),
            $data->end_date,
        ];

        return str_replace($search, $replace, $notificationCategory->message_template);
    }
}
