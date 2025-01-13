<?php

namespace App\Modules\Notifications;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Modules\Notifications\Interfaces\NotificationMessageInterface;
use App\Modules\Notifications\Models\NotificationCategory;

class NotificationVacationDecreeLeaveMessage implements NotificationMessageInterface
{

    public static function generate($data): string
    {
        $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::VACATION_DECREE_LEAVE_EXPIRY->value);

        $search = [
            '${fullName}',
            '${expiryDate}',
        ];

        $replace = [
            $data->orderEntry->user->fullName(),
            $data->end_date,
        ];

        return str_replace($search, $replace, $notificationCategory->message_template);
    }
}
