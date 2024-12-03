<?php

    namespace App\Core\Repository;

    interface EquipmentRoleRateRepositoryInterface extends CrudRepositoryInterface
    {
        public function findByEquipment(int $equipmentId);
    }
