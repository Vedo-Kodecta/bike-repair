<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface BaseServiceInterface
{
    public function getAll(?Model $model, ?array $relationships);
    public function getOne(Model $model, ?array $relationships);
    public function create(mixed $request);
    public function update(Request $request, Model $model);
    public function remove(Model $model);
}
