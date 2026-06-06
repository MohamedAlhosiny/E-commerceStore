<?php

namespace App\Services\ExceptionHandler;

use Illuminate\Http\JsonResponse;

interface CategoryExceptionHandlerInterface
{
    public function handle(\Exception $e): JsonResponse;
}
