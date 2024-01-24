<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Scopes\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class OrderService extends BaseService
{

    private array $relations = ['mechanic', 'customer', 'repairStatus'];

    public function getAll(?Model $model = null, ?array $relationships = null)
    {
        $model = $model ?? Order::class;
        $relationships = $relationships ?? $this->relations;

        $data = parent::getAll(new $model, $relationships);

        //paginacija ide ovde
        return OrderResource::collection($data->latest()->paginate());
    }

    public function getOne(Model $model, ?array $relationships = null)
    {
        $relationships = $relationships ?? $this->relations;

        $data = parent::getOne($model, $relationships);

        //paginacija ide ovde
        return OrderResource::make($data);
    }

    public function create($request)
    {
        $data = GlobalScope::addCurrentUserValueToRequest($request, 'customer_id');
        $order = Order::createOrder($data);

        return OrderResource::make(parent::create($order, $this->relations));
    }

    public function remove(Model $model)
    {
        parent::remove($model);

        return response(status: 204)->json([
            'message' => 'Order deleted successfully'
        ]);
    }
}
