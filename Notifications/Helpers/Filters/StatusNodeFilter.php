<?php

namespace App\Modules\Notifications\Helpers\Filters;

use Illuminate\Database\Eloquent\Builder;

class StatusNodeFilter
{
    public function handle(Builder $query, $status): Builder
    {
        return $query->where('status', $status);
    }
}
