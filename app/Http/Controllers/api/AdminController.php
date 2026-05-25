<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\adminRequest;
use App\Http\Requests\loginRequestAdmin;
use App\Interfaces\AdminServiceInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private AdminServiceInterface $adminService)
    {
    }

    public function register(adminRequest $request)
    {
        $admin = $this->adminService->register($request->validated());

        return $this->successResponse([
            $admin->name ,
             $admin->email,
              $admin->role]
              ,'Admin created successfully', 201);
    }

    public function login(loginRequestAdmin $request)
    {
        $result = $this->adminService->login($request->email, $request->password);

        if (!$result['success']) {
            return $this->errorResponse(null, $result['message'], 401);
        }

        return $this->successResponse($result['data'], $result['message'], 200);
    }

    public function index()
    {
        $admins = $this->adminService->index();

        return $this->successResponse($admins, 'list of all admins', 200);
    }

    public function dashboardStats()
    {
        $stats = $this->adminService->dashboardStats();

        return $this->successResponse($stats, 'dashboard statistics', 200);
    }

    public function logout()
    {
        $result = $this->adminService->logout();

        return $this->successResponse(null, $result['message'], 200);
    }

    public function destroy($id)
    {
        $deleted = $this->adminService->destroy($id);

        if (!$deleted) {
            return $this->errorResponse(null, 'Admin not found', 404);
        }

        return $this->successResponse(null, 'Admin deleted successfully', 200);
    }
}
