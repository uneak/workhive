<?php

    namespace App\Entity;

    use App\Core\Model\EquipmentModel;
    use App\Core\Model\RoomEquipmentModel;
    use App\Core\Model\RoomModel;
    use App\Repository\RoomEquipmentRepository;
    use DateTime;
    use Doctrine\ORM\Mapping as ORM;
    use OpenApi\Attributes as OA;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Represents the association of equipment with a room and its quantity.
     *
     * Groups:
     * - read: Global read group
     * - write: Global write group
     * - room-equipment:read: Room equipment-specific read group
     * - room-equipment:write: Room equipment-specific write group
     */
    #[OA\Schema(
        title: 'RoomEquipment',
        description: 'Represents the association between a room and its available equipment',
        type: 'object'
    )]
    #[ORM\Entity(repositoryClass: RoomEquipmentRepository::class)]
    #[ORM\Table(name: 'room_equipment')]
    class RoomEquipment implements RoomEquipmentModel
    {
        public const READ_GROUPS = ['read', RoomEquipmentModel::GROUP_PREFIX . ':read'];
        public const WRITE_GROUPS = ['write', RoomEquipmentModel::GROUP_PREFIX . ':write'];

        /**
         * The unique identifier of the room-equipment association.
         */
        #[OA\Property(
            property: 'id',
            description: 'The unique identifier of the room-equipment association',
            type: 'integer',
            example: 1
        )]
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        #[Groups(self::READ_GROUPS)]
        private ?int $id = null;

        /**
         * The room associated with the equipment.
         */
        #[OA\Property(
            ref: new Model(type: Room::class),
            description: 'The room associated with this equipment'
        )]
        #[ORM\ManyToOne(targetEntity: Room::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        #[Assert\NotNull(message: 'Room is required')]
        private RoomModel $room;

        /**
         * The equipment associated with the room.
         */
        #[OA\Property(
            ref: new Model(type: Equipment::class),
            description: 'The equipment associated with this room'
        )]
        #[ORM\ManyToOne(targetEntity: Equipment::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        #[Assert\NotNull(message: 'Equipment is required')]
        private EquipmentModel $equipment;

        /**
         * The quantity of the equipment in the room.
         */
        #[OA\Property(
            property: 'quantity',
            description: 'The quantity of the equipment in the room',
            type: 'integer',
            minimum: 1,
            example: 5
        )]
        #[ORM\Column(type: 'integer')]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\NotNull(message: 'Quantity is required')]
        #[Assert\GreaterThan(value: 0, message: 'Quantity must be greater than zero')]
        private int $quantity;

        /**
         * The timestamp when the equipment was assigned to the room.
         */
        #[OA\Property(
            property: 'assignedAt',
            description: 'The timestamp when the equipment was assigned to the room',
            type: 'string',
            format: 'date-time',
            example: '2024-01-01T12:00:00+00:00'
        )]
        #[ORM\Column(type: 'datetime')]
        #[Groups(self::READ_GROUPS)]
        private DateTime $assignedAt;

        public function __construct()
        {
            $this->assignedAt = new DateTime();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getRoom(): RoomModel
        {
            return $this->room;
        }

        #[OA\Property(
            property: 'roomId',
            description: 'The ID of the room associated with the equipment',
            type: 'integer',
            example: 1
        )]
        #[Groups(self::READ_GROUPS)]
        public function getRoomId(): ?int
        {
            return $this->room?->getId();
        }

        public function setRoom(RoomModel $room): static
        {
            $this->room = $room;

            return $this;
        }

        public function getEquipment(): EquipmentModel
        {
            return $this->equipment;
        }

        #[OA\Property(
            property: 'equipmentId',
            description: 'The ID of the equipment associated with the room',
            type: 'integer',
            example: 1
        )]
        #[Groups(self::READ_GROUPS)]
        public function getEquipmentId(): ?int
        {
            return $this->equipment?->getId();
        }

        public function setEquipment(EquipmentModel $equipment): static
        {
            $this->equipment = $equipment;

            return $this;
        }

        public function getQuantity(): int
        {
            return $this->quantity;
        }

        public function setQuantity(int $quantity): static
        {
            $this->quantity = $quantity;

            return $this;
        }

        public function getAssignedAt(): DateTime
        {
            return $this->assignedAt;
        }

        public function setAssignedAt(DateTime $assignedAt): static
        {
            $this->assignedAt = $assignedAt;

            return $this;
        }
    }
