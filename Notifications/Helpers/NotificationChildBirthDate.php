<?php

namespace App\Modules\Notifications\Helpers;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Enums\Notification\NotificationStatusEnum;
use App\Modules\Notifications\Models\Notification;
use App\Modules\Notifications\Models\NotificationCategory;
use App\Modules\Notifications\NotificationChildBirthDateMessage;
use Carbon\Carbon;

class NotificationChildBirthDate
{
    private const CHILD_AGE_IN_MOUTH = 18;

    public function store($ordNursingBreak): void
    {
        $childBirthDate = $ordNursingBreak->orderEntry->user->childrenBirthDates->sortByDesc('date')->first();
        $childAgeInMouth = Carbon::parse($childBirthDate->date)->diffInMonths(Carbon::now());

        if ($childAgeInMouth <= self::CHILD_AGE_IN_MOUTH) {

            $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::BABY_IS_EIGHTEEN_MONTHS->value);
            $message = NotificationChildBirthDateMessage::generate($ordNursingBreak);

            $notification = new Notification();
            $notification->message = $message;
            $notification->notice_date = NotificationHelper::calcNoticeDate($ordNursingBreak->end_date, $notificationCategory->days_advance);
            $notification->status = NotificationStatusEnum::NEW->value;
            $notification->category_id = NotificationCategoryEnum::BABY_IS_EIGHTEEN_MONTHS->value;
            $notification->ordNursingBreak()->associate($ordNursingBreak);
            $notification->save();
        }
    }

    public function update($ordNursingBreak): void
    {
        $childBirthDate = $ordNursingBreak->orderEntry->user->childrenBirthDates->sortByDesc('date')->first();
        $childAgeInMouth = Carbon::parse($childBirthDate->date)->diffInMonths(Carbon::now());

        if ($childAgeInMouth <= self::CHILD_AGE_IN_MOUTH) {

            $notificationCategory = NotificationCategory::find(NotificationCategoryEnum::BABY_IS_EIGHTEEN_MONTHS->value);
            $message = NotificationChildBirthDateMessage::generate($ordNursingBreak);

            (new Notification())->updateOrCreate(
                [
                    'notificationable_type' => 'ord-nursing-break',
                    'notificationable_id'   => $ordNursingBreak->id,
                ],
                [
                    'message'     => $message,
                    'notice_date' => NotificationHelper::calcNoticeDate($childBirthDate->end_date, $notificationCategory->days_advance),
                    'status'      => NotificationStatusEnum::NEW->value,
                    'category_id' => NotificationCategoryEnum::BABY_IS_EIGHTEEN_MONTHS->value,
                ]
            );

        } else {
            $ordNursingBreak->whereHas('notification', fn($query) => $query->delete());
        }
    }

    public function delete($ordNursingBreak): void
    {
        Notification::where('notificationable_type', 'ord-nursing-break')
            ->where('notificationable_id', $ordNursingBreak->id)
            ->where('category_id', NotificationCategoryEnum::BABY_IS_EIGHTEEN_MONTHS->value)
            ->delete();
    }
}
