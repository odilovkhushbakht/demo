<?php

namespace App\Modules\Notifications;

use App\Enums\Notification\NotificationCategoryFactoryEnum;
use App\Modules\Notifications\Helpers\NotificationChildBirthDate;
use App\Modules\Notifications\Helpers\NotificationExpiringDocuments;
use App\Modules\Notifications\Helpers\NotificationLegalAge;
use App\Modules\Notifications\Helpers\NotificationPassport;
use App\Modules\Notifications\Helpers\NotificationResidentialAddress;
use App\Modules\Notifications\Helpers\NotificationVacationChildcareLeave;
use App\Modules\Notifications\Helpers\NotificationVacationDecreeLeave;

class NotificationFactory
{
    public static function make(string $typeName)
    {
        return match ($typeName) {

            NotificationCategoryFactoryEnum::PASSPORT->value                   => resolve(NotificationPassport::class),
            NotificationCategoryFactoryEnum::LEGAL_AGE->value                  => resolve(NotificationLegalAge::class),
            NotificationCategoryFactoryEnum::VACATION_DECREE_LEAVE->value      => resolve(NotificationVacationDecreeLeave::class),
            NotificationCategoryFactoryEnum::VACATION_CHILDCARE_LEAVE->value   => resolve(NotificationVacationChildcareLeave::class),
            NotificationCategoryFactoryEnum::DOCUMENTS_EXPIRY->value           => resolve(NotificationExpiringDocuments::class),
            NotificationCategoryFactoryEnum::CHILDREN_BIRTH_DATE->value        => resolve(NotificationChildBirthDate::class),
            NotificationCategoryFactoryEnum::RESIDENTIAL_ADDRESS_EXPIRY->value => resolve(NotificationResidentialAddress::class),

        };
    }
}
