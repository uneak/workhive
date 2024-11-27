<?php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: 'reservation_equipment')]
    class ReservationEquipment
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        #[ORM\ManyToOne(targetEntity: Reservation::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        private Reservation $reservation;

        #[ORM\ManyToOne(targetEntity: Equipment::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        private Equipment $equipment;

        #[ORM\Column(type: 'integer')]
        private int $quantity;

        #[ORM\Column(type: 'datetime')]
        private \DateTime $createdAt;

        #[ORM\Column(type: 'datetime', nullable: true)]
        private ?\DateTime $updatedAt;

        public function __construct()
        {
            $this->createdAt = new \DateTime();
        }

        // Getters and Setters
        public function getId(): ?int
        {
            return $this->id;
        }

        public function getReservation(): Reservation
        {
            return $this->reservation;
        }

        public function setReservation(Reservation $reservation): self
        {
            $this->reservation = $reservation;

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

        public function getCreatedAt(): \DateTime
        {
            return $this->createdAt;
        }

        public function getUpdatedAt(): ?\DateTime
        {
            return $this->updatedAt;
        }

        public function setUpdatedAt(?\DateTime $updatedAt): self
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }
    }
