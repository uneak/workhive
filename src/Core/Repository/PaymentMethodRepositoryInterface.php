<?php

    namespace App\Core\Repository;


    interface PaymentMethodRepositoryInterface extends CrudRepositoryInterface
    {
        public function findByUser(int $userId): array;
    }
