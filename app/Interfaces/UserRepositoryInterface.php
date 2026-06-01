<?php

namespace App\Interfaces;



interface UserRepositoryInterface {

    public function store(array $data) ;

    public function findByEmail(string $email); // to login

    public function getAll();
    public function findById($id);

    public function delete($id);


}
