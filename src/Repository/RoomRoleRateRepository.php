<?php

    namespace App\Repository;

    use App\Core\Model\ObjectModel;
    use App\Core\Repository\Adapter\SymfonyRepository;
    use App\Core\Repository\RoomRoleRateRepositoryInterface;
    use App\Entity\RoomRoleRate;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the Room entity.
     *
     * @extends SymfonyRepository<RoomRoleRate>
     */
    class RoomRoleRateRepository extends SymfonyRepository implements RoomRoleRateRepositoryInterface
    {
        /**
         * Constructor for the RoomRoleRate repository.
         *
         * @param ManagerRegistry $registry The manager registry for the repository.
         */
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, RoomRoleRate::class);
        }

        /**
         * @inheritDoc
         *
         * @throws \Exception
         */
        protected function hydrateObject(array $data, ObjectModel $object): void
        {
            if (isset($data['room'])) $object->setRoom($data['room']);
            if (isset($data['userRole'])) $object->setUserRole($data['userRole']);
            if (isset($data['hourlyRate'])) $object->setHourlyRate($data['hourlyRate']);
        }

        /**
         * Get all room role rates by room ID.
         *
         * @param int $roomId The ID of the room
         * @return array<RoomRoleRate>
         */
        public function findByRoom(int $roomId): array
        {
            return $this->createQueryBuilder('rrr')
                ->andWhere('rrr.room = :roomId')
                ->setParameter('roomId', $roomId)
                ->orderBy('rrr.userRole', 'ASC')
                ->getQuery()
                ->getResult();
        }

    }
