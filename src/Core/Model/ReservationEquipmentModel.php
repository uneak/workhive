<?php

    namespace App\Core\Model;

    use DateTime;

    /**
     * Interface for ReservationEquipment.
     */
    interface ReservationEquipmentModel extends ObjectModel
    {
        public const GROUP_PREFIX = 'reservation_equipment';

        /**
         * Get the reservation associated with this equipment.
         *
         * @return ReservationModel
         */
        public function getReservation(): ReservationModel;

        /**
         * Get the ID of the reservation associated with this equipment.
         */
        public function getReservationId(): ?int;

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
         * Get the ID of the equipment associated with this reservation.
         */
        public function getEquipmentId(): ?int;

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
