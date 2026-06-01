<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminRepository implements AdminRepositoryInterface
{
    public function store(array $data)
    {
        return Admin::create($data);
    }

    public function findByEmail(string $email)
    {
        return Admin::where('email', $email)->first();
    }

    public function getAll()
    {
        return Admin::all(['id', 'name', 'email', 'role', 'created_at']);
    }

    public function findById($id)
    {
        return Admin::find($id);
    }

    public function delete($id)
    {
        $admin = $this->findById($id);

        if (!$admin) {
            return false;
        }

        return $admin->delete();
    }

    public function dashboardStats()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $allAdmins = Admin::all(['id', 'name', 'email', 'role', 'created_at']);
        $totalRevenue = Order::where('status', 'completed')->sum('totalPrice');
        $topSellingProduct = Product::withCount('orders')
            ->orderByDesc('orders_count')
            ->first(['id', 'name', 'orders_count']);


        return [
            'total_users' => $totalUsers,
            'total_products' => $totalProducts,
            'total_orders' => $totalOrders,
            'all_admins' => $allAdmins,
            'total_revenue' => $totalRevenue,
            'top_selling_product' => $topSellingProduct,
        ];
    }
}
