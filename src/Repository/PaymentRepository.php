<?php

    namespace App\Repository;

    use App\Core\Model\ObjectModel;
    use App\Core\Repository\Adapter\SymfonyRepository;
    use App\Core\Repository\PaymentRepositoryInterface;
    use App\Entity\Payment;
    use DateTime;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the Payment entity.
     *
     * @extends SymfonyRepository<Payment>
     */
    class PaymentRepository extends SymfonyRepository implements PaymentRepositoryInterface
    {
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Payment::class);
        }

        /**
         * @inheritDoc
         *
         * @throws \Exception
         */
        protected function hydrateObject(array $data, ObjectModel $object): void
        {
            if (isset($data['reservation'])) $object->setReservation($data['reservation']);
            if (isset($data['paymentMethod'])) $object->setPaymentMethod($data['paymentMethod']);
            if (isset($data['amount'])) $object->setAmount($data['amount']);
            if (isset($data['status'])) $object->setStatus($data['status']);
            if (isset($data['createdAt'])) $object->setCreatedAt(new DateTime($data['createdAt']));
            if (isset($data['updatedAt'])) $object->setUpdatedAt(new DateTime($data['updatedAt']));
        }
    }
