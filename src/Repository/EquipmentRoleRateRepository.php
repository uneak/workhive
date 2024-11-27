<?php

namespace App\Repository;

use App\Entity\Equipment;
use App\Entity\EquipmentRoleRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EquipmentRoleRate>
 */
class EquipmentRoleRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EquipmentRoleRate::class);
    }
}
