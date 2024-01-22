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
use ReflectionClass;
use ReflectionMethod;

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
        $this->middleware('checkUserRole:1')->only(['show', 'store', 'destroy',]);
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

    /*
    *State machine API controller methods
    */
    //Second status (order_inquiry_recieved)
    public function setPrice(OrderRequest $request, Order $order)
    {
        GlobalScope::checkIfFieldIsEmpty($request, 'price');

        $response = GlobalScope::updateStateMachine(
            $order,
            ['repairStatus'],
            OrderResource::class,
            function ($status, $order) use ($request) {
                $status->set_price();
                $order->update($request->validated());
            }
        );

        return $response;
    }

    //Third status (payment_sent)
    public function pay(Order $order)
    {
        $response = GlobalScope::updateStateMachine(
            $order,
            ['repairStatus'],
            OrderResource::class,
            function ($status) {
                $status->pay();
            }
        );

        return $response;
    }

    //Fourth status (order_in_progress)
    public function paymentAccepted(Order $order)
    {
        $response = GlobalScope::updateStateMachine(
            $order,
            ['repairStatus'],
            OrderResource::class,
            function ($status) {
                $status->payment_accepted();
            }
        );

        return $response;
    }

    //Fifth status (order_ready)
    public function finalizeOrder(Order $order)
    {
        $response = GlobalScope::updateStateMachine(
            $order,
            ['repairStatus'],
            OrderResource::class,
            function ($status) {
                $status->finalize_order();
            }
        );

        return $response;
    }

    //Sixth status (order_failed)
    public function cancelOrder(Order $order)
    {
        $response = GlobalScope::updateStateMachine(
            $order,
            ['repairStatus'],
            OrderResource::class,
            function ($status) {
                $status->cancel_order();
            }
        );

        return $response;
    }

    public function availableFunctions(Order $order)
    {
        try {
            $status = $this->loadRelationships($order, ['repairStatus'])->state();
            $availableFunctions = GlobalScope::getAvailableFunctions($status);

            return response()->json([
                'message' => 'List of available functions for order status',
                'functions' => $availableFunctions,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve available functions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
