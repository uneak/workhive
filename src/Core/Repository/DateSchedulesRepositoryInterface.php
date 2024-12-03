<?php

    namespace App\Core\Repository;

    interface DateSchedulesRepositoryInterface extends CrudRepositoryInterface
    {
        public function findByRoom(int $roomId): array;
    }
