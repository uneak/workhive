<?php

    namespace App\Entity;

    use App\Core\Model\EquipmentModel;
    use App\Core\Model\ReservationEquipmentModel;
    use App\Core\Model\ReservationModel;
    use App\Repository\ReservationEquipmentRepository;
    use DateTime;
    use Doctrine\ORM\Mapping as ORM;
    use OpenApi\Attributes as OA;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Represents the equipment reserved as part of a reservation.
     *
     * Groups:
     * - read: Global read group
     * - write: Global write group
     * - reservation-equipment:read: Reservation equipment-specific read group
     * - reservation-equipment:write: Reservation equipment-specific write group
     */
    #[OA\Schema(
        title: 'ReservationEquipment',
        description: 'Represents equipment included in a reservation with its quantity',
        type: 'object'
    )]
    #[ORM\Entity(repositoryClass: ReservationEquipmentRepository::class)]
    #[ORM\Table(name: 'reservation_equipment')]
    class ReservationEquipment implements ReservationEquipmentModel
    {
        public const READ_GROUPS = ['read', ReservationEquipmentModel::GROUP_PREFIX . ':read'];
        public const WRITE_GROUPS = ['write', ReservationEquipmentModel::GROUP_PREFIX . ':write'];

        /**
         * The unique identifier of the reservation equipment.
         *
         * @var int|null
         */
        #[OA\Property(
            property: 'id',
            description: 'The unique identifier of the reservation equipment',
            type: 'integer',
            example: 1
        )]
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        #[Groups(self::READ_GROUPS)]
        private ?int $id = null;

        /**
         * The reservation associated with this equipment.
         *
         * @var ?ReservationModel
         */
        #[OA\Property(
            ref: new Model(type: Reservation::class),
            description: 'The reservation associated with this equipment'
        )]
        #[ORM\ManyToOne(targetEntity: Reservation::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        #[Groups(self::WRITE_GROUPS)]
        #[Assert\NotNull(message: 'Reservation is required')]
        private ?ReservationModel $reservation = null;

        /**
         * The equipment associated with this reservation.
         *
         * @var EquipmentModel
         */
        #[OA\Property(
            ref: new Model(type: Equipment::class),
            description: 'The equipment associated with this reservation'
        )]
        #[ORM\ManyToOne(targetEntity: Equipment::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        #[Groups(self::WRITE_GROUPS)]
        #[Assert\NotNull(message: 'Equipment is required')]
        private ?EquipmentModel $equipment = null;

        /**
         * The quantity of the equipment reserved.
         *
         * @var int
         */
        #[OA\Property(
            property: 'quantity',
            description: 'The quantity of the equipment reserved',
            type: 'integer',
            minimum: 1,
            example: 2
        )]
        #[ORM\Column(type: 'integer')]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\NotNull(message: 'Quantity is required')]
        #[Assert\GreaterThan(
            value: 0,
            message: 'Quantity must be greater than zero'
        )]
        private int $quantity;

        /**
         * The timestamp when this record was created.
         *
         * @var \DateTime
         */
        #[OA\Property(
            property: 'createdAt',
            description: 'The timestamp when this record was created',
            type: 'string',
            format: 'date-time',
            example: '2024-01-01T12:00:00+00:00'
        )]
        #[ORM\Column(type: 'datetime')]
        #[Groups(self::READ_GROUPS)]
        private DateTime $createdAt;

        /**
         * The timestamp when this record was last updated.
         *
         * @var \DateTime|null
         */
        #[OA\Property(
            property: 'updatedAt',
            description: 'The timestamp when this record was last updated',
            type: 'string',
            format: 'date-time',
            example: '2024-01-02T15:30:00+00:00',
            nullable: true
        )]
        #[ORM\Column(type: 'datetime', nullable: true)]
        #[Groups(self::READ_GROUPS)]
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

        /**
         * Get the ID of the reservation associated with this equipment.
         *
         * @return int|null
         */
        #[Groups(self::READ_GROUPS)]
        public function getReservationId(): ?int
        {
            return $this->reservation?->getId();
        }

        /**
         * Get the ID of the equipment associated with this reservation.
         *
         * @return int|null
         */
        #[Groups(self::READ_GROUPS)]
        public function getEquipmentId(): ?int
        {
            return $this->equipment?->getId();
        }
    }
