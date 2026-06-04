<?php

namespace App\Traits;

trait ApiResponseTrait
{
    protected function successResponse($data = null, $message = 'success', $status = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status,
            // 'success' => true,

        ], $status);
    }

    protected function errorResponse($data = null, $message = 'error', $status = 400)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status,
            // 'success' => false,

        ], $status);
    }

    protected function notFoundResponse($message = 'Resource not found')
    {
        return $this->errorResponse(null, $message, 404);
    }

    protected function createdResponse($data = null, $message = 'Resource created successfully' , $status = 201 )
    {
        return $this->successResponse($data, $message, $status );
    }

}
