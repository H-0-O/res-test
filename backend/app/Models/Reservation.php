<?php

namespace App\Models;

use App\Enums\ReservationStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasUlids;


    protected $table = "reservations";

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'room_type_id',
        'status',
        'date_to_reserve',
        'expiration_time',
        'requested_count'
    ];

    protected function casts(): array
    {
        return [
            'status' => ReservationStatusEnum::class
        ];
    }
}
