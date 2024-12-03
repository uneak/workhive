<?php

    namespace App\Core\Model;

    use App\Core\Enum\UserRole;

    /**
     * Interface for EquipmentRoleRate.
     */
    interface EquipmentRoleRateModel extends ObjectModel
    {


        /**
         * Get the equipment associated with this rate.
         *
         * @return EquipmentModel
         */
        public function getEquipment(): EquipmentModel;

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
