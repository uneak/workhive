<?php

    namespace App\Core\Model;

    use App\Core\Enum\Status;

    /**
     * Interface for Room.
     */
    interface RoomModel extends ObjectModel
    {
        public const GROUP_PREFIX = 'room';

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
