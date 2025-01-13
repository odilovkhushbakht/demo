<?php

namespace App\Modules\Notifications;

use App\Enums\Notification\ExpiryDocumentsTypesEnum;
use App\Enums\Notification\NotificationCategoryEnum;
use App\Modules\Notifications\Interfaces\NotificationMessageInterface;
use App\Modules\Notifications\Models\NotificationCategory;
use Carbon\Carbon;

class NotificationExpiringDocumentsMessage implements NotificationMessageInterface
{

    public static function generate($data): string
    {
        $documentTypeId = match ($data->document_type_id) {
            ExpiryDocumentsTypesEnum::LICENSE_FOREIGN_WORKER->value           => NotificationCategoryEnum::LICENSE_FOREIGN_WORKER->value,
            ExpiryDocumentsTypesEnum::WORK_PERMIT_FOREIGN_WORKER->value       => NotificationCategoryEnum::WORK_PERMIT_FOREIGN_WORKER->value,
            ExpiryDocumentsTypesEnum::REGISTRATION_LIST_FOREIGN_WORKER->value => NotificationCategoryEnum::REGISTRATION_LIST_FOREIGN_WORKER->value,
            ExpiryDocumentsTypesEnum::CRIMINAL_RECORD_CERTIFICATE->value      => NotificationCategoryEnum::CRIMINAL_RECORD_CERTIFICATE->value,
            ExpiryDocumentsTypesEnum::MEDICAL_CERTIFICATE->value              => NotificationCategoryEnum::MEDICAL_CERTIFICATE->value,
        };
        $notificationCategory = NotificationCategory::find($documentTypeId);

        $search = [
            '${fullName}',
            '${expiryDate}',
        ];

        $replace = [
            $data->user->fullName(),
            $data->to_date,
        ];

        return str_replace($search, $replace, $notificationCategory->message_template);
    }
}
