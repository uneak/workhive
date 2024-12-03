<?php

    namespace App\Core\Model;

    /**
     * Interface for WeekSchedules.
     */
    interface ObjectModel
    {
        /**
         * Get the unique identifier of the weekly schedule.
         *
         * @return int|null
         */
        public function getId(): ?int;
    }
