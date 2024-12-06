<?php

    namespace App\Core\Model;

    use App\Core\Enum\Status;

    /**
     * Interface for Room.
     */
    interface RoomModel extends ObjectModel
    {
        public const GROUP_PREFIX = 'room';
        public const CREATE_GROUPS = [ObjectModel::CREATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::CREATE_PREFIX];
        public const UPDATE_GROUPS = [ObjectModel::UPDATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::UPDATE_PREFIX];
        public const WRITE_GROUPS = [...self::CREATE_GROUPS, ...self::UPDATE_GROUPS];
        public const READ_GROUPS = [ObjectModel::READ_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::READ_PREFIX];
        public const RW_GROUPS = [...self::READ_GROUPS, ...self::WRITE_GROUPS];
        /**
         * Get the status of the room.
         *
         * @return Status|null
         */
        public function getStatus(): ?Status;

        /**
         * Set the status of the room.
         *
         * @param Status $status
         *
         * @return static
         */
        public function setStatus(Status $status): static;
    }
