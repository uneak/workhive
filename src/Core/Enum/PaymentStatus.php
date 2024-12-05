<?php

    namespace App\Core\Enum;

    use OpenApi\Attributes as OA;

    #[OA\Schema(
        description: 'Status of a payment',
        type: 'string',
        enum: ['pending', 'completed', 'failed']
    )]
    enum PaymentStatus: string
    {
        case PENDING = 'pending';
        case COMPLETED = 'completed';
        case FAILED = 'failed';
    }
