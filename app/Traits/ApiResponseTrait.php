<?php

namespace App\Traits;

trait ApiResponseTrait
{
    protected function successResponse($data = null, $message = 'success', $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'success' => true,
            'status' => $status,
        ], $status);
    }

    protected function errorResponse($data = null, $message = 'error', $status = 400)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'success' => false,
            'status' => $status,
        ], $status);
    }
}
