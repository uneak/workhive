<?php

    namespace App\Core\Enum;

    use OpenApi\Attributes as OA;

    #[OA\Schema(
        description: 'Status of a room reservation',
        type: 'string',
        enum: ['pending', 'confirmed', 'cancelled']
    )]
    enum ReservationStatus: string
    {
        case PENDING = 'pending';
        case CONFIRMED = 'confirmed';
        case CANCELLED = 'cancelled';

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
