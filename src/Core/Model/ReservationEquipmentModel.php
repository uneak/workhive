<?php

    namespace App\Core\Model;

    use DateTime;

    /**
     * Interface for ReservationEquipment.
     */
    interface ReservationEquipmentModel extends ObjectModel
    {

        /**
         * Get the reservation associated with this equipment.
         *
         * @return ReservationModel
         */
        public function getReservation(): ReservationModel;

        /**
         * Set the reservation associated with this equipment.
         *
         * @param ReservationModel $reservation
         *
         * @return static
         */
        public function setReservation(ReservationModel $reservation): static;

        /**
         * Get the equipment associated with this reservation.
         *
         * @return EquipmentModel
         */
        public function getEquipment(): EquipmentModel;

        /**
         * Set the equipment associated with this reservation.
         *
         * @param EquipmentModel $equipment
         *
         * @return static
         */
        public function setEquipment(EquipmentModel $equipment): static;

        /**
         * Get the quantity of the equipment reserved.
         *
         * @return int
         */
        public function getQuantity(): int;

        /**
         * Set the quantity of the equipment reserved.
         *
         * @param int $quantity
         *
         * @return static
         */
        public function setQuantity(int $quantity): static;
    }
