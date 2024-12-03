<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\PaymentMethodModel;
    use App\Core\Repository\PaymentMethodRepositoryInterface;

    /**
     * Repository class for the PaymentMethod entity.
     *
     * @template T of PaymentMethodModel
     * @template-extends AbstractCrudManager<T>
     */
    class PaymentMethodManager extends AbstractCrudManager
    {
        public function __construct(
            private readonly PaymentMethodRepositoryInterface $repository
        ) {
            parent::__construct($this->repository);
        }

        public function getByUser(int $userId): array
        {
            return $this->repository->findByUser($userId);
        }
    }
