<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\RoomEquipmentModel;
    use App\Core\Model\RoomModel;
    use App\Core\Repository\RoomEquipmentRepositoryInterface;

    /**
     * Repository class for the RoomEquipment entity.
     *
     * @template T of RoomEquipmentModel
     * @template-extends AbstractCrudManager<T>
     */
    class RoomEquipmentManager extends AbstractCrudManager
    {
        public function __construct(
            private readonly RoomEquipmentRepositoryInterface $repository
        ) {
            parent::__construct($this->repository);
        }

        public function getByRoom(int $roomId): array
        {
            return $this->repository->findByRoom($roomId);
        }
    }
