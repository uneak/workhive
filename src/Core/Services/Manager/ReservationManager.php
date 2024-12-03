<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\ReservationModel;
    use App\Core\Repository\ReservationRepositoryInterface;

    /**
     * Repository class for the Reservation entity.
     *
     * @template T of ReservationModel
     * @template-extends AbstractCrudManager<T>
     */
    class ReservationManager extends AbstractCrudManager
    {
        public function __construct(
            private readonly ReservationRepositoryInterface $repository
        ) {
            parent::__construct($this->repository);
        }
    }
