<?php

namespace App\Services;

use App\Http\Traits\CanLoadRelationships;
use App\Interfaces\BaseServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class BaseService implements BaseServiceInterface
{
    use CanLoadRelationships;

    public function getAll(?Model $model = null, ?array $relationships = null)
    {
        return $this->loadRelationships($model::query(), $relationships);
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
