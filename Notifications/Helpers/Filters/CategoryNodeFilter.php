<?php

namespace App\Modules\Notifications\Helpers\Filters;

use Illuminate\Database\Eloquent\Builder;

class CategoryNodeFilter
{
    public function handle(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }
}
