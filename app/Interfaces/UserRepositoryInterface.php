<?php

namespace App\Interfaces;



interface UserRepositoryInterface {

    public function store(array $data);

    public function findByEmail(array $data);
}
