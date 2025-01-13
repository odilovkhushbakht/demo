<?php

namespace App\Modules\Notifications\Interfaces;

interface NotificationMessageInterface
{
    public static function generate($data): string;
}
