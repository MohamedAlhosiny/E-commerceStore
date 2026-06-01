<?php

namespace App\Interfaces;

interface AdminServiceInterface
{
    public function register(array $data);

    public function login(string $email, string $password);

    public function logout();

    public function index();

    public function dashboardStats();
    
    public function destroy($id);
}
