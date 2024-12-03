<?php

    namespace App\Repository;

    use App\Core\Model\ObjectModel;
    use App\Core\Repository\Adapter\SymfonyRepository;
    use App\Core\Repository\RoomEquipmentRepositoryInterface;
    use App\Entity\RoomEquipment;
    use DateTime;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the RoomEquipment entity.
     *
     * @extends SymfonyRepository<RoomEquipment>
     */
    class RoomEquipmentRepository extends SymfonyRepository implements RoomEquipmentRepositoryInterface
    {
        /**
         * Constructor for the RoomEquipment repository.
         *
         * @param ManagerRegistry $registry The manager registry for the repository.
         */
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, RoomEquipment::class);
        }

        /**
         * @inheritDoc
         *
         * @throws \Exception
         */
        protected function hydrateObject(array $data, ObjectModel $object): void
        {
            if (isset($data['room'])) $object->setRoom($data['room']);
            if (isset($data['equipment'])) $object->setEquipment($data['equipment']);
            if (isset($data['quantity'])) $object->setQuantity($data['quantity']);
            if (isset($data['assignedAt'])) $object->setAssignedAt(new DateTime($data['assignedAt']));
        }

        /**
         * Get all room equipments by room ID.
         *
         * @param int $roomId The ID of the room
         * @return array<RoomEquipment>
         */
        public function findByRoom(int $roomId): array
        {
            return $this->createQueryBuilder('re')
                ->andWhere('re.room = :roomId')
                ->setParameter('roomId', $roomId)
                ->orderBy('re.id', 'ASC')
                ->getQuery()
                ->getResult();
        }

    }
