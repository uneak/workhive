<?php

    namespace App\Core\Enum;

    enum PaymentStatus: string
    {
        case PENDING = 'pending';
        case COMPLETED = 'completed';
        case FAILED = 'failed';
    }
