<?php

namespace App\Http\Controllers\Api;

use App\Enums\EStateMachineFunctions;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Order;
use App\Models\RepairStatus;
use App\Models\Scopes\GlobalScope;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    use CanLoadRelationships;
    /**
     * Display a listing of the resource.
     */

    public function __construct(protected OrderService $orderService)
    {
        $this->middleware('auth:sanctum')->only(['show', 'store', 'getOrdersWithRepairStatus']);
        $this->middleware('checkUserRole:1')->only(['store', 'destroy']);
    }

    public function index()
    {
        return $this->orderService->getAll(null, 'name');
    }

    /**
     * Store a newly created resource in storage.
     */
    //First step (order_inquiry_sent)
    public function store(OrderRequest $request)
    {
        return $this->orderService->create($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return $this->orderService->getOne($order);
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
        return $this->orderService->remove($order);
    }

    public function getOrdersWithRepairStatus(Order $order, int $status)
    {
        return $this->orderService->orderWithRepairStatus($order, $status);
    }

    /*
    *State machine API controller methods
    */
    //Second status (order_inquiry_recieved)
    public function setPrice(OrderRequest $request, Order $order)
    {
        $order = $this->orderService->updateOrderForSetPrice($request, $order);

        return $this->orderService->generateResponseForStateMachine($order, EStateMachineFunctions::SET_PRICE, $request);
    }

    //Third status (payment_sent)
    public function pay(Order $order)
    {
        return $this->orderService->generateResponseForStateMachine($order, EStateMachineFunctions::PAY,);
    }

    //Fourth status (order_in_progress)
    public function paymentAccepted(Order $order)
    {
        return $this->orderService->generateResponseForStateMachine($order, EStateMachineFunctions::PAYMENT_ACCEPTED,);
    }

    //Fifth status (order_ready)
    public function finalizeOrder(Order $order)
    {
        return $this->orderService->generateResponseForStateMachine($order, EStateMachineFunctions::FINALIZE_ORDER,);
    }

    //Sixth status (order_failed)
    public function cancelOrder(Order $order)
    {
        return $this->orderService->generateResponseForStateMachine($order, EStateMachineFunctions::CANCEL_ORDER,);
    }

    public function availableFunctions(Order $order)
    {
        $status = $this->loadRelationships($order, ['repairStatus'])->state();
        return GlobalScope::getAvailableFunctions($status);
    }
}
