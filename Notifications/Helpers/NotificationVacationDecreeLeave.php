<?php

namespace App\Modules\Notifications\Helpers;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Enums\Notification\NotificationStatusEnum;
use App\Modules\Notifications\Models\Notification;
use App\Modules\Notifications\Models\NotificationCategory;
use App\Modules\Notifications\NotificationVacationDecreeLeaveMessage;
use Carbon\Carbon;

class NotificationVacationDecreeLeave
{

    public function store($vacation): void
    {
        $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::VACATION_DECREE_LEAVE_EXPIRY->value);
        $message = NotificationVacationDecreeLeaveMessage::generate($vacation);

        $notification = new Notification();
        $notification->message = $message;
        $notification->notice_date = NotificationHelper::calcNoticeDate($vacation->end_date, $notificationCategory->days_advance);
        $notification->status = NotificationStatusEnum::NEW->value;
        $notification->category_id = NotificationCategoryEnum::VACATION_DECREE_LEAVE_EXPIRY->value;
        $notification->ordVacation()->associate($vacation);
        $notification->save();
    }

    public function update($vacation): void
    {
        if (Carbon::now()->lte($vacation->end_date)) {

            $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::VACATION_DECREE_LEAVE_EXPIRY->value);
            $message = NotificationVacationDecreeLeaveMessage::generate($vacation);

            (new Notification())->updateOrCreate(
                [
                    'notificationable_type' => 'ord_vacation',
                    'notificationable_id'   => $vacation->id,
                ],
                [
                    'message'     => $message,
                    'notice_date' => NotificationHelper::calcNoticeDate($vacation->end_date, $notificationCategory->days_advance),
                    'status'      => NotificationStatusEnum::NEW->value,
                    'category_id' => NotificationCategoryEnum::VACATION_DECREE_LEAVE_EXPIRY->value,
                ]
            );

        }
    }

    public function delete($vacation): void
    {
        Notification::where('notificationable_type', 'ord_vacation')
            ->where('notificationable_id', $vacation->id)
            ->where('category_id', NotificationCategoryEnum::VACATION_DECREE_LEAVE_EXPIRY->value)
            ->delete();
    }
}
