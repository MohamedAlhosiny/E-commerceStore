<?php
namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;

use App\Models\User;

class userRepository implements UserRepositoryInterface {

public function store(array $data) {


        return User::create($data);
    }

    public function findByEmail(array $data) {
        return User::where('email' , $data['email'])->first();

    }
}
