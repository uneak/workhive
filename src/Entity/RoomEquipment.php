<?php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: 'room_equipment')]
    class RoomEquipment
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        #[ORM\ManyToOne(targetEntity: Room::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        private Room $room;

        #[ORM\ManyToOne(targetEntity: Equipment::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        private Equipment $equipment;

        #[ORM\Column(type: 'integer')]
        private int $quantity;

        #[ORM\Column(type: 'datetime')]
        private \DateTime $assignedAt;

        public function __construct()
        {
            $this->assignedAt = new \DateTime();
        }

        // Getters and Setters
        public function getId(): ?int
        {
            return $this->id;
        }

        public function getRoom(): Room
        {
            return $this->room;
        }

        public function setRoom(Room $room): self
        {
            $this->room = $room;

            return $this;
        }

        public function getEquipment(): Equipment
        {
            return $this->equipment;
        }

        public function setEquipment(Equipment $equipment): self
        {
            $this->equipment = $equipment;

            return $this;
        }

        public function getQuantity(): int
        {
            return $this->quantity;
        }

        public function setQuantity(int $quantity): self
        {
            $this->quantity = $quantity;

            return $this;
        }

        public function getAssignedAt(): \DateTime
        {
            return $this->assignedAt;
        }

        public function setAssignedAt(\DateTime $assignedAt): self
        {
            $this->assignedAt = $assignedAt;

            return $this;
        }
    }
