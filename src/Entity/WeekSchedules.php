<?php

    namespace App\Entity;

    use App\Core\Model\RoomModel;
    use App\Core\Model\WeekSchedulesModel;
    use App\Repository\WeekSchedulesRepository;
    use DateTimeInterface;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * Represents the weekly schedule for a room.
     */
    #[ORM\Entity(repositoryClass: WeekSchedulesRepository::class)]
    #[ORM\Table(name: 'week_schedules')]
    class WeekSchedules implements WeekSchedulesModel
    {
        /**
         * The unique identifier of the weekly schedule.
         *
         * @var int|null
         */
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        /**
         * The time the schedule starts on a given day.
         *
         * @var \DateTimeInterface|null
         */
        #[ORM\Column(type: Types::TIME_MUTABLE)]
        private ?DateTimeInterface $startedAt = null;

        /**
         * The time the schedule ends on a given day.
         *
         * @var \DateTimeInterface|null
         */
        #[ORM\Column(type: Types::TIME_MUTABLE)]
        private ?DateTimeInterface $endedAt = null;

        /**
         * The day of the week for the schedule (0 = Sunday, 6 = Saturday).
         *
         * @var int|null
         */
        #[ORM\Column(type: Types::SMALLINT)]
        private ?int $weekDay = null;

        /**
         * The room associated with this schedule.
         *
         * @var RoomModel|null
         */
        #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: 'weekSchedules')]
        #[ORM\JoinColumn(nullable: false)]
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
    }
