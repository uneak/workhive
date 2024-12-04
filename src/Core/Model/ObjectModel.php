<?php

    namespace App\Core\Model;

    /**
     * Interface for WeekSchedules.
     */
    interface ObjectModel
    {
        /**
         * The prefix used for serialization groups.
         * Each model should override this constant with its own prefix.
         */
        public const GROUP_PREFIX = 'object';

        /**
         * Get the unique identifier of the weekly schedule.
         *
         * @return int|null
         */
        public function getId(): ?int;
    }
