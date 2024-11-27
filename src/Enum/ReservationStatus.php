<?php

    namespace App\Enum;

    enum ReservationStatus: string
    {
        case PENDING = 'pending';
        case CONFIRMED = 'confirmed';
        case CANCELLED = 'cancelled';
    }
