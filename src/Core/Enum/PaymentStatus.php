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

        /**
         * Converts the enum cases into an associative array.
         *
         * @return array<string, string> An array where keys are the enum names and values are the enum values.
         */
        public static function casesAsArray(): array
        {
            $cases = [];
            foreach (self::cases() as $case) {
                $cases[$case->name] = $case->value;
            }

            return $cases;
        }
    }
