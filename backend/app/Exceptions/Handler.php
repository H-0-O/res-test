<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler
{
   
    public static function render(Throwable $e)
    {
        $message = $e->getMessage() ?: 'HTTP Error';

        $details = [];
        $code = 500;
        if ($e instanceof ValidationException) {
            $message = 'Validation failed';
            $details = $e->errors();
            $code = 422;
        }

        return Response::gen(null, null, [
            'message' => $message,
            'details' => $details
        ], $code);
    }
}
