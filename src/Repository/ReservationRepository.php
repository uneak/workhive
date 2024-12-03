<?php

    namespace App\Repository;

    use App\Core\Enum\ReservationStatus;
    use App\Core\Model\ObjectModel;
    use App\Core\Repository\Adapter\SymfonyRepository;
    use App\Core\Repository\ReservationRepositoryInterface;
    use App\Entity\Reservation;
    use DateTime;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the Reservation entity.
     *
     * @extends SymfonyRepository<Reservation>
     */
    class ReservationRepository extends SymfonyRepository implements ReservationRepositoryInterface
    {
        /**
         * Constructor for the Reservation repository.
         *
         * @param ManagerRegistry $registry The manager registry for the repository.
         */
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Reservation::class);
        }

        /**
         * @inheritDoc
         *
         * @throws \Exception
         */
        protected function hydrateObject(array $data, ObjectModel $object): void
        {
            if (isset($data['user'])) $object->setUser($data['user']);
            if (isset($data['room'])) $object->setRoom($data['room']);
            if (isset($data['startedAt'])) $object->setStartAt(new DateTime($data['startedAt']));
            if (isset($data['endedAt'])) $object->setEndAt(new DateTime($data['endedAt']));
            if (isset($data['status'])) $object->setStatus($data['status']);
            if (isset($data['createdAt'])) $object->setCreatedAt(new DateTime($data['createdAt']));
            if (isset($data['updatedAt'])) $object->setUpdatedAt(new DateTime($data['updatedAt']));
        }
    }
