<?php

namespace App\Modules\Notifications\Models;

use App\Enums\Notification\NotificationCategoryEnum;
use App\Modules\Documents\Models\ExpiringDocument;
use App\Modules\Documents\Models\Passport;
use App\Modules\Orders\Models\OrdNursingBreak;
use App\Modules\Orders\Models\OrdVacation;
use Carbon\Carbon;
use Filter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use Filterable;

    protected $table = 'notifications';
    protected $fillable = [
        'notificationable_type',
        'notificationable_id',
        'message',
        'notice_date',
        'status',
        'category_id'
    ];

    public function passport(): MorphTo
    {
        return $this->morphTo(Passport::class);
    }

    public function category(): HasOne
    {
        return $this->hasOne(NotificationCategory::class, 'id', 'category_id');
    }

    public function ordVacation(): MorphTo
    {
        return $this->morphTo(OrdVacation::class, 'notificationable_type', 'notificationable_id');
    }

    public function expiringDocuments(): MorphTo
    {
        return $this->morphTo(ExpiringDocument::class, 'notificationable_type', 'notificationable_id');
    }

    public function ordNursingBreak(): MorphTo
    {
        return $this->morphTo(OrdNursingBreak::class, 'notificationable_type', 'notificationable_id');
    }

    public function scopeActual($query)
    {
        return $query
            ->where('status', 'new')
            ->whereDate('notice_date', '<=', today()->toDateString());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('notice_date', '<=', today()->addMonth()->toDateString());
    }
}
