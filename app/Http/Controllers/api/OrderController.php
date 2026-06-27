<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;



namespace App\Http\Controllers\Api;

use App\Exceptions\OrderProductUnavailableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\orderRequest as OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Notifications\OrderstausUpdated;
use App\Interfaces\OrderServiceInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private OrderServiceInterface $orderService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 10);
        $orders = $this->orderService->getAll($perPage);

        return $this->successResponse(
            OrderResource::collection($orders),
            'All orders retrieved successfully.',
            200
        );
    }


    //=======================================


    public function myorders(Request $request)
    {
        $userId = Auth::id();

        if (!$userId) {
            return $this->errorResponse(null, 'Unauthenticated user.', 401);
        }

        $perPage = $request->integer('per_page', 10);
        $myorders = $this->orderService->getMyOrders($userId, $perPage);

        if ($myorders->total() === 0) {
            return $this->errorResponse(null, 'No orders found for this user.', 404);
        }

        return $this->successResponse(
            OrderResource::collection($myorders),
            'User orders retrieved successfully.',
            200
        );
    }




    //============================================================

    public function store(OrderRequest $request)
    {
        if (!Auth::id()) {
            return $this->errorResponse(null, 'Unauthenticated user.', 401);
        }



        try {
            $order = $this->orderService->createOrder(Auth::id(), $request->validated()['products']);

            return $this->createdResponse(
                new OrderResource($order),
                'Order created successfully.',
                201
            );
        } catch (OrderProductUnavailableException $e) {
            return $this->errorResponse(
                [
                    'errors' => $e->errors(),
                ],
                $e->getMessage(),
                422
            );
        } catch (\Exception $e) {
            $status = $e->getCode();

            if (!is_int($status) || $status < 400) {
                $status = 500;
            }

            return $this->errorResponse(null, $e->getMessage(), $status);
        }
    }





    //=======================================================================



    public function controlStatus(string $id, Request $request)
    {

        $orderStatus = Order::find($id);
        if (!$orderStatus) {

            return $this->errorResponse(null, 'order not found to change status', 404);
        }
        // logger($orderStatus);
        $nameProductInOrder = $orderStatus->products->pluck('pivot.product_name')->join(' ,');
        // logger($nameProductInOrder);

        $currentStatus = $orderStatus->status;
        $newStatus = $request->status;
        $allowedStatuses = ['pending', 'processing', 'completed', 'cancelled'];

        if (!in_array($newStatus, $allowedStatuses)) {
            return response()->json([
                'message' => 'invalid status value',
                'allowed statuses' => $allowedStatuses,
                'status' => 400
            ], 400);
        }



        // allowed transitions ===
        $validTransition = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => []
        ];



        //====
        if (!in_array($newStatus, $validTransition[$currentStatus])) {
            return response()->json([
                'message' => "invalid status transition from {$currentStatus} to {$newStatus}",
                'allowed transions' => $validTransition,
                'status' => 400
            ], 400);
        }






        $orderStatus->update([
            'status' => $request->status
        ]);



        $user_name = Auth::user()->name;
        $oderID = $orderStatus->id;
        $orderStatus->user->notify(new OrderstausUpdated($oderID, $currentStatus, $newStatus, $user_name));


        return response()->json([
            'message' => 'this status for order',
            'aboutOrder' => "the order for  {$nameProductInOrder} has status {$currentStatus}",
            'newStatus' => "the order updated it status successfully to {$newStatus}",
            'Notification sent to user' => true,
            'success' => true,
            'status' => 200
        ], 200);
    }



    public function show(string $id)
    {



        $orderDetails = Order::with(['user:id,name', 'products:name,price', 'products.category:name'])->find($id);
        if (!$orderDetails) {

            return $this->errorResponse(null, 'order not found to show details', 404);
        }


        return $this->successResponse($orderDetails, 'order details retrieved successfully', 200);
    }


    public function update(Request $request, Order $order)
    {
       
    }


    public function destroy(string $id)
    {
        $orderToDelete = Order::find($id);
        if (!$orderToDelete) {

            return $this->errorResponse(null, 'order not found to delete', 404);
        }

        $orderToDelete->delete();

        return $this->successResponse(null, 'order deleted successfully', 204);
    }
}
