<?php

    namespace App\Repository;

    use App\Core\Model\ObjectModel;
    use App\Core\Repository\Adapter\SymfonyRepository;
    use App\Core\Repository\PaymentMethodRepositoryInterface;
    use App\Entity\PaymentMethod;
    use DateTime;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the PaymentMethod entity.
     *
     * @extends SymfonyRepository<PaymentMethod>
     */
    class PaymentMethodRepository extends SymfonyRepository implements PaymentMethodRepositoryInterface
    {
        /**
         * Constructor for the PaymentMethod repository.
         *
         * @param ManagerRegistry $registry The manager registry for the repository.
         */
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, PaymentMethod::class);
        }

        /**
         * @inheritDoc
         *
         * @throws \Exception
         */
        protected function hydrateObject(array $data, ObjectModel $object): void
        {
            if (isset($data['user'])) $object->setUser($data['user']);
            if (isset($data['label'])) $object->setLabel($data['label']);
            if (isset($data['type'])) $object->setType($data['type']);
            if (isset($data['data'])) $object->setData($data['data']);
            if (isset($data['createdAt'])) $object->setCreatedAt(new DateTime($data['createdAt']));
            if (isset($data['updatedAt'])) $object->setUpdatedAt(new DateTime($data['updatedAt']));
        }

        /**
         * Get all payment methods by user ID.
         *
         * @param int $userId The ID of the user
         * @return array<PaymentMethod>
         */
        public function findByUser(int $userId): array
        {
            return $this->createQueryBuilder('pm')
                ->andWhere('pm.user = :userId')
                ->setParameter('userId', $userId)
                ->orderBy('pm.createdAt', 'DESC')
                ->getQuery()
                ->getResult();
        }
    }
