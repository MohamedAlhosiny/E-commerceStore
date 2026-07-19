<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;



namespace App\Http\Controllers\Api;

use App\Exceptions\OrderProductUnavailableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\orderRequest as OrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Interfaces\OrderServiceInterface;
use App\Interfaces\OrderStatusServiceInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private OrderServiceInterface $orderService,
        private OrderStatusServiceInterface $orderStatusService
    )
    {
    }

    public function index(Request $request)
    {

        $perPage = $request->integer('per_page', 10);
        $orders = $this->orderService->getAll($perPage);
        $checkOrders = $orders->isNotEmpty() ? true : false ;


        if (!$checkOrders) {
            return $this->errorResponse(null, 'No orders found.', 404);
        }
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
            $orderRequest = $request->validated();
            $order = $this->orderService->createOrder(Auth::id(), $orderRequest["products"]);

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



    public function controlStatus(string $id, UpdateOrderStatusRequest $request)
    {
        try {
            $order = $this->orderStatusService->changeStatus($id, $request->validated()['status']);

            return $this->successResponse(
                new OrderResource($order),
                'Order status updated successfully.',
                200
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse(null, 'order not found to change status', 404);
        } catch (\DomainException $e) {
            return $this->errorResponse(null, $e->getMessage(), 400);
        } catch (\Exception $e) {
            $status = $e->getCode();
            if (!is_int($status) || $status < 400) {
                $status = 500;
            }

            return $this->errorResponse(null, $e->getMessage(), $status);
        }
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
