<?php

namespace App\Exceptions;

use Exception;

class OverSellingAttemptException extends Exception
{
    public function __construct(string $roomTypeName, string $date, int $requested, int $available)
    {
        $message = "Requested {$requested} room(s), but only {$available} available for {$roomTypeName} on {$date}.";
        parent::__construct($message);
    }
}
