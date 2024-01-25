<?php

namespace App\Services;

use App\Enums\EStateMachineFunctions;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\RepairStatus;
use App\Models\Scopes\GlobalScope;
use App\Traits\PaginationTrait;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class OrderService extends BaseService
{

    use PaginationTrait;
    private array $relations = ['mechanic', 'customer', 'repairStatus'];

    public function getAll(?Model $model = null, ?string $searchParameter = null, ?array $relationships = null)
    {
        $model = $model ?? Order::class;
        $relationships = $relationships ?? $this->relations;

        $data = parent::getAll(new $model, $searchParameter, $relationships);

        FilterService::apply($data);

        return OrderResource::collection($this->customPagination($data->get()));
    }

    public function getOne(Model $model, ?array $relationships = null)
    {
        $relationships = $relationships ?? $this->relations;

        $data = parent::getOne($model, $relationships);

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

        return GlobalScope::returnJSONMessage(204, "Order deleted successfully");
    }

    public function orderWithRepairStatus(Order $order, int $status)
    {
        GlobalScope::checkExistance(new RepairStatus(), 'id', $status);

        $orders = $order->where('repair_status_id', $status);

        return OrderResource::collection(
            $this->loadRelationships($orders, $this->relations)
                ->latest()->paginate()
        );
    }

    public function updateOrderForSetPrice(OrderRequest $request, Order $order)
    {
        GlobalScope::checkIfFieldIsEmpty($request, 'price');

        return GlobalScope::addCurrentUserValueToModel($order, 'mechanic_id');
    }

    private function getFunctionForOrderState(string $eStateMachineFunctions, $status, $order, $request)
    {
        switch ($eStateMachineFunctions) {
            case EStateMachineFunctions::SET_PRICE:
                $status->set_price();
                $order->update($request->validated());
                break;

            case EStateMachineFunctions::PAY:
                return $status->pay();

            case EStateMachineFunctions::PAYMENT_ACCEPTED:
                return $status->payment_accepted();

            case EStateMachineFunctions::FINALIZE_ORDER:
                return $status->finalize_order();

            case EStateMachineFunctions::CANCEL_ORDER:
                return $status->cancel_order();

            default:
                throw new InvalidArgumentException('Invalid status');
        }
    }

    public function generateResponseForStateMachine(
        Order $order,
        string $eStateMachineFunctions,
        OrderRequest $request = null,
    ) {
        return GlobalScope::updateStateMachine(
            $order,
            ['repairStatus'],
            OrderResource::class,
            function ($status, $order) use ($request, $eStateMachineFunctions) {
                $this->getFunctionForOrderState($eStateMachineFunctions, $status, $order, $request);
            }
        );
    }
}
