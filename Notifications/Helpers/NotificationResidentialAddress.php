<?php

namespace App\Modules\Notifications\Helpers;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Enums\Notification\NotificationStatusEnum;
use App\Modules\Notifications\Models\Notification;
use App\Modules\Notifications\Models\NotificationCategory;
use App\Modules\Notifications\NotificationResidentialAddressMessage;
use Carbon\Carbon;

class NotificationResidentialAddress
{
    public function store($passport): void
    {
        if (!is_null($passport->residential_address_expiry_date)) {
            $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::RESIDENTIAL_ADDRESS_EXPIRY->value);
            $message = NotificationResidentialAddressMessage::generate($passport);
            $noticeDate = NotificationHelper::calcNoticeDate($passport->residential_address_expiry_date, $notificationCategory->days_advance);

            $passport->notification()->create([
                'message'     => $message,
                'notice_date' => $noticeDate,
                'status'      => NotificationStatusEnum::NEW->value,
                'category_id' => NotificationCategoryEnum::RESIDENTIAL_ADDRESS_EXPIRY->value,
            ]);
        }
    }

    public function update($passport): void
    {
        if (!is_null($passport->residential_address_expiry_date)) {
            $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::RESIDENTIAL_ADDRESS_EXPIRY->value);
            $message = NotificationResidentialAddressMessage::generate($passport);
            $noticeDate = NotificationHelper::calcNoticeDate($passport->residential_address_expiry_date, $notificationCategory->days_advance);

            $notification = [
                'message'     => $message,
                'notice_date' => $noticeDate,
                'status'      => NotificationStatusEnum::NEW->value,
            ];

            $noticeDateOld = Notification::query()
                ->where('notificationable_type', 'passport')
                ->where('notificationable_id', $passport->id)
                ->where('category_id', $notificationCategory->id)
                ->whereDate('notice_date', $noticeDate)
                ->first();

            if (!is_null($noticeDateOld)) {
                return;
            }

            (new Notification())->updateOrCreate(
                [
                    'notificationable_type' => 'passport',
                    'notificationable_id'   => $passport->id,
                    'category_id'           => NotificationCategoryEnum::RESIDENTIAL_ADDRESS_EXPIRY->value,
                ],
                $notification
            );
        }
    }
}
