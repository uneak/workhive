<?php

    namespace App\Entity;

    use App\Core\Model\EquipmentModel;
    use App\Core\Model\RoomEquipmentModel;
    use App\Core\Model\RoomModel;
    use App\Repository\RoomEquipmentRepository;
    use DateTime;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * Represents the association of equipment with a room and its quantity.
     */
    #[ORM\Entity(repositoryClass: RoomEquipmentRepository::class)]
    #[ORM\Table(name: 'room_equipment')]
    class RoomEquipment implements RoomEquipmentModel
    {
        /**
         * The unique identifier of the room-equipment association.
         *
         * @var int|null
         */
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        /**
         * The room associated with the equipment.
         *
         * @var RoomModel
         */
        #[ORM\ManyToOne(targetEntity: Room::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        private RoomModel $room;

        /**
         * The equipment associated with the room.
         *
         * @var EquipmentModel
         */
        #[ORM\ManyToOne(targetEntity: Equipment::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        private EquipmentModel $equipment;

        /**
         * The quantity of the equipment in the room.
         *
         * @var int
         */
        #[ORM\Column(type: 'integer')]
        private int $quantity;

        /**
         * The timestamp when the equipment was assigned to the room.
         *
         * @var \DateTime
         */
        #[ORM\Column(type: 'datetime')]
        private DateTime $assignedAt;

        /**
         * Initializes the room-equipment association with the assignment timestamp.
         */
        public function __construct()
        {
            $this->assignedAt = new DateTime();
        }

        /**
         * Get the unique identifier of the room-equipment association.
         *
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get the room associated with the equipment.
         *
         * @return Room
         */
        public function getRoom(): Room
        {
            return $this->room;
        }

        /**
         * Set the room associated with the equipment.
         *
         * @param RoomModel $room
         *
         * @return $this
         */
        public function setRoom(RoomModel $room): static
        {
            $this->room = $room;

            return $this;
        }

        /**
         * Get the equipment associated with the room.
         *
         * @return EquipmentModel
         */
        public function getEquipment(): EquipmentModel
        {
            return $this->equipment;
        }

        /**
         * Set the equipment associated with the room.
         *
         * @param EquipmentModel $equipment
         *
         * @return $this
         */
        public function setEquipment(EquipmentModel $equipment): static
        {
            $this->equipment = $equipment;

            return $this;
        }

        /**
         * Get the quantity of the equipment in the room.
         *
         * @return int
         */
        public function getQuantity(): int
        {
            return $this->quantity;
        }

        /**
         * Set the quantity of the equipment in the room.
         *
         * @param int $quantity
         *
         * @return $this
         */
        public function setQuantity(int $quantity): static
        {
            $this->quantity = $quantity;

            return $this;
        }

        /**
         * Get the timestamp when the equipment was assigned to the room.
         *
         * @return \DateTime
         */
        public function getAssignedAt(): DateTime
        {
            return $this->assignedAt;
        }

        /**
         * Set the timestamp when the equipment was assigned to the room.
         *
         * @param \DateTime $assignedAt
         *
         * @return $this
         */
        public function setAssignedAt(DateTime $assignedAt): static
        {
            $this->assignedAt = $assignedAt;

            return $this;
        }
    }
