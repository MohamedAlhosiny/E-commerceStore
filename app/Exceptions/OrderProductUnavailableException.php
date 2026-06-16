<?php

namespace App\Exceptions;

use Exception;

class OrderProductUnavailableException extends Exception
{
    public function __construct(
        protected array $errors,
        string $message = 'One or more products are unavailable for this order.',
        int $code = 422
    ) {
        parent::__construct($message, $code);
        // dd($code , $message);
    }

    public function errors(): array
    {
        // dd($this->errors);
        return $this->errors;
    }
}
