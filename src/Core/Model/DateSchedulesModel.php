<?php

    namespace App\Core\Model;

    use DateTimeInterface;

    /**
     * Interface for DateSchedules.
     */
    interface DateSchedulesModel extends ObjectModel
    {
        public const GROUP_PREFIX = 'date_schedules';

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
