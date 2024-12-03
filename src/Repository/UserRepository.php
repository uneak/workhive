<?php

    namespace App\Repository;

    use App\Core\Enum\Status;
    use App\Core\Enum\UserRole;
    use App\Core\Model\ObjectModel;
    use App\Core\Repository\Adapter\SymfonyRepository;
    use App\Core\Repository\UserRepositoryInterface;
    use App\Entity\User;
    use DateTime;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the User entity.
     *
     * @extends SymfonyRepository<User>
     */
    class UserRepository extends SymfonyRepository implements UserRepositoryInterface
    {
        /**
         * Constructor for the User repository.
         *
         * @param ManagerRegistry $registry The manager registry for the repository.
         */
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, User::class);
        }

        /**
         * @inheritDoc
         *
         * @throws \Exception
         */
        protected function hydrateObject(array $data, ObjectModel $object): void
        {
            if (isset($data['firstName'])) $object->setFirstName($data['firstName']);
            if (isset($data['lastName'])) $object->setLastName($data['lastName']);
            if (isset($data['photo'])) $object->setPhoto($data['photo']);
            if (isset($data['userRole'])) $object->setUserRole($data['userRole']);
            if (isset($data['phone'])) $object->setPhone($data['phone']);
            if (isset($data['email'])) $object->setEmail($data['email']);
            if (isset($data['password'])) $object->setPassword($data['password']);
            if (isset($data['status'])) $object->setStatus($data['status']);
            if (isset($data['createdAt'])) $object->setCreatedAt(new DateTime($data['createdAt']));
            if (isset($data['updatedAt'])) $object->setUpdatedAt(new DateTime($data['updatedAt']));
            if (isset($data['isVerified'])) $object->setVerified($data['isVerified']);
        }
    }
