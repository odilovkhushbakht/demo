<?php

namespace App\Modules\Notifications;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Modules\Notifications\Interfaces\NotificationMessageInterface;
use App\Modules\Notifications\Models\NotificationCategory;
use Carbon\Carbon;

class NotificationLegalAgeMessage implements NotificationMessageInterface
{
    private const LEGAL_AGE = 18;

    public static function generate($data): string
    {
        $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::LEGAL_AGE->value);

        $search = [
            '${fullName}',
            '${expiryDate}',
        ];

        $replace = [
            $data->fullName(),
            Carbon::create($data->birth_date)->addYears(self::LEGAL_AGE)->toDateString(),
        ];

        return str_replace($search, $replace, $notificationCategory->message_template);
    }
}
