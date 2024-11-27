<?php

    namespace App\Enum;
    enum UserRole: string
    {
        case MEMBER = 'member';
        case USER = 'user';
        case ADMIN = 'admin';

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
