<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Order;
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
        $this->middleware('auth:sanctum')->only(['show', 'store']);
        $this->middleware('checkUserRole:1')->only(['show', 'store']);
        $this->middleware('checkUserRole:2')->only(['index']);
    }

    public function index()
    {
        $query = $this->loadRelationships(Order::query(), $this->relations);

        return OrderResource::collection($query->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    //First step (order_inquiry_sent)
    public function store(OrderRequest $request)
    {
        //TODO: CHECK IF USER ROLE IS CUSTOMER
        $data = $request->validated();

        //remove price if its somehow set
        unset($data['price']);

        // Set default values if not provided
        $data['customer_id'] = $data['customer_id'] ?? 1;
        $data['repair_status_id'] = $data['repair_status_id'] ?? 1;

        $order = Order::create($data);

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
    public function update(Request $request, string $id)
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
}
