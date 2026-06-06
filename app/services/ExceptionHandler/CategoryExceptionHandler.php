<?php

namespace App\Services\ExceptionHandler;

use App\Traits\ApiResponseTrait;
use App\Services\ExceptionHandler\CategoryExceptionHandlerInterface;
use Illuminate\Http\JsonResponse;

class CategoryExceptionHandler implements CategoryExceptionHandlerInterface
{
    use ApiResponseTrait;

    public function handle(\Exception $e): JsonResponse
    {
        if ($this->isForeignKeyViolation($e)) {
            return $this->foreignKeyResponse();
        }

        if ($this->isNotFound($e)) {
            return $this->notFoundResponse($e->getMessage());
        }

        return $this->genericErrorResponse($e);
    }

    private function isForeignKeyViolation(\Exception $e): bool
    {
        $message = $e->getMessage();
        return strpos($message, 'Integrity constraint') !== false ||
               strpos($message, 'foreign key') !== false;
    }

    private function isNotFound(\Exception $e): bool
    {
        return $e->getCode() === 404 || strpos($e->getMessage(), 'not found') !== false;
    }

    private function foreignKeyResponse(): JsonResponse
    {
        return $this->errorResponse(
            null,
            'Cannot delete this category because it is associated with existing products.',
            409
        );
    }

    private function genericErrorResponse(\Exception $e): JsonResponse
    {
        return $this->errorResponse(
            null,
            $e->getMessage(),
            $e->getCode() ?: 500
        );
    }
}
