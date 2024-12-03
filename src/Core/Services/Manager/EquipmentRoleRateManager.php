<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\EquipmentModel;
    use App\Core\Model\EquipmentRoleRateModel;
    use App\Core\Repository\EquipmentRoleRateRepositoryInterface;

    /**
     * Repository class for the EquipmentRoleRate entity.
     *
     * @template T of EquipmentRoleRateModel
     * @template-extends AbstractCrudManager<T>
     */
    class EquipmentRoleRateManager extends AbstractCrudManager
    {
        public function __construct(
            private readonly EquipmentRoleRateRepositoryInterface $repository
        ) {
            parent::__construct($this->repository);
        }

        /**
         * @param EquipmentModel $equipment
         *
         * @return array<T>
         */
        public function getByEquipment(EquipmentModel $equipment) : array
        {
            return $this->repository->findByEquipment($equipment->getId());
        }
    }
