<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\RoomModel;
    use App\Core\Repository\RoomRepositoryInterface;

    /**
     * Repository class for the Room entity.
     *
     * @template-extends AbstractCrudManager<RoomModel>
     */
    class RoomManager extends AbstractCrudManager
    {
        public function __construct(
            private readonly RoomRepositoryInterface $repository
        ) {
            parent::__construct($this->repository);
        }
    }
