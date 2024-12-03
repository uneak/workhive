<?php

    namespace App\Core\Repository;

    interface ReservationEquipmentRepositoryInterface extends CrudRepositoryInterface
    {
        public function findByReservation(int $reservationId): array;
    }
