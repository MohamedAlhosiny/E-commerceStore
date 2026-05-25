<?php
namespace App\Interfaces;

interface AdminRepositoryInterface
{
    public function store(array $data);

    public function findByEmail(string $email);

    public function getAll();

    public function findById($id);

    public function delete($id);
}

