<?php

namespace App\Modules\Notifications\Traits;

use App\Modules\Notifications\Models\Notification;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait NotificationRelationable
{
    public function notification(): MorphOne
    {
        return $this->morphOne(Notification::class, 'notification','notificationable_type', 'notificationable_id');
    }
}
