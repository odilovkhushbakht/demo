<?php

namespace App\Modules\Notifications\Helpers;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Enums\Notification\NotificationStatusEnum;
use App\Modules\Notifications\Models\Notification;
use App\Modules\Notifications\Models\NotificationCategory;
use App\Modules\Notifications\NotificationLegalAgeMessage;
use Carbon\Carbon;

class NotificationLegalAge
{
    private const LEGAL_AGE = 18;

    public function store($passport): void
    {
        $birthDate = Carbon::create($passport->birth_date);

        if ((int)$birthDate->age < self::LEGAL_AGE) {

            $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::LEGAL_AGE->value);
            $message = NotificationLegalAgeMessage::generate($passport);

            (new Notification())->updateOrCreate(
                [
                    'notificationable_type' => 'user',
                    'notificationable_id'   => $passport->user_id,
                ],
                [
                    'notificationable_type' => 'user',
                    'notificationable_id'   => $passport->user_id,
                    'message'               => $message,
                    'notice_date'           => NotificationHelper::calcNoticeDateLegalAge($birthDate, $notificationCategory->days_advance, self::LEGAL_AGE),
                    'status'                => NotificationStatusEnum::NEW->value,
                    'category_id'           => NotificationCategoryEnum::LEGAL_AGE->value,
                ]
            );
        }
    }

    public function update($passport): void
    {
        $birthDate = Carbon::create($passport->birth_date);

        if ((int)$birthDate->age < self::LEGAL_AGE) {

            $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::LEGAL_AGE->value);
            $message = NotificationLegalAgeMessage::generate($passport);

            (new Notification())->updateOrCreate(
                [
                    'notificationable_type' => 'user',
                    'notificationable_id'   => $passport->user_id,
                ],
                [
                    'notificationable_type' => 'user',
                    'notificationable_id'   => $passport->user_id,
                    'message'               => $message,
                    'notice_date'           => NotificationHelper::calcNoticeDateLegalAge($birthDate, $notificationCategory->days_advance, self::LEGAL_AGE),
                    'status'                => NotificationStatusEnum::NEW->value,
                    'category_id'           => NotificationCategoryEnum::LEGAL_AGE->value,
                ]
            );

        } else {
            $passport->notification()->delete();
        }
    }
}
