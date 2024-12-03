<?php

    namespace App\Repository;

    use App\Core\Model\ObjectModel;
    use App\Core\Repository\Adapter\SymfonyRepository;
    use App\Core\Repository\WeekSchedulesRepositoryInterface;
    use App\Entity\WeekSchedules;
    use DateTime;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the WeekSchedules entity.
     *
     * @extends SymfonyRepository<WeekSchedules>
     */
    class WeekSchedulesRepository extends SymfonyRepository implements WeekSchedulesRepositoryInterface
    {
        /**
         * Constructor for the WeekSchedules repository.
         *
         * @param ManagerRegistry $registry The manager registry for the repository.
         */
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, WeekSchedules::class);
        }

        /**
         * @inheritDoc
         *
         * @throws \Exception
         */
        protected function hydrateObject(array $data, ObjectModel $object): void
        {
            if (isset($data['room'])) $object->setRoom($data['room']);
            if (isset($data['startedAt'])) $object->setStartedAt(new DateTime($data['startedAt']));
            if (isset($data['endedAt'])) $object->setEndedAt(new DateTime($data['endedAt']));
            if (isset($data['weekDay'])) $object->setWeekDay($data['weekDay']);
        }

        /**
         * Get all week schedules by room ID.
         *
         * @param int $roomId The ID of the room
         * @return array<WeekSchedules>
         */
        public function findByRoom(int $roomId): array
        {
            return $this->createQueryBuilder('ws')
                ->andWhere('ws.room = :roomId')
                ->setParameter('roomId', $roomId)
                ->orderBy('ws.weekDay', 'ASC')
                ->getQuery()
                ->getResult();
        }

        /**
         * Get all week schedules by room ID and day of the week.
         *
         * @param int $roomId The ID of the room
         * @param int $weekDay The day of the week (0-6)
         * @return array<WeekSchedules>
         */
        public function findByRoomAndDay(int $roomId, int $weekDay): array
        {
            return $this->createQueryBuilder('ws')
                ->andWhere('ws.room = :roomId')
                ->andWhere('ws.weekDay = :weekDay')
                ->setParameter('roomId', $roomId)
                ->setParameter('weekDay', $weekDay)
                ->orderBy('ws.startedAt', 'ASC')
                ->getQuery()
                ->getResult();
        }
    }
