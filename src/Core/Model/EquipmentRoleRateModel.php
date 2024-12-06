<?php

    namespace App\Core\Model;

    use App\Core\Enum\UserRole;

    /**
     * Interface for EquipmentRoleRate.
     */
    interface EquipmentRoleRateModel extends ObjectModel
    {
        public const GROUP_PREFIX = 'equipment_role_rate';
        public const CREATE_GROUPS = [ObjectModel::CREATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::CREATE_PREFIX];
        public const UPDATE_GROUPS = [ObjectModel::UPDATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::UPDATE_PREFIX];
        public const WRITE_GROUPS = [...self::CREATE_GROUPS, ...self::UPDATE_GROUPS];
        public const READ_GROUPS = [ObjectModel::READ_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::READ_PREFIX];
        public const RW_GROUPS = [...self::READ_GROUPS, ...self::WRITE_GROUPS];

        /**
         * Get the equipment associated with this rate.
         *
         * @return EquipmentModel
         */
        public function getEquipment(): EquipmentModel;

        /**
         * Get the ID of the equipment associated with this rate.
         */
        public function getEquipmentId(): ?int;

        /**
         * Set the equipment associated with this rate.
         *
         * @param EquipmentModel $equipment
         *
         * @return static
         */
        public function setEquipment(EquipmentModel $equipment): static;

        /**
         * Get the role of the user for which this rate applies.
         *
         * @return UserRole
         */
        public function getUserRole(): UserRole;

        /**
         * Set the role of the user for which this rate applies.
         *
         * @param UserRole $userRole
         *
         * @return static
         */
        public function setUserRole(UserRole $userRole): static;

        /**
         * Get the hourly rate for using the equipment.
         *
         * @return float
         */
        public function getHourlyRate(): float;

        /**
         * Set the hourly rate for using the equipment.
         *
         * @param float $hourlyRate
         *
         * @return static
         */
        public function setHourlyRate(float $hourlyRate): static;
    }
