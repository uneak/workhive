<?php

    namespace App\Repository;


    use App\Core\Model\ObjectModel;
    use App\Core\Repository\Adapter\SymfonyRepository;
    use App\Core\Repository\DateSchedulesRepositoryInterface;
    use App\Entity\DateSchedules;
    use DateTime;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the DateSchedules entity.
     *
     * @extends SymfonyRepository<DateSchedules>
     */
    class DateSchedulesRepository extends SymfonyRepository implements DateSchedulesRepositoryInterface
    {
        /**
         * Constructor for the DateSchedules repository.
         *
         * @param ManagerRegistry $registry The manager registry for the repository.
         */
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, DateSchedules::class);
        }

        /**
         * @inheritDoc
         *
         * @throws \Exception
         */
        protected function hydrateObject(array $data, ObjectModel $object): void
        {
            if (isset($data['room'])) $object->setRoom($data['room']);
            if (isset($data['name'])) $object->setName($data['name']);
            if (isset($data['date'])) $object->setDate(new DateTime($data['date']));
            if (isset($data['startedAt'])) $object->setStartedAt(new DateTime($data['startedAt']));
            if (isset($data['endedAt'])) $object->setEndedAt(new DateTime($data['endedAt']));
            if (isset($data['isOpen'])) $object->setIsOpen($data['isOpen']);
        }

        /**
         * Get all date schedules by room ID.
         *
         * @param int $roomId The ID of the room
         * @return array<DateSchedules>
         */
        public function findByRoom(int $roomId): array
        {
            return $this->createQueryBuilder('ds')
                ->andWhere('ds.room = :roomId')
                ->setParameter('roomId', $roomId)
                ->orderBy('ds.date', 'ASC')
                ->addOrderBy('ds.startedAt', 'ASC')
                ->getQuery()
                ->getResult();
        }

    }
