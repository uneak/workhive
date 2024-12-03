<?php

    namespace App\Repository;

    use App\Core\Model\ObjectModel;
    use App\Core\Repository\Adapter\SymfonyRepository;
    use App\Core\Repository\ReservationEquipmentRepositoryInterface;
    use App\Entity\ReservationEquipment;
    use DateTime;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the ReservationEquipment entity.
     *
     * @extends SymfonyRepository<ReservationEquipment>
     */
    class ReservationEquipmentRepository extends SymfonyRepository implements ReservationEquipmentRepositoryInterface
    {
        /**
         * Constructor for the ReservationEquipment repository.
         *
         * @param ManagerRegistry $registry The manager registry for the repository.
         */
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, ReservationEquipment::class);
        }

        /**
         * @inheritDoc
         *
         * @throws \Exception
         */
        protected function hydrateObject(array $data, ObjectModel $object): void
        {
            if (isset($data['reservation'])) $object->setReservation($data['reservation']);
            if (isset($data['equipment'])) $object->setEquipment($data['equipment']);
            if (isset($data['quantity'])) $object->setQuantity($data['quantity']);
            if (isset($data['updatedAt'])) $object->setUpdatedAt(new DateTime($data['updatedAt']));
        }

        /**
         * Get all reservation equipments by reservation ID.
         *
         * @param int $reservationId The ID of the reservation
         * @return array<ReservationEquipment>
         */
        public function findByReservation(int $reservationId): array
        {
            return $this->createQueryBuilder('re')
                ->andWhere('re.reservation = :reservationId')
                ->setParameter('reservationId', $reservationId)
                ->orderBy('re.id', 'ASC')
                ->getQuery()
                ->getResult();
        }

    }
