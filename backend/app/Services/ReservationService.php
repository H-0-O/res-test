<?php

namespace App\Services;

use App\Dto\ReserveDto;
use App\Enums\ReservationStatusEnum;
use App\Exceptions\NoAvailabilityException;
use App\Exceptions\OverSellingAttemptException;
use App\Models\Inventory;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new Reservation);
    }

    public function reserve(ReserveDto $dto)
    {

        return DB::transaction(function () use ($dto) {
            $inventory = Inventory::with("roomType")
                ->where("room_type_id", $dto->roomTypeId)
                ->where("date", $dto->dateToReserve)
                // I used row locking to prevent overselling in concurrent situation,
                // there are other ways to handle the problem and it completely depends on load and requests count  
                // but for test I kep it simple 
                ->lockForUpdate()
                ->first();
            if (!$inventory || $inventory->available_rooms == 0) {
                throw new NoAvailabilityException(
                    $dto->roomTypeId,
                    $dto->dateToReserve->format('Y-m-d')
                );
            }
            if ($inventory->available_rooms - $dto->requestedCount < 0) {
                throw new OverSellingAttemptException(
                    $inventory->roomType->name,
                    $dto->dateToReserve->format('Y-m-d'),
                    $dto->requestedCount,
                    $inventory->available_rooms
                );
            }
            $reservation = Reservation::create([
                // because we don't have authentication and login for now , I just fetch test user from db 
                'user_id' => User::first()->id,
                'room_type_id' => $dto->roomTypeId,
                'status' => ReservationStatusEnum::ACTIVE,
                'requested_count' => $dto->requestedCount,
                'date_to_reserve' => $dto->dateToReserve,
                'expiration_time' => Carbon::now()->addMinutes(config('reservation.expiration_minutes'))->setSeconds(0)
            ]);

            $inventory->available_rooms -= $dto->requestedCount;
            $inventory->save();

            return $reservation;
        });
    }


    public function cleanUpExpiredReservations()
    {
        //if we have hight rows we should use chunking here to break the transaction into smaller pieces
        // and release locks faster and avoid dead lock but for now for now I kep it simple 
        $expired = Reservation::where('status', ReservationStatusEnum::ACTIVE->value)
            ->where('expiration_time', '<=', now())
            ->get();

        if ($expired->isEmpty()) return;

        DB::transaction(function () use ($expired) {
            foreach ($expired as $reservation) {
                Inventory::where('room_type_id', $reservation->room_type_id)
                    ->where('date', $reservation->date_to_reserve)
                    ->lockForUpdate()
                    ->increment('available_rooms', $reservation->requested_count);

                $reservation->status = ReservationStatusEnum::EXPIRED;
                $reservation->save();
            }
        });
    }
}
