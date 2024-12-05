<?php

    namespace App\Entity;

    use App\Core\Enum\ReservationStatus;
    use App\Core\Model\ReservationModel;
    use App\Core\Model\RoomModel;
    use App\Core\Model\UserModel;
    use App\Repository\ReservationRepository;
    use DateTime;
    use Doctrine\ORM\Mapping as ORM;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use OpenApi\Attributes as OA;


    /**
     * Represents a reservation for a room by a user.
     */
    #[ORM\Entity(repositoryClass: ReservationRepository::class)]
    #[ORM\Table(name: 'reservations')]
    class Reservation implements ReservationModel
    {
        /**
         * The unique identifier of the reservation.
         *
         * @var int|null
         */
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        /**
         * The room associated with the reservation.
         *
         * @var RoomModel|null
         */
        #[ORM\ManyToOne(targetEntity: Room::class)]
        #[ORM\JoinColumn(nullable: false)]
        private ?RoomModel $room;

        /**
         * The user who made the reservation.
         *
         * @var UserModel|null
         */
        #[ORM\ManyToOne(targetEntity: User::class)]
        #[ORM\JoinColumn(nullable: false)]
        private ?UserModel $user;

        /**
         * The start date and time of the reservation.
         *
         * @var DateTime
         */
        #[ORM\Column(type: 'datetime')]
        private DateTime $startAt;

        /**
         * The end date and time of the reservation.
         *
         * @var DateTime
         */
        #[ORM\Column(type: 'datetime')]
        private DateTime $endAt;

        /**
         * The current status of the reservation.
         *
         * @var ReservationStatus|null
         */
        #[ORM\Column(enumType: ReservationStatus::class)]
        #[OA\Property(ref: new Model(type: ReservationStatus::class))]
        private ?ReservationStatus $status;

        /**
         * The timestamp when the reservation was created.
         *
         * @var DateTime
         */
        #[ORM\Column(type: 'datetime')]
        private DateTime $createdAt;

        /**
         * The timestamp when the reservation was last updated.
         *
         * @var DateTime|null
         */
        #[ORM\Column(type: 'datetime', nullable: true)]
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
        public function setCreatedAt(\DateTime $createdAt): void
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
