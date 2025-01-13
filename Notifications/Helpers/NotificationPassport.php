<?php

namespace App\Modules\Notifications\Helpers;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Enums\Notification\NotificationStatusEnum;
use App\Modules\Notifications\Models\Notification;
use App\Modules\Notifications\NotificationPassportMessage;
use App\Modules\Notifications\Models\NotificationCategory;
use Carbon\Carbon;

class NotificationPassport
{
    public function store($passport): void
    {
        $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::PASSPORT_EXPIRY->value);
        $message = NotificationPassportMessage::generate($passport);
        $noticeDate = NotificationHelper::calcNoticeDate($passport->expiry_date, $notificationCategory->days_advance);

        $passport->notification()->create([
            'message'     => $message,
            'notice_date' => $noticeDate,
            'status'      => NotificationStatusEnum::NEW->value,
            'category_id' => NotificationCategoryEnum::PASSPORT_EXPIRY->value,
        ]);
    }

    public function update($passport): void
    {
        $expiryDate = Carbon::create($passport->expiry_date);

        if ($expiryDate->gte(Carbon::now()->toDateString())) {

            $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::PASSPORT_EXPIRY->value);
            $message = NotificationPassportMessage::generate($passport);
            $noticeDate = NotificationHelper::calcNoticeDate($passport->expiry_date, $notificationCategory->days_advance);

            (new Notification())->updateOrCreate(
                [
                    'notificationable_type' => 'passport',
                    'notificationable_id'   => $passport->user_id,
                ],
                [
                    'notificationable_type' => 'passport',
                    'notificationable_id'   => $passport->user_id,
                    'message'               => $message,
                    'notice_date'           => $noticeDate,
                    'status'                => NotificationStatusEnum::NEW->value,
                    'category_id'           => NotificationCategoryEnum::PASSPORT_EXPIRY->value,
                ]
            );
        }
    }
}
