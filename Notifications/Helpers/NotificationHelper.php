<?php

namespace App\Modules\Notifications\Helpers;

use Carbon\Carbon;

class NotificationHelper
{
    public static function calcNoticeDate($endDate, $daysAdvance)
    {
        return Carbon::create($endDate)->subDays($daysAdvance);
    }

    public static function calcNoticeDateLegalAge($endDate, $daysAdvance, $legalAge)
    {
        return Carbon::create($endDate)->addYears($legalAge)->subDays($daysAdvance);
    }
}
