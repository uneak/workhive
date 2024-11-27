<?php

    namespace App\Entity;

    use App\Repository\WeekSchedulesRepository;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: WeekSchedulesRepository::class)]
    class WeekSchedules
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(type: Types::TIME_MUTABLE)]
        private ?\DateTimeInterface $startedAt = null;

        #[ORM\Column(type: Types::TIME_MUTABLE)]
        private ?\DateTimeInterface $endedAt = null;

        #[ORM\Column(type: Types::SMALLINT)]
        private ?int $weekDay = null;

        #[ORM\ManyToOne(inversedBy: 'weekSchedules')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Room $room = null;

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getStartedAt(): ?\DateTimeInterface
        {
            return $this->startedAt;
        }

        public function setStartedAt(\DateTimeInterface $startedAt): static
        {
            $this->startedAt = $startedAt;

            return $this;
        }

        public function getEndedAt(): ?\DateTimeInterface
        {
            return $this->endedAt;
        }

        public function setEndedAt(\DateTimeInterface $endedAt): static
        {
            $this->endedAt = $endedAt;

            return $this;
        }

        public function getWeekDay(): ?int
        {
            return $this->weekDay;
        }

        public function setWeekDay(?int $weekDay): static
        {
            $this->weekDay = $weekDay;

            return $this;
        }

        public function getRoom(): ?Room
        {
            return $this->room;
        }

        public function setRoom(?Room $room): static
        {
            $this->room = $room;

            return $this;
        }
    }
