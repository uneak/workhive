<?php

    namespace App\Core\Model;

    use App\Core\Enum\UserRole;

    /**
     * Interface for RoomRoleRate.
     */
    interface RoomRoleRateModel extends ObjectModel
    {
        public const GROUP_PREFIX = 'room_role_rate';
        public const CREATE_GROUPS = [ObjectModel::CREATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::CREATE_PREFIX];
        public const UPDATE_GROUPS = [ObjectModel::UPDATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::UPDATE_PREFIX];
        public const WRITE_GROUPS = [...self::CREATE_GROUPS, ...self::UPDATE_GROUPS];
        public const READ_GROUPS = [ObjectModel::READ_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::READ_PREFIX];
        public const RW_GROUPS = [...self::READ_GROUPS, ...self::WRITE_GROUPS];
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
