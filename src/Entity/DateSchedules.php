<?php

    namespace App\Entity;

    use App\Repository\DateSchedulesRepository;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: DateSchedulesRepository::class)]
    class DateSchedules
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $name = null;

        #[ORM\Column(type: Types::DATE_MUTABLE)]
        private ?\DateTimeInterface $date = null;

        #[ORM\Column(type: Types::TIME_MUTABLE)]
        private ?\DateTimeInterface $startedAt = null;

        #[ORM\Column(type: Types::TIME_MUTABLE)]
        private ?\DateTimeInterface $endedAt = null;

        #[ORM\Column]
        private ?bool $isOpen = null;

        #[ORM\ManyToOne(inversedBy: 'dateSchedules')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Room $room = null;

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getName(): ?string
        {
            return $this->name;
        }

        public function setName(?string $name): static
        {
            $this->name = $name;

            return $this;
        }

        public function getDate(): ?\DateTimeInterface
        {
            return $this->date;
        }

        public function setDate(\DateTimeInterface $date): static
        {
            $this->date = $date;

            return $this;
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

        public function isOpen(): ?bool
        {
            return $this->isOpen;
        }

        public function setIsOpen(bool $isOpen): static
        {
            $this->isOpen = $isOpen;

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
