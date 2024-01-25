<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class FilterService
{
    public static function apply(Builder $query)
    {
        $filters = explode(',', request('filters'));

        foreach ($filters as $filter) {
            if (method_exists(self::class, $filter)) {
                self::$filter($query, request($filter));
            }
        }
        return $query;
    }

    public static function expensive(Builder $query, $value)
    {
        $query->where('price', '>', 100);
    }

    public static function cheap(Builder $query, $value)
    {
        $query->where('price', '<=', 100);
    }
}
