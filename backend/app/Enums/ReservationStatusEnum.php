<?php

namespace App\Enums;

enum ReservationStatusEnum: string
{
    case ACTIVE = "active";

    case CHECKED_IN = "checked_in";

    case CANCELLED = "cancelled";

    case EXPIRED = "expired";

    public static function values()
    {
        return array_map(fn($val) => $val->value, self::cases());
    }
}
