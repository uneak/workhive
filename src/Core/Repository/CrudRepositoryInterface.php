<?php

    namespace App\Core\Repository;

    use App\Core\Model\ObjectModel;

    interface CrudRepositoryInterface
    {
        /**
         * Create a new entity.
         *
         * @param array $data The entity to create.
         *
         * @return ObjectModel The created entity.
         */
        public function create(array $data): ObjectModel;

        /**
         * Update an existing entity.
         *
         * @param array $data The updated entity data.
         *
         * @return ObjectModel The updated entity.
         */
        public function update(array $data): ObjectModel;

        /**
         * Get an entity by its ID.
         *
         * @param int $id The ID of the entity to retrieve.
         *
         * @return ObjectModel|null The entity if found, or null if not found.
         */
        public function get(int $id): ?ObjectModel;

        /**
         * Get all entities.
         *
         * @return array<ObjectModel> An array of all entities.
         */
        public function all(): array;

        public function save(ObjectModel $entity, bool $flush = false): void;

        public function remove(ObjectModel $entity, bool $flush = false): void;

    }
