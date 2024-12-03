<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\RoomModel;
    use App\Core\Model\RoomRoleRateModel;
    use App\Core\Repository\RoomRoleRateRepositoryInterface;

    /**
     * Repository class for the WeekSchedules entity.
     *
     * @template T of RoomRoleRateModel
     * @template-extends AbstractCrudManager<T>
     */
    class RoomRoleRateManager extends AbstractCrudManager
    {
        public function __construct(
            private readonly RoomRoleRateRepositoryInterface $repository
        ) {
            parent::__construct($this->repository);
        }

        public function getByRoom(int $roomId): array
        {
            return $this->repository->findByRoom($roomId);
        }
    }
