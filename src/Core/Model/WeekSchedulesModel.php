<?php

    namespace App\Core\Model;

    use DateTimeInterface;

    /**
     * Interface for WeekSchedules.
     */
    interface WeekSchedulesModel extends ObjectModel
    {

        const WEEK_DAYS = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        /**
         * Get the time the schedule starts on a given day.
         *
         * @return DateTimeInterface|null
         */
        public function getStartedAt(): ?DateTimeInterface;

        /**
         * Set the time the schedule starts on a given day.
         *
         * @param DateTimeInterface $startedAt
         *
         * @return static
         */
        public function setStartedAt(DateTimeInterface $startedAt): static;

        /**
         * Get the time the schedule ends on a given day.
         *
         * @return DateTimeInterface|null
         */
        public function getEndedAt(): ?DateTimeInterface;

        /**
         * Set the time the schedule ends on a given day.
         *
         * @param DateTimeInterface $endedAt
         *
         * @return static
         */
        public function setEndedAt(DateTimeInterface $endedAt): static;

        /**
         * Get the day of the week for the schedule (0 = Sunday, 6 = Saturday).
         *
         * @return int|null
         */
        public function getWeekDay(): ?int;

        /**
         * Set the day of the week for the schedule (0 = Sunday, 6 = Saturday).
         *
         * @param int|null $weekDay
         *
         * @return static
         */
        public function setWeekDay(?int $weekDay): static;

        /**
         * Get the room associated with this schedule.
         *
         * @return RoomModel|null
         */
        public function getRoom(): ?RoomModel;

        /**
         * Set the room associated with this schedule.
         *
         * @param RoomModel|null $room
         *
         * @return static
         */
        public function setRoom(?RoomModel $room): static;
    }
