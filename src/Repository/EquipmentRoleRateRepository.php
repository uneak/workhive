<?php

    namespace App\Repository;

    use App\Core\Model\ObjectModel;
    use App\Core\Repository\Adapter\SymfonyRepository;
    use App\Core\Repository\EquipmentRoleRateRepositoryInterface;
    use App\Entity\EquipmentRoleRate;
    use Doctrine\ORM\NonUniqueResultException;
    use Doctrine\ORM\NoResultException;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the EquipmentRoleRate entity.
     *
     * @extends SymfonyRepository<EquipmentRoleRate>
     */
    class EquipmentRoleRateRepository extends SymfonyRepository implements EquipmentRoleRateRepositoryInterface
    {
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, EquipmentRoleRate::class);
        }

        /**
         * @inheritDoc
         *
         * @throws \Exception
         */
        protected function hydrateObject(array $data, ObjectModel $object): void
        {
            if (isset($data['equipment'])) $object->setEquipment($data['equipment']);
            if (isset($data['userRole'])) $object->setUserRole($data['userRole']);
            if (isset($data['hourlyRate'])) $object->setHourlyRate($data['hourlyRate']);
        }

        /**
         * Get all equipment role rates by equipment ID.
         *
         * @param int $equipmentId The ID of the equipment
         * @return array<EquipmentRoleRate>
         *
         * @throws NoResultException
         * @throws NonUniqueResultException
         */
        public function findByEquipment(int $equipmentId): array
        {
            return $this->createQueryBuilder('err')
                ->andWhere('err.equipment = :equipmentId')
                ->setParameter('equipmentId', $equipmentId)
                ->getQuery()
                ->getResult();
        }
    }
