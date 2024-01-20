<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class GlobalScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        //
    }

    /**
     * Scope method to check existance of value in a table
     *
     */
    public static function checkExistance(Model $model, string $parameter, $value): bool
    {
        $exists =  $model->where($parameter, $value)->exists();

        if (!$exists) {
            response()->json(['error' => 'Invalid parameter set (out of bound).'], 422)->send();
            exit;
        }

        return true;
    }
}
