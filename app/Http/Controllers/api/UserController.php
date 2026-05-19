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
use App\Services\AuthService;

class UserController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private AuthService $authService)
    {
        $this->authService = $authService;
    }


    // register user
    public function store(userRequest $request)
    {

        $data_validated = $request->validated();

        $userRegister = $this->authService->register($data_validated);



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
        $authData = $this->authService->login($data);

        // dd($authData);

        if ($authData['message'] === 'Login successful') {
            return $this->successResponse($authData['data'], $authData['message'], 200);
        } else {
            return $this->errorResponse(null, $authData['message'], 401);
        }
    }


    public function logout() {
        $authLogout = $this->authService->logout();
        return $this->successResponse(null, $authLogout['message'], 200);
    }

    public function index()
    {
        $users = User::all(['id', 'name', 'email', 'created_at']);


        return $this->successResponse($users, 'All users are here', 200);
    }


    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {

            return $this->errorResponse(null, 'User not found', 404);
        }

        $user->delete();


        return $this->successResponse(null, 'User deleted successfully', 204);
    }
}
