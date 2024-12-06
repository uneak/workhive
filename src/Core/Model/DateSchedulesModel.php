<?php

    namespace App\Core\Model;

    use DateTimeInterface;

    /**
     * Interface for DateSchedules.
     */
    interface DateSchedulesModel extends ObjectModel
    {
        public const GROUP_PREFIX = 'date_schedules';
        public const CREATE_GROUPS = [ObjectModel::CREATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::CREATE_PREFIX];
        public const UPDATE_GROUPS = [ObjectModel::UPDATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::UPDATE_PREFIX];
        public const WRITE_GROUPS = [...self::CREATE_GROUPS, ...self::UPDATE_GROUPS];
        public const READ_GROUPS = [ObjectModel::READ_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::READ_PREFIX];
        public const RW_GROUPS = [...self::READ_GROUPS, ...self::WRITE_GROUPS];
        /**
         * Get the date of the schedule.
         *
         * @return \DateTimeInterface|null
         */
        public function getDate(): ?DateTimeInterface;

        /**
         * Set the date of the schedule.
         *
         * @param \DateTimeInterface $date
         *
         * @return static
         */
        public function setDate(DateTimeInterface $date): static;

        /**
         * Get the starting time of the schedule.
         *
         * @return \DateTimeInterface|null
         */
        public function getStartedAt(): ?DateTimeInterface;

        /**
         * Set the starting time of the schedule.
         *
         * @param \DateTimeInterface $startedAt
         *
         * @return static
         */
        public function setStartedAt(DateTimeInterface $startedAt): static;

        /**
         * Get the ending time of the schedule.
         *
         * @return \DateTimeInterface|null
         */
        public function getEndedAt(): ?DateTimeInterface;

        /**
         * Set the ending time of the schedule.
         *
         * @param \DateTimeInterface $endedAt
         *
         * @return static
         */
        public function setEndedAt(DateTimeInterface $endedAt): static;

        /**
         * Check if the schedule is open during the specified time.
         *
         * @return bool|null
         */
        public function isOpen(): ?bool;

        /**
         * Set whether the schedule is open during the specified time.
         *
         * @param bool $isOpen
         *
         * @return static
         */
        public function setIsOpen(bool $isOpen): static;

        /**
         * Get the room associated with this schedule.
         *
         * @return RoomModel|null
         */
        public function getRoom(): ?RoomModel;

        /**
         * Get the ID of the room associated with this schedule.
         */
        public function getRoomId(): ?int;

        /**
         * Set the room associated with this schedule.
         *
         * @param RoomModel|null $room
         *
         * @return static
         */
        public function setRoom(?RoomModel $room): static;

        /**
         * Get the ID of the schedule.
         */
        public function getId(): ?int;
    }
