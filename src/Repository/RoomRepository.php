<?php

    namespace App\Repository;

    use App\Core\Model\ObjectModel;
    use App\Core\Repository\Adapter\SymfonyRepository;
    use App\Core\Repository\RoomRepositoryInterface;
    use App\Entity\Room;
    use DateTime;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * Repository class for the Room entity.
     *
     * @extends SymfonyRepository<Room>
     */
    class RoomRepository extends SymfonyRepository implements RoomRepositoryInterface
    {
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Room::class);
        }

        /**
         * @inheritDoc
         *
         * @throws \Exception
         */
        protected function hydrateObject(array $data, ObjectModel $object): void
        {
            if (isset($data['name'])) $object->setName($data['name']);
            if (isset($data['capacity'])) $object->setCapacity($data['capacity']);
            if (isset($data['width'])) $object->setWidth($data['width']);
            if (isset($data['length'])) $object->setLength($data['length']);
            if (isset($data['description'])) $object->setDescription($data['description']);
            if (isset($data['photo'])) $object->setPhoto($data['photo']);
            if (isset($data['status'])) $object->setStatus($data['status']);
            if (isset($data['createdAt'])) $object->setCreatedAt(new DateTime($data['createdAt']));
            if (isset($data['updatedAt'])) $object->setUpdatedAt(new DateTime($data['updatedAt']));
        }
    }
