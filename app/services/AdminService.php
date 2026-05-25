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

        return $this->adminRepository->store($data);
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
        return [
            'all-users' => User::count(),
            'all-admins' => $this->adminRepository->getAll()->count(),
            'all-products' => Product::count(),
            'all-orders' => Order::count(),
            'total-revenue' => Order::where('status', 'completed')->sum('totalPrice'),
            'top-selling-product' => Product::withCount('orders')
                ->orderByDesc('orders_count')
                ->first(['id', 'name', 'orders_count']),
        ];
    }

    public function destroy($id)
    {
        return $this->adminRepository->delete($id);
    }
}
