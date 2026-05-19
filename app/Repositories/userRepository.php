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

    public function index() {
        return User::all(['id', 'name', 'email', 'created_at']);
    }

    public function show($id) {
        $getUser = User::find($id);
        return $getUser;
    }

    public function destroy($id)
    {
            return $this->show($id)->delete();
    }

}
