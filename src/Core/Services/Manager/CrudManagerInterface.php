<?php

    namespace App\Core\Services\Manager;

    use App\Core\Model\ObjectModel;

    interface CrudManagerInterface
    {
        public function save(ObjectModel $entity, bool $flush = false): void;
        public function get(int $id): ?ObjectModel;
        /**
         * @return array<ObjectModel>
         */
        public function all(): array;
        public function remove(ObjectModel $entity, bool $flush = false): void;
    }
