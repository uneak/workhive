<?php

    namespace App\Entity;

    use App\Core\Enum\ReservationStatus;
    use App\Core\Model\ReservationModel;
    use App\Core\Model\RoomModel;
    use App\Core\Model\UserModel;
    use App\Repository\ReservationRepository;
    use DateTime;
    use Doctrine\ORM\Mapping as ORM;
    use OpenApi\Attributes as OA;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use Symfony\Component\Validator\Constraints as Assert;
    use Symfony\Component\Serializer\Annotation\Groups;

    /**
     * Reservation Entity
     *
     * This entity represents a room reservation made by a user.
     * It includes booking details such as start and end times,
     * the associated room and user, and the current status of the reservation.
     *
     * Groups:
     * - read: Global read group
     * - write: Global write group
     * - reservation:read: Reservation-specific read group
     * - reservation:write: Reservation-specific write group
     */
    #[OA\Schema(
        title: 'Reservation',
        description: 'Represents a room reservation with associated equipment and payment details',
        type: 'object'
    )]
    #[ORM\Entity(repositoryClass: ReservationRepository::class)]
    #[ORM\Table(name: 'reservations')]
    class Reservation implements ReservationModel
    {
        /**
         * The unique identifier of the reservation.
         */
        #[OA\Property(
            property: 'id',
            description: 'The unique identifier of the reservation',
            type: 'integer',
            example: 1
        )]
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        #[Groups(ReservationModel::READ_GROUPS)]
        private ?int $id = null;

        /**
         * The room being reserved.
         * Must be a valid room from the system.
         */
        #[OA\Property(
            ref: new Model(type: Room::class),
            description: 'The room being reserved'
        )]
        #[ORM\ManyToOne(targetEntity: Room::class)]
        #[ORM\JoinColumn(nullable: false)]
        #[Groups(ReservationModel::WRITE_GROUPS)]
        #[Assert\NotNull(message: 'Room is required')]
        private ?RoomModel $room;

        /**
         * The user making the reservation.
         * Must be a valid user from the system.
         */
        #[OA\Property(
            ref: new Model(type: User::class),
            description: 'The user making the reservation'
        )]
        #[ORM\ManyToOne(targetEntity: User::class)]
        #[ORM\JoinColumn(nullable: false)]
        #[Groups(ReservationModel::WRITE_GROUPS)]
        #[Assert\NotNull(message: 'User is required')]
        private ?UserModel $user;

        /**
         * The start date and time of the reservation.
         * Must be today or in the future.
         */
        #[OA\Property(
            property: 'startAt',
            description: 'The start date and time of the reservation',
            type: 'string',
            format: 'date-time',
            example: '2024-01-01T09:00:00+00:00'
        )]
        #[ORM\Column(type: 'datetime')]
        #[Groups(ReservationModel::RW_GROUPS)]
        #[Assert\NotNull(message: 'Start date is required')]
        #[Assert\Type(type: DateTime::class)]
        #[Assert\GreaterThanOrEqual(
            'today',
            message: 'Start date must be today or in the future'
        )]
        private DateTime $startAt;

        /**
         * The end date and time of the reservation.
         * Must be after the start date.
         */
        #[OA\Property(
            property: 'endAt',
            description: 'The end date and time of the reservation',
            type: 'string',
            format: 'date-time',
            example: '2024-01-01T17:00:00+00:00'
        )]
        #[ORM\Column(type: 'datetime')]
        #[Groups(ReservationModel::RW_GROUPS)]
        #[Assert\NotNull(message: 'End date is required')]
        #[Assert\Type(type: DateTime::class)]
        #[Assert\Expression(
            "this.getEndAt() > this.getStartAt()",
            message: 'End date must be after the start date'
        )]
        private DateTime $endAt;

        /**
         * The current status of the reservation.
         * Tracks the state of the reservation (pending, confirmed, etc.).
         */
        #[OA\Property(
            ref: new Model(type: ReservationStatus::class),
            description: 'The current status of the reservation'
        )]
        #[ORM\Column(enumType: ReservationStatus::class)]
        #[Groups(ReservationModel::READ_GROUPS)]
        #[Assert\NotNull(message: 'Status is required')]
        private ?ReservationStatus $status;

        /**
         * The timestamp when the reservation was created.
         * Automatically set when the reservation is made.
         */
        #[OA\Property(
            property: 'createdAt',
            description: 'The timestamp when the reservation was created',
            type: 'string',
            format: 'date-time',
            example: '2024-01-01T12:00:00+00:00'
        )]
        #[ORM\Column(type: 'datetime')]
        #[Groups(ReservationModel::READ_GROUPS)]
        #[Assert\NotNull(message: 'Created date is required')]
        private DateTime $createdAt;

        /**
         * The timestamp when the reservation was last updated.
         * Optional, updated automatically when changes are made.
         */
        #[OA\Property(
            property: 'updatedAt',
            description: 'The timestamp when the reservation was last updated',
            type: 'string',
            format: 'date-time',
            example: '2024-01-02T15:30:00+00:00',
            nullable: true
        )]
        #[ORM\Column(type: 'datetime', nullable: true)]
        #[Groups(ReservationModel::READ_GROUPS)]
        #[Assert\Type(type: DateTime::class)]
        private ?DateTime $updatedAt;

        /**
         * Initializes the reservation with default values.
         */
        public function __construct()
        {
            $this->status = ReservationStatus::PENDING;
            $this->createdAt = new DateTime();
        }

        /**
         * Get the unique identifier of the reservation.
         *
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get the room associated with the reservation.
         *
         * @return RoomModel|null
         */
        public function getRoom(): ?RoomModel
        {
            return $this->room;
        }

        /**
         * Set the room associated with the reservation.
         *
         * @param RoomModel|null $room
         * @return $this
         */
        public function setRoom(?RoomModel $room): static
        {
            $this->room = $room;

            return $this;
        }

        /**
         * Get the ID of the room associated with the reservation.
         *
         * @return int|null
         */
        #[OA\Property(
            property: 'roomId',
            description: 'The ID of the room associated with the reservation',
            type: 'integer',
            example: 1
        )]
        #[Groups(ReservationModel::READ_GROUPS)]
        public function getRoomId(): ?int
        {
            return $this->room?->getId();
        }

        /**
         * Get the user who made the reservation.
         *
         * @return UserModel|null
         */
        public function getUser(): ?UserModel
        {
            return $this->user;
        }

        /**
         * Set the user who made the reservation.
         *
         * @param UserModel|null $user
         * @return $this
         */
        public function setUser(?UserModel $user): static
        {
            $this->user = $user;

            return $this;
        }

        /**
         * Get the ID of the user who made the reservation.
         *
         * @return int|null
         */
        #[OA\Property(
            property: 'userId',
            description: 'The ID of the user who made the reservation',
            type: 'integer',
            example: 1
        )]
        #[Groups(ReservationModel::READ_GROUPS)]
        public function getUserId(): ?int
        {
            return $this->user?->getId();
        }

        /**
         * Get the start date and time of the reservation.
         *
         * @return DateTime
         */
        public function getStartAt(): DateTime
        {
            return $this->startAt;
        }

        /**
         * Set the start date and time of the reservation.
         *
         * @param DateTime $startAt
         * @return $this
         */
        public function setStartAt(DateTime $startAt): static
        {
            $this->startAt = $startAt;

            return $this;
        }

        /**
         * Get the end date and time of the reservation.
         *
         * @return DateTime
         */
        public function getEndAt(): DateTime
        {
            return $this->endAt;
        }

        /**
         * Set the end date and time of the reservation.
         *
         * @param DateTime $endAt
         * @return $this
         */
        public function setEndAt(DateTime $endAt): static
        {
            $this->endAt = $endAt;

            return $this;
        }

        /**
         * Get the current status of the reservation.
         *
         * @return ReservationStatus|null
         */
        public function getStatus(): ?ReservationStatus
        {
            return $this->status;
        }

        /**
         * Set the current status of the reservation.
         *
         * @param ReservationStatus $status
         * @return $this
         */
        public function setStatus(ReservationStatus $status): static
        {
            $this->status = $status;

            return $this;
        }

        /**
         * Get the timestamp when the reservation was created.
         *
         * @return DateTime
         */
        public function getCreatedAt(): DateTime
        {
            return $this->createdAt;
        }

        /**
         * Set the timestamp when the equipment was created.
         *
         * @param \DateTime $createdAt
         *
         * @return void
         */
        public function setCreatedAt(DateTime $createdAt): void
        {
            $this->createdAt = $createdAt;
        }

        /**
         * Get the timestamp when the reservation was last updated.
         *
         * @return DateTime|null
         */
        public function getUpdatedAt(): ?DateTime
        {
            return $this->updatedAt;
        }

        /**
         * Set the timestamp when the reservation was last updated.
         *
         * @param DateTime|null $updatedAt
         * @return $this
         */
        public function setUpdatedAt(?DateTime $updatedAt): static
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }
    }
