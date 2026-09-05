<?php

namespace App\Services;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\AdminServiceInterface;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminService implements AdminServiceInterface
{
    public function __construct(private AdminRepositoryInterface $adminRepository)
    {
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['role'] = $data['role'] ?? 'admin';

        return $this->adminRepository->store($data); // composition over inheritance
    }

    public function login(string $email, string $password)
    {
        $admin = $this->adminRepository->findByEmail($email);

        if (!$admin || !Hash::check($password, $admin->password)) {
            return [
                'success' => false,
                'message' => 'Invalid credentials',
            ];
        }

        $token = $admin->createToken('tokenAdmin', ['role:'.$admin->role])->plainTextToken;

        return [
            'success' => true,
            'data' => [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
                'token' => $token,
            ],
            'message' => 'Admin login successfully',
        ];
    }

    public function logout()
    {
        $admin = Auth::user();

        if ($admin) {
            $admin->currentAccessToken()->delete();
        }

        return [
            'success' => true,
            'message' => 'Admin logout successfully',
        ];
    }

    public function index()
    {
        return $this->adminRepository->getAll();
    }

    public function dashboardStats()
    {
        return $this->adminRepository->dashboardStats();

    }

    public function destroy($id)
    {
        return $this->adminRepository->delete($id);
    }
}
