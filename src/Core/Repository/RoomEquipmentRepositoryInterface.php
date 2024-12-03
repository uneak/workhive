<?php

    namespace App\Core\Repository;

    interface RoomEquipmentRepositoryInterface extends CrudRepositoryInterface
    {
        public function findByRoom(int $roomId): array;
    }
