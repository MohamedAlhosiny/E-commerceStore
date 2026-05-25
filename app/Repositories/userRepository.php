<?php
namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function store(array $data)
    {
        return User::create($data);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function getAll()
    {
        return User::all(['id', 'name', 'email', 'created_at']);
    }

    public function findById($id)
    {
        return User::find($id);
    }

    public function delete($id)
    {
        return $this->findById($id)->delete();
    }
}
