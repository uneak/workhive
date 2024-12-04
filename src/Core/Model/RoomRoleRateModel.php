<?php

    namespace App\Core\Model;

    use App\Core\Enum\UserRole;

    /**
     * Interface for RoomRoleRate.
     */
    interface RoomRoleRateModel extends ObjectModel
    {
        public const GROUP_PREFIX = 'room_role_rate';

        /**
         * Get the room associated with this rate.
         *
         * @return RoomModel
         */
        public function getRoom(): RoomModel;

        /**
         * Get the ID of the room associated with this rate.
         */
        public function getRoomId(): ?int;

        /**
         * Set the room associated with this rate.
         *
         * @param RoomModel $room
         *
         * @return static
         */
        public function setRoom(RoomModel $room): static;

        /**
         * Get the user role for which this rate applies.
         *
         * @return UserRole
         */
        public function getUserRole(): UserRole;

        /**
         * Set the user role for which this rate applies.
         *
         * @param UserRole $userRole
         *
         * @return static
         */
        public function setUserRole(UserRole $userRole): static;

        /**
         * Get the hourly rate for the room based on the user role.
         *
         * @return float
         */
        public function getHourlyRate(): float;

        /**
         * Set the hourly rate for the room based on the user role.
         *
         * @param float $hourlyRate
         *
         * @return static
         */
        public function setHourlyRate(float $hourlyRate): static;
    }
