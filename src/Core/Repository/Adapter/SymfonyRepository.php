<?php

    namespace App\Core\Repository\Adapter;

    use App\Core\Model\ObjectModel;
    use App\Core\Repository\CrudRepositoryInterface;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Exception;

    /**
     * @internal Extend {@see ServiceEntityRepository} instead.
     *
     * @template T of ObjectModel
     * @template-extends ServiceEntityRepository<T>
     */
    abstract class SymfonyRepository extends ServiceEntityRepository implements CrudRepositoryInterface
    {

        /**
         * Create an entity in the repository.
         *
         * @param array $data The data to create the entity with.
         *
         * @return ObjectModel The entity instance.
         * @psalm-return ?T
         * @throws \Exception
         */
        public function create(array $data): ObjectModel
        {
            $id = $data['id'] ?? null;
            if ($id) {
                throw new Exception("ID provided for create");
            }

            $entityClass = $this->getClassName();
            $entity = new $entityClass();

            $this->hydrateObject($data, $entity);

            $this->save($entity, true);

            return $entity;
        }

        /**
         * Updates an entity in the repository.
         *
         * @return ObjectModel The entity instance.
         * @psalm-return ?T
         * @throws \Exception
         */
        public function update(array $data): ObjectModel
        {
            $id = $data['id'] ?? null;
            if (!$id) {
                throw new Exception("ID not provided");
            }

            $entity = $this->find($id);

            if (!$entity) {
                throw new Exception("Entity not found");
            }

            $this->hydrateObject($data, $entity);

            return $entity;
        }

        /**
         * Finds all entities in the repository.
         *
         * @psalm-return list<T> The entities.
         */
        public function all(): array
        {
            return $this->findAll();
        }

        /**
         * @throws \Exception
         */
        public function delete(int $id): void
        {
            $entity = $this->find($id);

            if (!$entity) {
                throw new Exception("Entity not found");
            }

            $this->remove($entity, true);
        }

        /**
         * Finds an entity by its primary key / identifier.
         *
         * @return ObjectModel|null The entity instance or NULL if the entity can not be found.
         * @psalm-return ?T
         */
        public function get(int $id): ?ObjectModel
        {
            return $this->find($id);
        }

        /**
         * Save an entity instance.
         *
         * @param ObjectModel $entity
         *
         * @psalm-param T     $entity
         *
         * @param bool        $flush
         *
         * @return void
         */
        public function save(ObjectModel $entity, bool $flush = false): void
        {
            $this->getEntityManager()->persist($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
        }

        /**
         * Removes an entity instance.
         *
         * @param ObjectModel $entity
         *
         * @psalm-param T     $entity
         *
         * @param bool        $flush
         *
         * @return void
         */
        public function remove(ObjectModel $entity, bool $flush = false): void
        {
            $this->getEntityManager()->remove($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
        }

        /**
         * Finds an entity by its primary key / identifier.
         *
         * @param array       $data
         * @param ObjectModel $object
         *
         * @psalm-param T     $object
         *
         * @return void
         */
        abstract protected function hydrateObject(array $data, ObjectModel $object): void;
    }
