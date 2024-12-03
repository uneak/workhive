<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\PaymentModel;
    use App\Core\Repository\PaymentRepositoryInterface;

    /**
     * Repository class for the DateSchedules entity.
     *
     * @template T of PaymentModel
     * @template-extends AbstractCrudManager<T>
     */
    class PaymentManager extends AbstractCrudManager
    {
        public function __construct(
            private readonly PaymentRepositoryInterface $repository
        ) {
            parent::__construct($this->repository);
        }
    }
