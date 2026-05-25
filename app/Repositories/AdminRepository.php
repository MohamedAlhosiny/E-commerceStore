<?php

namespace App\Repositories;

use App\Interfaces\AdminRepositoryInterface;
use App\Models\Admin;

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
}

