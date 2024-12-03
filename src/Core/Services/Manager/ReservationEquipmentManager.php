<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\ReservationEquipmentModel;
    use App\Core\Repository\ReservationEquipmentRepositoryInterface;

    /**
     * Repository class for the ReservationEquipment entity.
     *
     * @template T of ReservationEquipmentModel
     * @template-extends AbstractCrudManager<T>
     */
    class ReservationEquipmentManager extends AbstractCrudManager
    {
        public function __construct(
            private readonly ReservationEquipmentRepositoryInterface $repository
        ) {
            parent::__construct($this->repository);
        }

        public function getByReservation(ReservationEquipmentModel $reservation): array
        {
            return $this->repository->findByReservation($reservation->getId());
        }
    }
