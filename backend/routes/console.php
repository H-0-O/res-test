<?php

use App\Jobs\CleanupExpiredReservationJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new CleanupExpiredReservationJob)->everyMinute();
