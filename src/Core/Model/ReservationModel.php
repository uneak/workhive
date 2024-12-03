<?php

    namespace App\Core\Model;

    use App\Core\Enum\ReservationStatus;
    use DateTime;

    /**
     * Interface for Reservation.
     */
    interface ReservationModel extends ObjectModel
    {

        /**
         * Get the room associated with the reservation.
         *
         * @return RoomModel|null
         */
        public function getRoom(): ?RoomModel;

        /**
         * Set the room associated with the reservation.
         *
         * @param RoomModel|null $room
         *
         * @return static
         */
        public function setRoom(?RoomModel $room): static;

        /**
         * Get the user who made the reservation.
         *
         * @return UserModel|null
         */
        public function getUser(): ?UserModel;

        /**
         * Set the user who made the reservation.
         *
         * @param UserModel|null $user
         *
         * @return static
         */
        public function setUser(?UserModel $user): static;

        /**
         * Get the start date and time of the reservation.
         *
         * @return DateTime
         */
        public function getStartAt(): DateTime;

        /**
         * Set the start date and time of the reservation.
         *
         * @param DateTime $startAt
         *
         * @return static
         */
        public function setStartAt(DateTime $startAt): static;

        /**
         * Get the end date and time of the reservation.
         *
         * @return DateTime
         */
        public function getEndAt(): DateTime;

        /**
         * Set the end date and time of the reservation.
         *
         * @param DateTime $endAt
         *
         * @return static
         */
        public function setEndAt(DateTime $endAt): static;

        /**
         * Get the current status of the reservation.
         *
         * @return ReservationStatus|null
         */
        public function getStatus(): ?ReservationStatus;

        /**
         * Set the current status of the reservation.
         *
         * @param ReservationStatus $status
         *
         * @return static
         */
        public function setStatus(ReservationStatus $status): static;

    }
