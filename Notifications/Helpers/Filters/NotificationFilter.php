<?php

namespace App\Modules\Notifications\Helpers\Filters;

use Filter\Filter;

class NotificationFilter extends Filter
{
    protected array $filters = [
        'status'      => StatusNodeFilter::class,
        'category_id' => CategoryNodeFilter::class,
        'between'     => BetweenNodeFilter::class,
    ];
}
