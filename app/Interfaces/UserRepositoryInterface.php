<?php

namespace App\Interfaces;



interface UserRepositoryInterface {

    public function store(array $data);

    public function findByEmail(array $data); // to login

    public function index();
    public function show($id);

    public function destroy($id);

}
