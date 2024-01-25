<?php

namespace App\Services;

use App\Http\Traits\CanLoadRelationships;
use App\Interfaces\BaseServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class BaseService implements BaseServiceInterface
{
    use CanLoadRelationships;

    public function getAll(?Model $model = null, ?string $searchParameter, ?array $relationships = null)
    {
        $query = $model::query();

        if ($searchParameter) {
            $query->searchByValue($searchParameter);
        }

        return $this->loadRelationships($query, $relationships);
    }

    public function getOne(Model $model, ?array $relationships = null)
    {
        return $this->loadRelationships($model);
    }

    public function create($request)
    {
        return $this->loadRelationships($request);
    }

    public function update(Request $request, Model $model)
    {
        return $this->loadRelationships($model::query());
    }

    public function remove(Model $model)
    {
        return $model->delete();
    }
}
