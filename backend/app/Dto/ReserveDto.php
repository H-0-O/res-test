<?php

namespace App\Dto;

use DateTime;
use Illuminate\Support\Carbon;

class ReserveDto
{
    public Carbon $dateToReserve;

    public function __construct(
        public string $roomTypeId,
        public int $requestedCount,
        string|DateTime $dateToReserve
    ) {
        if (is_string($dateToReserve)) {
            $this->dateToReserve = Carbon::createFromDate($dateToReserve);
        } else if (is_a($dateToReserve, DateTime::class)) {
            $this->dateToReserve = Carbon::createFromDate($dateToReserve);
        }
    }
}
