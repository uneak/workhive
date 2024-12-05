<?php

    namespace App\Core\Repository;

    use App\Core\Model\ObjectModel;

    /**
     * Interface for a CRUD repository.
     *
     * @template T of ObjectModel
     */
    interface CrudRepositoryInterface
    {
        /**
         * Create a new entity.
         *
         * @param array $data The entity to create.
         *
         * @return ObjectModel The created entity.
         * @psalm-return T
         */
        public function create(array $data): ObjectModel;

        /**
         * Update an existing entity.
         *
         * @param array $data The updated entity data.
         *
         * @return ObjectModel The updated entity.
         * @psalm-return T
         */
        public function update(array $data): ObjectModel;

        /**
         * Get an entity by its ID.
         *
         * @param int $id The ID of the entity to retrieve.
         *
         * @return ObjectModel|null The entity if found, or null if not found.
         * @psalm-return T|null
         */
        public function get(int $id): ?ObjectModel;

        /**
         * Delete an entity by its ID.
         *
         * @param int $id The ID of the entity to delete.
         *
         * @return void
         */
        public function delete(int $id): void;

        /**
         * Find all entities in the repository (optional: by filters with ordering and pagination)
         *
         * @param array    $filters Array of filter strings in format "field:value" or "field[]:value1,value2"
         * @param array    $orderBy Order criteria ['field' => 'ASC|DESC']
         * @param int|null $limit   Max number of results
         * @param int|null $offset  Offset for pagination
         *
         * @return array<ObjectModel> The entities.
         * @psalm-return array<T> The entities.
         */
        public function all(
            array $filters = [],
            array $orderBy = [],
            ?int $limit = null,
            ?int $offset = null
        ): array;

        /**
         * Save an entity.
         *
         * @param ObjectModel $entity The entity to save.
         *
         * @psalm-param T     $entity
         *
         * @param bool        $flush  Whether to flush the entity manager after saving.
         *
         * @return void
         */
        public function save(ObjectModel $entity, bool $flush = false): void;

        /**
         * Remove an entity.
         *
         * @param ObjectModel $entity The entity to remove.
         *
         * @psalm-param T     $entity
         *
         * @param bool        $flush  Whether to flush the entity manager after removing.
         *
         * @return void
         */
        public function remove(ObjectModel $entity, bool $flush = false): void;

    }
