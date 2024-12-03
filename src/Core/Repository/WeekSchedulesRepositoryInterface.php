<?php

    namespace App\Core\Repository;

    interface WeekSchedulesRepositoryInterface extends CrudRepositoryInterface
    {
        public function findByRoom(int $roomId): array;
    }
