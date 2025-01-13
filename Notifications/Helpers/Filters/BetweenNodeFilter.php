<?php

namespace App\Modules\Notifications\Helpers\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class BetweenNodeFilter
{
    public function handle(Builder $query, $between): Builder
    {
        return $query->whereBetween('notice_date', $between);
    }
}
