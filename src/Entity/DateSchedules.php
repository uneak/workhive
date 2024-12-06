<?php

    namespace App\Entity;

    use App\Core\Model\DateSchedulesModel;
    use App\Core\Model\RoomModel;
    use App\Repository\DateSchedulesRepository;
    use DateTimeInterface;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use OpenApi\Attributes as OA;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Represents a specific schedule for a date, including open/close times and status.
     *
     * Groups:
     * - read: Global read group
     * - write: Global write group
     * - schedule:read: Schedule-specific read group
     * - schedule:write: Schedule-specific write group
     */
    #[OA\Schema(
        title: 'DateSchedules',
        description: 'Represents specific date schedules for rooms or equipment availability',
        type: 'object'
    )]
    #[ORM\Entity(repositoryClass: DateSchedulesRepository::class)]
    #[ORM\Table(name: 'date_schedules')]
    class DateSchedules implements DateSchedulesModel
    {
        /**
         * The unique identifier of the schedule.
         */
        #[OA\Property(description: 'The unique identifier of the schedule', type: 'integer', example: 1)]
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        #[Groups(DateSchedulesModel::READ_GROUPS)]
        private ?int $id = null;

        /**
         * An optional name or label for the schedule.
         */
        #[OA\Property(description: 'An optional name or label for the schedule', type: 'string', example: 'Holiday Schedule', nullable: true)]
        #[ORM\Column(length: 255, nullable: true)]
        #[Groups(DateSchedulesModel::RW_GROUPS)]
        private ?string $name = null;

        /**
         * The date of the schedule.
         */
        #[OA\Property(description: 'The date of the schedule', type: 'string', format: 'date', example: '2024-01-01')]
        #[ORM\Column(type: Types::DATE_MUTABLE)]
        #[Groups(DateSchedulesModel::RW_GROUPS)]
        #[Assert\NotNull(message: 'Date is required')]
        private ?DateTimeInterface $date = null;

        /**
         * The starting time of the schedule.
         */
        #[OA\Property(description: 'The starting time of the schedule', type: 'string', format: 'time', example: '09:00:00')]
        #[ORM\Column(type: Types::TIME_MUTABLE)]
        #[Groups(DateSchedulesModel::RW_GROUPS)]
        #[Assert\NotNull(message: 'Opening time is required')]
        private ?DateTimeInterface $startedAt = null;

        /**
         * The ending time of the schedule.
         */
        #[OA\Property(description: 'The ending time of the schedule', type: 'string', format: 'time', example: '17:00:00')]
        #[ORM\Column(type: Types::TIME_MUTABLE)]
        #[Groups(DateSchedulesModel::RW_GROUPS)]
        #[Assert\NotNull(message: 'Closing time is required')]
        private ?DateTimeInterface $endedAt = null;

        /**
         * Indicates whether the schedule is open during the specified time.
         */
        #[OA\Property(description: 'Indicates whether the schedule is open during the specified time', type: 'boolean', example: true)]
        #[ORM\Column]
        #[Groups(DateSchedulesModel::RW_GROUPS)]
        private ?bool $isOpen = null;

        /**
         * The room associated with this schedule.
         */
        #[OA\Property(ref: new Model(type: Room::class), description: 'The room associated with this schedule')]
        #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: 'dateSchedules')]
        #[ORM\JoinColumn(nullable: false)]
        #[Groups(DateSchedulesModel::WRITE_GROUPS)]
        #[Assert\NotNull(message: 'Room is required')]
        private ?RoomModel $room = null;

        /**
         * Get the unique identifier of the schedule.
         *
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get the name or label of the schedule.
         *
         * @return string|null
         */
        public function getName(): ?string
        {
            return $this->name;
        }

        /**
         * Set the name or label of the schedule.
         *
         * @param string|null $name
         *
         * @return $this
         */
        public function setName(?string $name): static
        {
            $this->name = $name;

            return $this;
        }

        /**
         * Get the date of the schedule.
         *
         * @return \DateTimeInterface|null
         */
        public function getDate(): ?DateTimeInterface
        {
            return $this->date;
        }

        /**
         * Set the date of the schedule.
         *
         * @param \DateTimeInterface $date
         *
         * @return $this
         */
        public function setDate(DateTimeInterface $date): static
        {
            $this->date = $date;

            return $this;
        }

        /**
         * Get the starting time of the schedule.
         *
         * @return \DateTimeInterface|null
         */
        public function getStartedAt(): ?DateTimeInterface
        {
            return $this->startedAt;
        }

        /**
         * Set the starting time of the schedule.
         *
         * @param \DateTimeInterface $startedAt
         *
         * @return $this
         */
        public function setStartedAt(DateTimeInterface $startedAt): static
        {
            $this->startedAt = $startedAt;

            return $this;
        }

        /**
         * Get the ending time of the schedule.
         *
         * @return \DateTimeInterface|null
         */
        public function getEndedAt(): ?DateTimeInterface
        {
            return $this->endedAt;
        }

        /**
         * Set the ending time of the schedule.
         *
         * @param \DateTimeInterface $endedAt
         *
         * @return $this
         */
        public function setEndedAt(DateTimeInterface $endedAt): static
        {
            $this->endedAt = $endedAt;

            return $this;
        }

        /**
         * Check if the schedule is open during the specified time.
         *
         * @return bool|null
         */
        public function isOpen(): ?bool
        {
            return $this->isOpen;
        }

        /**
         * Set whether the schedule is open during the specified time.
         *
         * @param bool $isOpen
         *
         * @return $this
         */
        public function setIsOpen(bool $isOpen): static
        {
            $this->isOpen = $isOpen;

            return $this;
        }

        /**
         * Get the room associated with this schedule.
         *
         * @return RoomModel|null
         */
        public function getRoom(): ?RoomModel
        {
            return $this->room;
        }

        /**
         * Set the room associated with this schedule.
         *
         * @param ?RoomModel $room
         *
         * @return $this
         */
        public function setRoom(?RoomModel $room): static
        {
            $this->room = $room;

            return $this;
        }

        /**
         * Get the ID of the room associated with this schedule.
         *
         * @return int|null
         */
        #[Groups(DateSchedulesModel::READ_GROUPS)]
        public function getRoomId(): ?int
        {
            return $this->room?->getId();
        }
    }
