<?php

namespace App\Modules\Notifications;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Modules\Notifications\Interfaces\NotificationMessageInterface;
use App\Modules\Notifications\Models\NotificationCategory;

class NotificationVacationChildcareLeaveMessage implements NotificationMessageInterface
{

    public static function generate($data): string
    {
        $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::VACATION_CHILDCARE_LEAVE_EXPIRY->value);

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
