<?php

    namespace App\Core\Repository;

    interface RoomRoleRateRepositoryInterface extends CrudRepositoryInterface
    {
        public function findByRoom(int $roomId): array;
    }
