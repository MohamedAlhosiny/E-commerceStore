<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class NotificationController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        if (!auth()->user()) {

            return $this->errorResponse(null , 'unothorized' , 401);


        }else {
            $user = auth()->user();
            $notifications  = $user->notifications;
            return $this->successResponse($notifications , "user {$user->name} notifications retrieved successfully" , 200);
        }
    }

   
}
