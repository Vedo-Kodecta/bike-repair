<?php

namespace App\Traits;

trait SearchableTrait
{
    public function scopeSearchByValue($query, string $value)
    {
        $searchValue = request('search');
        return $query->where($value, 'LIKE', '%' . $searchValue . '%');
    }
}
