<?php

    namespace App\Enum;

    enum Status: string
    {
        case ACTIVE = 'active';
        case INACTIVE = 'inactive';

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
