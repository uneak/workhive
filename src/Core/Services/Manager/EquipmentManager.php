<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\EquipmentModel;
    use App\Core\Repository\EquipmentRepositoryInterface;

    /**
     * Repository class for the Equipment entity.
     *
     * @template T of EquipmentModel
     * @template-extends AbstractCrudManager<T>
     */
    class EquipmentManager extends AbstractCrudManager
    {
        public function __construct(
            private readonly EquipmentRepositoryInterface $repository
        ) {
            parent::__construct($this->repository);
        }
    }
