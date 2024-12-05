<?php

    namespace App\Entity;

    use App\Core\Enum\Status;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use OpenApi\Attributes as OA;
    use App\Core\Model\DateSchedulesModel;
    use App\Core\Model\ReservationModel;
    use App\Core\Model\RoomModel;
    use App\Core\Model\WeekSchedulesModel;
    use App\Repository\RoomRepository;
    use DateTime;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Room Entity
     *
     * This entity represents a room that can be reserved in the application.
     * It includes physical characteristics like dimensions and capacity,
     * as well as administrative information like status and description.
     *
     * Groups:
     * - read: Global read group
     * - write: Global write group
     * - room:read: Room-specific read group
     * - room:write: Room-specific write group
     */
    #[OA\Schema(
        title: 'Room',
        description: 'Represents a room that can be reserved for various purposes',
        type: 'object'
    )]
    #[ORM\Entity(repositoryClass: RoomRepository::class)]
    #[ORM\Table(name: 'rooms')]
    class Room implements RoomModel
    {
        public const READ_GROUPS = ['read', RoomModel::GROUP_PREFIX . ':read'];
        public const WRITE_GROUPS = ['write', RoomModel::GROUP_PREFIX . ':write'];

        /**
         * The unique identifier of the room.
         */
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        #[Groups(self::READ_GROUPS)]
        private ?int $id = null;

        /**
         * The name of the room.
         * Must be between 2 and 100 characters.
         */
        #[ORM\Column(type: 'string', length: 100)]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\NotBlank(message: 'Room name is required')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'Room name must be at least {{ limit }} characters long',
            maxMessage: 'Room name cannot be longer than {{ limit }} characters'
        )]
        private string $name;

        /**
         * The maximum capacity of the room.
         * Must be between 1 and 100 people.
         */
        #[ORM\Column(type: 'integer')]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\NotBlank(message: 'Capacity is required')]
        #[Assert\Type(
            type: 'integer',
            message: 'Capacity must be a whole number'
        )]
        #[Assert\Range(
            notInRangeMessage: 'Capacity must be between {{ min }} and {{ max }} people',
            min: 1,
            max: 100
        )]
        private int $capacity;

        /**
         * The width of the room in meters.
         * Must be between 0.1 and 50.0 meters.
         */
        #[ORM\Column(type: 'float')]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\NotBlank(message: 'Width is required')]
        #[Assert\Type(
            type: 'float',
            message: 'Width must be a decimal number'
        )]
        #[Assert\Range(
            notInRangeMessage: 'Width must be between {{ min }} and {{ max }} meters',
            min: 0.1,
            max: 50.0
        )]
        private float $width;

        /**
         * The length of the room in meters.
         * Must be between 0.1 and 50.0 meters.
         */
        #[ORM\Column(type: 'float')]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\NotBlank(message: 'Length is required')]
        #[Assert\Type(
            type: 'float',
            message: 'Length must be a decimal number'
        )]
        #[Assert\Range(
            notInRangeMessage: 'Length must be between {{ min }} and {{ max }} meters',
            min: 0.1,
            max: 50.0
        )]
        private float $length;

        /**
         * The current status of the room.
         * Indicates whether the room is available for booking.
         */
        #[OA\Property(
            ref: new Model(type: Status::class),
            description: 'Status of the room'
        )]
        #[ORM\Column(enumType: Status::class)]
        #[Groups(self::READ_GROUPS)]
        #[Assert\NotNull(message: 'Status is required')]
        private ?Status $status = null;

        /**
         * A detailed description of the room.
         * Optional, limited to 1000 characters.
         */
        #[ORM\Column(type: 'text', nullable: true)]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\Length(
            max: 1000,
            maxMessage: 'Description cannot be longer than {{ limit }} characters'
        )]
        private ?string $description;

        /**
         * The photo URL of the room (optional).
         */
        #[ORM\Column(type: 'string', length: 255, nullable: true)]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        private ?string $photo;

        /**
         * The timestamp when the room was created.
         */
        #[ORM\Column(type: 'datetime')]
        #[Groups(self::READ_GROUPS)]
        private DateTime $createdAt;

        /**
         * The timestamp when the room was last updated.
         */
        #[ORM\Column(type: 'datetime', nullable: true)]
        #[Groups(self::READ_GROUPS)]
        private ?DateTime $updatedAt;

        /**
         * Reservations associated with this room.
         *
         * @var Collection<int, ReservationModel>
         */
        #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'room', cascade: ['persist', 'remove'])]
        private Collection $reservations;

        /**
         * Weekly schedules associated with this room.
         *
         * @var Collection<int, WeekSchedulesModel>
         */
        #[ORM\OneToMany(targetEntity: WeekSchedules::class, mappedBy: 'room', orphanRemoval: true)]
        private Collection $weekSchedules;

        /**
         * Date-specific schedules associated with this room.
         *
         * @var Collection<int, DateSchedulesModel>
         */
        #[ORM\OneToMany(targetEntity: DateSchedules::class, mappedBy: 'room', orphanRemoval: true)]
        private Collection $dateSchedules;

        /**
         * Initializes the room with default values and empty collections.
         */
        public function __construct()
        {
            $this->status = Status::ACTIVE;
            $this->createdAt = new DateTime();
            $this->reservations = new ArrayCollection();
            $this->weekSchedules = new ArrayCollection();
            $this->dateSchedules = new ArrayCollection();
        }

        /**
         * Get the unique identifier of the room.
         *
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get the name of the room.
         *
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }

        /**
         * Set the name of the room.
         *
         * @param string $name
         *
         * @return $this
         */
        public function setName(string $name): static
        {
            $this->name = $name;

            return $this;
        }

        /**
         * Get the capacity of the room.
         *
         * @return int
         */
        public function getCapacity(): int
        {
            return $this->capacity;
        }

        /**
         * Set the capacity of the room.
         *
         * @param int $capacity
         *
         * @return $this
         */
        public function setCapacity(int $capacity): static
        {
            $this->capacity = $capacity;

            return $this;
        }

        /**
         * Get the width of the room.
         *
         * @return float
         */
        public function getWidth(): float
        {
            return $this->width;
        }

        /**
         * Set the width of the room.
         *
         * @param float $width
         *
         * @return $this
         */
        public function setWidth(float $width): static
        {
            $this->width = $width;

            return $this;
        }

        /**
         * Get the length of the room.
         *
         * @return float
         */
        public function getLength(): float
        {
            return $this->length;
        }

        /**
         * Set the length of the room.
         *
         * @param float $length
         *
         * @return $this
         */
        public function setLength(float $length): static
        {
            $this->length = $length;

            return $this;
        }

        /**
         * Get the total area of the room (width × length).
         *
         * @return float
         */
        public function getArea(): float
        {
            return $this->width * $this->length;
        }

        /**
         * Get the description of the room.
         *
         * @return string|null
         */
        public function getDescription(): ?string
        {
            return $this->description;
        }

        /**
         * Set the description of the room.
         *
         * @param string|null $description
         *
         * @return $this
         */
        public function setDescription(?string $description): static
        {
            $this->description = $description;

            return $this;
        }

        /**
         * Get the photo of the room.
         *
         * @return string|null
         */
        public function getPhoto(): ?string
        {
            return $this->photo;
        }

        /**
         * Set the photo of the room.
         *
         * @param string|null $photo
         *
         * @return $this
         */
        public function setPhoto(?string $photo): static
        {
            $this->photo = $photo;

            return $this;
        }

        /**
         * Get the status of the room.
         *
         * @return Status|null
         */
        public function getStatus(): ?Status
        {
            return $this->status;
        }

        /**
         * Set the status of the room.
         *
         * @param Status $status
         *
         * @return $this
         */
        public function setStatus(Status $status): static
        {
            $this->status = $status;

            return $this;
        }

        /**
         * Check if the room is active.
         *
         * @return bool
         */
        public function isActive(): bool
        {
            return $this->status === Status::ACTIVE;
        }

        /**
         * Get the reservations associated with the room.
         *
         * @return Collection<int, ReservationModel>
         */
        public function getReservations(): Collection
        {
            return $this->reservations;
        }

        /**
         * Add a reservation to the room.
         *
         * @param ReservationModel $reservation
         *
         * @return $this
         */
        public function addReservation(ReservationModel $reservation): static
        {
            if (!$this->reservations->contains($reservation)) {
                $this->reservations->add($reservation);
                $reservation->setRoom($this);
            }

            return $this;
        }

        /**
         * Remove a reservation from the room.
         *
         * @param ReservationModel $reservation
         *
         * @return $this
         */
        public function removeReservation(ReservationModel $reservation): static
        {
            if ($this->reservations->removeElement($reservation)) {
                if ($reservation->getRoom() === $this) {
                    $reservation->setRoom(null);
                }
            }

            return $this;
        }

        /**
         * Get the collection of weekly schedules associated with the room.
         *
         * @return Collection<int, WeekSchedulesModel> A collection of WeekSchedules objects.
         */
        public function getWeekSchedules(): Collection
        {
            return $this->weekSchedules;
        }

        /**
         * Add a weekly schedule to the room.
         *
         * @param WeekSchedulesModel $weekSchedule The weekly schedule to add.
         *
         * @return $this
         */
        public function addWeekSchedule(WeekSchedulesModel $weekSchedule): static
        {
            if (!$this->weekSchedules->contains($weekSchedule)) {
                $this->weekSchedules->add($weekSchedule);
                $weekSchedule->setRoom($this);
            }

            return $this;
        }

        /**
         * Remove a weekly schedule from the room.
         *
         * @param WeekSchedulesModel $weekSchedule The weekly schedule to remove.
         *
         * @return $this
         */
        public function removeWeekSchedule(WeekSchedulesModel $weekSchedule): static
        {
            if ($this->weekSchedules->removeElement($weekSchedule)) {
                // Set the owning side to null (unless already changed).
                if ($weekSchedule->getRoom() === $this) {
                    $weekSchedule->setRoom(null);
                }
            }

            return $this;
        }

        /**
         * Get the collection of date-specific schedules associated with the room.
         *
         * @return Collection<int, DateSchedulesModel> A collection of DateSchedules objects.
         */
        public function getDateSchedules(): Collection
        {
            return $this->dateSchedules;
        }

        /**
         * Add a date-specific schedule to the room.
         *
         * @param DateSchedulesModel $dateSchedule The date-specific schedule to add.
         *
         * @return $this
         */
        public function addDateSchedule(DateSchedulesModel $dateSchedule): static
        {
            if (!$this->dateSchedules->contains($dateSchedule)) {
                $this->dateSchedules->add($dateSchedule);
                $dateSchedule->setRoom($this);
            }

            return $this;
        }

        /**
         * Remove a date-specific schedule from the room.
         *
         * @param DateSchedulesModel $dateSchedule The date-specific schedule to remove.
         *
         * @return $this
         */
        public function removeDateSchedule(DateSchedulesModel $dateSchedule): static
        {
            if ($this->dateSchedules->removeElement($dateSchedule)) {
                // Set the owning side to null (unless already changed).
                if ($dateSchedule->getRoom() === $this) {
                    $dateSchedule->setRoom(null);
                }
            }

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
         * Set the timestamp when this record was last updated.
         *
         * @param \DateTime|null $updatedAt
         * @return $this
         */
        public function setUpdatedAt(?DateTime $updatedAt): static
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }
    }
