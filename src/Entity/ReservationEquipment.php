<?php

    namespace App\Entity;

    use App\Core\Model\EquipmentModel;
    use App\Core\Model\ReservationEquipmentModel;
    use App\Core\Model\ReservationModel;
    use App\Repository\ReservationEquipmentRepository;
    use DateTime;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * Represents the equipment reserved as part of a reservation.
     */
    #[ORM\Entity(repositoryClass: ReservationEquipmentRepository::class)]
    #[ORM\Table(name: 'reservation_equipment')]
    class ReservationEquipment implements ReservationEquipmentModel
    {
        /**
         * The unique identifier of the reservation equipment.
         *
         * @var int|null
         */
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        /**
         * The reservation associated with this equipment.
         *
         * @var ReservationModel
         */
        #[ORM\ManyToOne(targetEntity: Reservation::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        private ReservationModel $reservation;

        /**
         * The equipment associated with this reservation.
         *
         * @var EquipmentModel
         */
        #[ORM\ManyToOne(targetEntity: Equipment::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        private EquipmentModel $equipment;

        /**
         * The quantity of the equipment reserved.
         *
         * @var int
         */
        #[ORM\Column(type: 'integer')]
        private int $quantity;

        /**
         * The timestamp when this record was created.
         *
         * @var \DateTime
         */
        #[ORM\Column(type: 'datetime')]
        private DateTime $createdAt;

        /**
         * The timestamp when this record was last updated.
         *
         * @var \DateTime|null
         */
        #[ORM\Column(type: 'datetime', nullable: true)]
        private ?DateTime $updatedAt;

        /**
         * Initializes the reservation equipment with a creation timestamp.
         */
        public function __construct()
        {
            $this->createdAt = new DateTime();
        }

        /**
         * Get the unique identifier of the reservation equipment.
         *
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get the reservation associated with this equipment.
         *
         * @return ReservationModel
         */
        public function getReservation(): ReservationModel
        {
            return $this->reservation;
        }

        /**
         * Set the reservation associated with this equipment.
         *
         * @param ReservationModel $reservation
         * @return $this
         */
        public function setReservation(ReservationModel $reservation): static
        {
            $this->reservation = $reservation;

            return $this;
        }

        /**
         * Get the equipment associated with this reservation.
         *
         * @return EquipmentModel
         */
        public function getEquipment(): EquipmentModel
        {
            return $this->equipment;
        }

        /**
         * Set the equipment associated with this reservation.
         *
         * @param EquipmentModel $equipment
         * @return $this
         */
        public function setEquipment(EquipmentModel $equipment): static
        {
            $this->equipment = $equipment;

            return $this;
        }

        /**
         * Get the quantity of the equipment reserved.
         *
         * @return int
         */
        public function getQuantity(): int
        {
            return $this->quantity;
        }

        /**
         * Set the quantity of the equipment reserved.
         *
         * @param int $quantity
         * @return $this
         */
        public function setQuantity(int $quantity): static
        {
            $this->quantity = $quantity;

            return $this;
        }

        /**
         * Get the timestamp when this record was created.
         *
         * @return \DateTime
         */
        public function getCreatedAt(): DateTime
        {
            return $this->createdAt;
        }

        /**
         * Get the timestamp when this record was last updated.
         *
         * @return \DateTime|null
         */
        public function getUpdatedAt(): ?DateTime
        {
            return $this->updatedAt;
        }

        /**
         * Set the timestamp when this record was last updated.
         *
         * @param \DateTime|null $updatedAt
         * @return $this
         */
        public function setUpdatedAt(?DateTime $updatedAt): self
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }
    }
