<?php

    namespace App\Entity;

    use App\Core\Model\RoomModel;
    use App\Core\Model\WeekSchedulesModel;
    use App\Repository\WeekSchedulesRepository;
    use DateTimeInterface;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Represents the weekly schedule for a room.
     * 
     * This entity represents weekly recurring schedules for rooms.
     * It defines regular opening hours for each day of the week.
     * 
     * Groups:
     * - read: Global read group
     * - write: Global write group
     * - schedule:read: Schedule-specific read group
     * - schedule:write: Schedule-specific write group
     */
    #[ORM\Entity(repositoryClass: WeekSchedulesRepository::class)]
    #[ORM\Table(name: 'week_schedules')]
    class WeekSchedules implements WeekSchedulesModel
    {
        public const READ_GROUPS = ['read', WeekSchedulesModel::GROUP_PREFIX . ':read'];
        public const WRITE_GROUPS = ['write', WeekSchedulesModel::GROUP_PREFIX . ':write'];

        /**
         * The unique identifier of the weekly schedule.
         *
         * @var int|null
         */
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        #[Groups(self::READ_GROUPS)]
        private ?int $id = null;

        /**
         * The time the schedule starts on a given day.
         *
         * @var \DateTimeInterface|null
         */
        #[ORM\Column(type: Types::TIME_MUTABLE)]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\NotNull(message: 'Start time is required')]
        private ?DateTimeInterface $startedAt = null;

        /**
         * The time the schedule ends on a given day.
         *
         * @var \DateTimeInterface|null
         */
        #[ORM\Column(type: Types::TIME_MUTABLE)]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\NotNull(message: 'End time is required')]
        private ?DateTimeInterface $endedAt = null;

        /**
         * The day of the week for the schedule (0 = Sunday, 6 = Saturday).
         *
         * @var int|null
         */
        #[ORM\Column(type: Types::SMALLINT)]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\NotNull(message: 'Day of week is required')]
        #[Assert\Range(
            min: 0,
            max: 6,
            notInRangeMessage: 'Day of week must be between {{ min }} and {{ max }}'
        )]
        private ?int $weekDay = null;

        /**
         * The room associated with this schedule.
         *
         * @var RoomModel|null
         */
        #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: 'weekSchedules')]
        #[ORM\JoinColumn(nullable: false)]
        #[Groups(self::WRITE_GROUPS)]
        #[Assert\NotNull(message: 'Room is required')]
        private ?RoomModel $room = null;

        /**
         * Get the unique identifier of the weekly schedule.
         *
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get the time the schedule starts on a given day.
         *
         * @return \DateTimeInterface|null
         */
        public function getStartedAt(): ?DateTimeInterface
        {
            return $this->startedAt;
        }

        /**
         * Set the time the schedule starts on a given day.
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
         * Get the time the schedule ends on a given day.
         *
         * @return \DateTimeInterface|null
         */
        public function getEndedAt(): ?DateTimeInterface
        {
            return $this->endedAt;
        }

        /**
         * Set the time the schedule ends on a given day.
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
         * Get the day of the week for the schedule (0 = Sunday, 6 = Saturday).
         *
         * @return int|null
         */
        public function getWeekDay(): ?int
        {
            return $this->weekDay;
        }

        /**
         * Set the day of the week for the schedule (0 = Sunday, 6 = Saturday).
         *
         * @param int|null $weekDay
         *
         * @return $this
         */
        public function setWeekDay(?int $weekDay): static
        {
            $this->weekDay = $weekDay;

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
         * @param RoomModel|null $room
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
        #[Groups(self::READ_GROUPS)]
        public function getRoomId(): ?int
        {
            return $this->room?->getId();
        }
    }
