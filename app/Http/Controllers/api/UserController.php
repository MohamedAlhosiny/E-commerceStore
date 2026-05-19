<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\userRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\loginRequestUser;
use App\Traits\ApiResponseTrait;
use App\Interfaces\UserRepositoryInterface;
use App\Services\UserService;

class UserController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private UserService $userService)
    {
        $this->userService = $userService;
    }


    // register user
    public function store(userRequest $request)
    {

        $data_validated = $request->validated();

        $userRegister = $this->userService->register($data_validated);



        if ($userRegister) {
            return $this->successResponse([
                $userRegister->name,
                $userRegister->email
            ], 'Hello user ' . $userRegister->name . " in our App", 201);
        } else {

            return $this->errorResponse(null, 'something is not valid', 422);
        }
    }




    public function login (loginRequestUser $request) {
        $data = $request->validated();
        $authData = $this->userService->login($data);

        // dd($authData);

        if ($authData['message'] === 'Login successful') {
            return $this->successResponse($authData['data'], $authData['message'], 200);
        } else {
            return $this->errorResponse(null, $authData['message'], 401);
        }
    }


    public function logout() {
        $authLogout = $this->userService->logout();
        return $this->successResponse(null, $authLogout['message'], 200);
    }



    //============ SuperAdmin only ============





    public function index(){
        $users = $this -> userService -> index();

        return $this->successResponse($users, 'All clients are here', 200);
    }

    public function show($id) {
            $getUser = $this -> userService ->show($id);
            if (!$getUser) {
                return $this -> errorResponse(null , 'User not found' , 404);
            }

            return $this -> successResponse($getUser , 'User found' , 200);
    }


    public function destroy($id) {
        $destroyUser = $this -> userService -> destroy($id);
        if (!$destroyUser) {
            return $this -> errorResponse(null , 'User not found' , 404);
        }
        return $this -> successResponse(null , 'User deleted successfully' , 204);

    }

}
