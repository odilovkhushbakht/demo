<?php

namespace App\Modules\Notifications\Helpers;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Enums\Notification\ExpiryDocumentsTypesEnum;
use App\Enums\Notification\NotificationStatusEnum;
use App\Modules\Notifications\Models\Notification;
use App\Modules\Notifications\Models\NotificationCategory;
use App\Modules\Notifications\NotificationExpiringDocumentsMessage;
use Carbon\Carbon;

class NotificationExpiringDocuments
{

    public function store($expiringDocuments): void
    {
        $notificationCategory = NotificationCategory::find($this->getDocumentCategoryId($expiringDocuments->document_type_id));
        $message = NotificationExpiringDocumentsMessage::generate($expiringDocuments);

        $notification = new Notification();
        $notification->message = $message;
        $notification->notice_date = NotificationHelper::calcNoticeDate($expiringDocuments->to_date, $notificationCategory->days_advance);
        $notification->status = NotificationStatusEnum::NEW->value;
        $notification->category_id = $this->getDocumentCategoryId($expiringDocuments->document_type_id);
        $notification->expiringDocuments()->associate($expiringDocuments);
        $notification->save();
    }

    public function update($expiringDocuments): void
    {
        if (Carbon::now()->lte($expiringDocuments->to_date)) {

            $notificationCategory = NotificationCategory::find($this->getDocumentCategoryId($expiringDocuments->document_type_id));
            $message = NotificationExpiringDocumentsMessage::generate($expiringDocuments);

            (new Notification())->updateOrCreate(
                [
                    'notificationable_type' => 'expiring-document',
                    'notificationable_id'   => $expiringDocuments->id,
                ],
                [
                    'message'     => $message,
                    'notice_date' => NotificationHelper::calcNoticeDate($expiringDocuments->to_date, $notificationCategory->days_advance),
                    'status'      => NotificationStatusEnum::NEW->value,
                    'category_id' => $this->getDocumentCategoryId($expiringDocuments->document_type_id),
                ]
            );

        }
    }

    public function delete($expiringDocuments): void
    {
        Notification::where('notificationable_type', 'expiring-document')
            ->where('notificationable_id', $expiringDocuments->id)
            ->where('category_id', $this->getDocumentCategoryId($expiringDocuments->document_type_id))
            ->delete();
    }

    private function getDocumentCategoryId(int $documentTypeId): int
    {
        return match ($documentTypeId) {
            ExpiryDocumentsTypesEnum::LICENSE_FOREIGN_WORKER->value => NotificationCategoryEnum::LICENSE_FOREIGN_WORKER->value,
            ExpiryDocumentsTypesEnum::WORK_PERMIT_FOREIGN_WORKER->value => NotificationCategoryEnum::WORK_PERMIT_FOREIGN_WORKER->value,
            ExpiryDocumentsTypesEnum::REGISTRATION_LIST_FOREIGN_WORKER->value => NotificationCategoryEnum::REGISTRATION_LIST_FOREIGN_WORKER->value,
            ExpiryDocumentsTypesEnum::CRIMINAL_RECORD_CERTIFICATE->value => NotificationCategoryEnum::CRIMINAL_RECORD_CERTIFICATE->value,
            ExpiryDocumentsTypesEnum::MEDICAL_CERTIFICATE->value => NotificationCategoryEnum::MEDICAL_CERTIFICATE->value,
        };
    }
}
