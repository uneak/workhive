<?php

    namespace App\Core\Repository\Adapter;

    use App\Core\Model\ObjectModel;
    use App\Core\Repository\CrudRepositoryInterface;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\ORM\QueryBuilder;
    use Exception;

    /**
     * @template T of ObjectModel
     *
     * @template-extends ServiceEntityRepository<T>
     * @template-implements CrudRepositoryInterface<T>
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
         * Find all entities in the repository (optional: by filters with ordering and pagination)
         *
         * @param array    $filters Array of filter strings in format "field:value" or "field[]:value1,value2"
         * @param array    $orderBy Order criteria ['field' => 'ASC|DESC']
         * @param int|null $limit   Max number of results
         * @param int|null $offset  Offset for pagination
         *
         * @return array
         * @psalm-return list<T> The entities.
         */
        public function all(
            array $filters = [],
            array $orderBy = [],
            ?int $limit = null,
            ?int $offset = null
        ): array {
            $qb = $this->createQueryBuilder('r');

            $this->applyFilters($qb, $filters);
            $this->applyOrdering($qb, $orderBy);
            $this->applyPagination($qb, $limit, $offset);

            return $qb->getQuery()->getResult();
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
         * Apply filters to a QueryBuilder
         *
         * @param QueryBuilder $qb
         * @param array        $filters Array of filter strings
         */
        private function applyFilters(QueryBuilder $qb, array $filters): void
        {
            foreach ($filters as $filter) {
                $filterParts = $this->parseFilter($filter);
                if ($filterParts === null) {
                    continue;
                }

                [$field, $value] = $filterParts;

                if ($this->isArrayFilter($field)) {
                    $this->applyArrayFilter($qb, $field, $value);
                } else {
                    $this->applyComparisonFilter($qb, $field, $value);
                }
            }
        }

        /**
         * Parse a filter string into field and value
         *
         * @param string $filter Filter string in format "field:value"
         *
         * @return array|null Array containing [field, value] or null if invalid format
         */
        private function parseFilter(string $filter): ?array
        {
            $parts = explode(':', $filter);
            if (count($parts) !== 2) {
                return null;
            }

            return $parts;
        }

        /**
         * Check if the field is an array filter
         *
         * @param string $field Field name
         *
         * @return bool True if field ends with []
         */
        private function isArrayFilter(string $field): bool
        {
            return str_ends_with($field, '[]');
        }

        /**
         * Apply an array filter to the query builder
         *
         * @param QueryBuilder $qb    Query builder
         * @param string       $field Field name (with [] suffix)
         * @param string       $value Comma-separated values
         */
        private function applyArrayFilter(QueryBuilder $qb, string $field, string $value): void
        {
            $field = rtrim($field, '[]');
            $values = explode(',', $value);
            $qb->andWhere($qb->expr()->in("r.$field", ':' . $field))
                ->setParameter($field, $values);
        }

        /**
         * Apply a comparison filter to the query builder
         *
         * @param QueryBuilder $qb    Query builder
         * @param string       $field Field name
         * @param string       $value Value with optional comparison operator
         */
        private function applyComparisonFilter(QueryBuilder $qb, string $field, string $value): void
        {
            $operator = $this->extractComparisonOperator($value);
            $cleanValue = $this->cleanComparisonValue($value);

            $qb->andWhere("r.$field $operator :" . $field)
                ->setParameter($field, $cleanValue);
        }

        /**
         * Extract the comparison operator from a value
         *
         * @param string $value Value with potential operator
         *
         * @return string SQL comparison operator
         */
        private function extractComparisonOperator(string $value): string
        {
            if (str_starts_with($value, '>=')) {
                return '>=';
            }
            if (str_starts_with($value, '<=')) {
                return '<=';
            }
            if (str_starts_with($value, '>')) {
                return '>';
            }
            if (str_starts_with($value, '<')) {
                return '<';
            }

            return '=';
        }

        /**
         * Clean the comparison value by removing the operator
         *
         * @param string $value Value with potential operator
         *
         * @return string Clean value without operator
         */
        private function cleanComparisonValue(string $value): string
        {
            if (str_starts_with($value, '>=') || str_starts_with($value, '<=')) {
                return substr($value, 2);
            }
            if (str_starts_with($value, '>') || str_starts_with($value, '<')) {
                return substr($value, 1);
            }

            return $value;
        }

        /**
         * Apply ordering to the query builder
         *
         * @param QueryBuilder $qb      Query builder
         * @param array        $orderBy Ordering criteria
         */
        private function applyOrdering(QueryBuilder $qb, array $orderBy): void
        {
            foreach ($orderBy as $field => $direction) {
                $qb->addOrderBy("r.$field", $direction);
            }
        }

        /**
         * Apply pagination to the query builder
         *
         * @param QueryBuilder $qb     Query builder
         * @param int|null     $limit  Maximum number of results
         * @param int|null     $offset Starting offset
         */
        private function applyPagination(QueryBuilder $qb, ?int $limit, ?int $offset): void
        {
            if ($limit !== null) {
                $qb->setMaxResults($limit);
            }
            if ($offset !== null) {
                $qb->setFirstResult($offset);
            }
        }

        /**
         * Hydrate an object with data
         *
         * @param array       $data   Data to hydrate the object with
         * @param ObjectModel $object Object to hydrate
         *
         * @psalm-param T     $object
         *
         * @return void
         */
        abstract protected function hydrateObject(array $data, ObjectModel $object): void;
    }
