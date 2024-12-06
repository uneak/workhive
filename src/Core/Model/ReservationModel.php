<?php

    namespace App\Core\Model;

    use App\Core\Enum\ReservationStatus;
    use DateTime;

    /**
     * Interface for Reservation.
     */
    interface ReservationModel extends ObjectModel
    {
        public const GROUP_PREFIX = 'reservation';
        public const CREATE_GROUPS = [ObjectModel::CREATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::CREATE_PREFIX];
        public const UPDATE_GROUPS = [ObjectModel::UPDATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::UPDATE_PREFIX];
        public const WRITE_GROUPS = [...self::CREATE_GROUPS, ...self::UPDATE_GROUPS];
        public const READ_GROUPS = [ObjectModel::READ_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::READ_PREFIX];
        public const RW_GROUPS = [...self::READ_GROUPS, ...self::WRITE_GROUPS];
        /**
         * Get the room associated with the reservation.
         *
         * @return RoomModel|null
         */
        public function getRoom(): ?RoomModel;

        /**
         * Get the ID of the room associated with the reservation.
         */
        public function getRoomId(): ?int;

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
         * Get the ID of the user who made the reservation.
         */
        public function getUserId(): ?int;

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
