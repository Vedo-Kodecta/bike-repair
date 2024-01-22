<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Order;
use App\Models\RepairStatus;
use App\Models\Scopes\GlobalScope;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    use CanLoadRelationships;
    /**
     * Display a listing of the resource.
     */
    private array $relations = ['mechanic', 'customer', 'repairStatus'];

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['show', 'store', 'getOrdersWithRepairStatus']);
        $this->middleware('checkUserRole:1')->only(['show', 'store', 'destroy']);
        //$this->middleware('checkUserRole:2')->only(['index']);
    }

    public function index()
    {
        $query = $this->loadRelationships(Order::query());

        return OrderResource::collection($query->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    //First step (order_inquiry_sent)
    public function store(OrderRequest $request)
    {
        $data = $request->validated();

        $order = Order::createOrder($data);

        return OrderResource::make($this->loadRelationships($order));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return OrderResource::make($this->loadRelationships($order));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response(status: 204)->json([
            'message' => 'Order deleted successfully'
        ]);
    }

    public function getOrdersWithRepairStatus(Order $order, int $status)
    {
        GlobalScope::checkExistance(new RepairStatus(), 'id', $status);

        $orders = $order->where('repair_status_id', $status);

        return OrderResource::collection($this->loadRelationships($orders)->latest()
            ->paginate());
    }

    public function setPrice(Order $order)
    {
        $order = $this->loadRelationships($order, ['repairStatus']);

        if (!$order->repairStatus) {
            return response()->json(['message' => 'Order does not have a repair status.'], 400);
        }

        try {
            $status = $this->loadRelationships($order, ['repairStatus'])->state();
            $status->set_price();

            return OrderResource::make($this->loadRelationships($order, ['repairStatus']));
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update order status.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
