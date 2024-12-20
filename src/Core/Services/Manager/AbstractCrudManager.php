<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\ObjectModel;
    use App\Core\Repository\CrudRepositoryInterface;

    /**
     *
     * @template T of ObjectModel
     */
    abstract class AbstractCrudManager implements CrudManagerInterface
    {

        public function __construct(
            private readonly CrudRepositoryInterface $repository
        ) {
        }

        /**
         * Save an entity instance.
         *
         * @param ObjectModel $entity
         *
         * @psalm-param T     $entity
         *
         * @param bool $flush
         *
         * @return void
         */
        public function save(ObjectModel $entity, bool $flush = false): void
        {
            $this->repository->save($entity, $flush);
        }

        /**
         * Get an entity instance by id
         *
         * @param int $id
         *
         * @return ObjectModel|null
         * @psalm-return ?T
         */
        public function get(int $id): ?ObjectModel
        {
            return $this->repository->get($id);
        }

        /**
         * Get all entities.
         *
         * @return array<ObjectModel>
         * @psalm-return list<T> The entities.
         */
        public function all(): array
        {
            return $this->repository->all();
        }

        /**
         * Removes an entity instance.
         *
         * @param ObjectModel $entity
         *
         * @psalm-param T     $entity
         *
         * @param bool $flush
         *
         * @return void
         */
        public function remove(ObjectModel $entity, bool $flush = false): void
        {
            $this->repository->remove($entity, $flush);
        }

    }
