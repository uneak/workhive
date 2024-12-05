<?php

    namespace App\Core\Enum;
    use OpenApi\Attributes as OA;

    #[OA\Schema(
        description: 'User role in the system',
        type: 'string',
        enum: ['member', 'user', 'admin']
    )]
    enum UserRole: string
    {
        case ROLE_MEMBER = 'member';
        case ROLE_USER = 'user';
        case ROLE_ADMIN = 'admin';

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
