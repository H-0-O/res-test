<?php

namespace App\Jobs;

use App\Services\ReservationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CleanupExpiredReservationJob implements ShouldQueue
{
    use Queueable;


    /**
     * Execute the job.
     */
    public function handle(ReservationService $reservationService): void {
        $reservationService->cleanUpExpiredReservations();
    }
}
