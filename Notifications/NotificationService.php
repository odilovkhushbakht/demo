<?php

namespace App\Modules\Notifications;

use App\Acme\Services\BaseService;
use App\Modules\Notifications\Models\Notification;

class NotificationService extends BaseService
{
    public function updateStatus(Notification $notification): void
    {
        $notification->status = $this->request->input('status');
        $notification->save();
    }
}
