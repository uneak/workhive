<?php

    namespace App\Repository;

    use App\Entity\Payment;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the DateSchedules entity.
     *
     * @extends ServiceEntityRepository<Payment>
     */
    class PaymentRepository extends ServiceEntityRepository
    {
        /**
         * Constructor for the DateSchedules repository.
         *
         * @param ManagerRegistry $registry The manager registry for the repository.
         */
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Payment::class);
        }

    }
