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
        public const CREATE_PREFIX = 'create';
        public const UPDATE_PREFIX = 'update';
        public const READ_PREFIX = 'read';

        public const CREATE_GROUPS = [self::CREATE_PREFIX, self::GROUP_PREFIX . ':' . self::CREATE_PREFIX];
        public const UPDATE_GROUPS = [self::UPDATE_PREFIX, self::GROUP_PREFIX . ':' . self::UPDATE_PREFIX];
        public const WRITE_GROUPS = [...self::CREATE_GROUPS, ...self::UPDATE_GROUPS];
        public const READ_GROUPS = [self::READ_PREFIX, self::GROUP_PREFIX . ':' . self::READ_PREFIX];
        public const RW_GROUPS = [...self::READ_GROUPS, ...self::WRITE_GROUPS];

        /**
         * Get the unique identifier of the weekly schedule.
         *
         * @return int|null
         */
        public function getId(): ?int;
    }
