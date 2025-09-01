<?php

namespace App\Exceptions;

use Exception;

class NoAvailabilityException extends Exception
{
    public function __construct(string $roomTypeId, string $date)
    {
        $message = "No availability for room type {$roomTypeId} on {$date}.";
        parent::__construct($message);
    }
}
