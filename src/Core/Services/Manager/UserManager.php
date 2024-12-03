<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\UserModel;
    use App\Core\Repository\UserRepositoryInterface;

    /**
     * Repository class for the User entity.
     *
     * @template T of UserModel
     * @template-extends AbstractCrudManager<T>
     */
    class UserManager extends AbstractCrudManager
    {
        public function __construct(
            private readonly UserRepositoryInterface $repository
        ) {
            parent::__construct($this->repository);
        }
    }
