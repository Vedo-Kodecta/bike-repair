<?php

namespace App\Models\Scopes;

use App\Helpers\Utility;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;

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
     */
    public static function checkExistance(Model $model, string $parameter, $value): bool
    {
        $exists =  $model->where($parameter, $value)->exists();

        if (!$exists) {
            response()->json(['error' => 'Invalid parameter set (out of bound)'], 422)->send();
            exit;
        }

        return true;
    }

    /**
     * Utility method to get available functions for a given state.
     */
    public static function getAvailableFunctions($state): array
    {
        return Utility::getAvailableFunctions($state);
    }

    /**
     * Update state machine and perform additional functionality.
     */
    public static function updateStateMachine(Model $model, ?array $relationships = null, JsonResource|string $resource, ?callable $additionalFunction = null)
    {
        if ($relationships) {
            $model = $model->load($relationships);
        }

        if (!$model->repairStatus) {
            return response()->json(['message' => 'Order does not have a repair status'], 400);
        }

        try {
            $status = $model->state();

            // Call additional function if provided
            if ($additionalFunction && is_callable($additionalFunction)) {
                $additionalFunction($status, $model);
            }

            return $resource::make($model->load($relationships));
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update order status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Manually check if field is empty in request
     */
    public static function checkIfFieldIsEmpty(Request $request, string $field)
    {
        if (!$request->filled($field)) {
            throw ValidationException::withMessages([
                $field => ['The ' . $field . ' field is required.']
            ]);
        }

        return null;
    }
}
