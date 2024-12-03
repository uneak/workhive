<?php

    namespace App\Repository;

    use App\Core\Model\ObjectModel;
    use App\Core\Repository\Adapter\SymfonyRepository;
    use App\Core\Repository\EquipmentRepositoryInterface;
    use App\Entity\Equipment;
    use DateTime;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the Equipment entity.
     *
     * @extends SymfonyRepository<Equipment>
     */
    class EquipmentRepository extends SymfonyRepository implements EquipmentRepositoryInterface
    {
        /**
         * Constructor for the Equipment repository.
         *
         * @param ManagerRegistry $registry The manager registry for the repository.
         */
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Equipment::class);
        }

        /**
         * @inheritDoc
         *
         * @throws \Exception
         */
        protected function hydrateObject(array $data, ObjectModel $object): void
        {
            if (isset($data['name'])) $object->setName($data['name']);
            if (isset($data['description'])) $object->setDescription($data['description']);
            if (isset($data['photo'])) $object->setPhoto($data['photo']);
            if (isset($data['totalStock'])) $object->setTotalStock($data['totalStock']);
            if (isset($data['updatedAt'])) $object->setUpdatedAt(new DateTime($data['updatedAt']));
        }

    }
