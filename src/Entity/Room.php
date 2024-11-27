<?php

    namespace App\Entity;

    use App\Enum\Status;
    use DateTime;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: 'rooms')]
    class Room
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        #[ORM\Column(type: 'string', length: 100)]
        private string $name;

        #[ORM\Column(type: 'integer')]
        private int $capacity;

        #[ORM\Column(type: 'float')]
        private float $width;

        #[ORM\Column(type: 'float')]
        private float $length;

        #[ORM\Column(enumType: Status::class)]
        private ?Status $status = null;

        #[ORM\Column(type: 'text', nullable: true)]
        private ?string $description;

        #[ORM\Column(type: 'string', length: 255, nullable: true)]
        private ?string $photo;

        #[ORM\Column(type: 'datetime')]
        private DateTime $createdAt;

        #[ORM\Column(type: 'datetime', nullable: true)]
        private ?DateTime $updatedAt;


        #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'room', cascade: ['persist', 'remove'])]
        private Collection $reservations;

        /**
         * @var Collection<int, WeekSchedules>
         */
        #[ORM\OneToMany(targetEntity: WeekSchedules::class, mappedBy: 'room', orphanRemoval: true)]
        private Collection $weekSchedules;

        /**
         * @var Collection<int, DateSchedules>
         */
        #[ORM\OneToMany(targetEntity: DateSchedules::class, mappedBy: 'room', orphanRemoval: true)]
        private Collection $dateSchedules;

        public function __construct()
        {
            $this->status = Status::ACTIVE;
            $this->createdAt = new DateTime();
            $this->reservations = new ArrayCollection();
            $this->weekSchedules = new ArrayCollection();
            $this->dateSchedules = new ArrayCollection();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getName(): string
        {
            return $this->name;
        }

        public function setName(string $name): self
        {
            $this->name = $name;
            return $this;
        }

        public function getCapacity(): int
        {
            return $this->capacity;
        }

        public function setCapacity(int $capacity): self
        {
            $this->capacity = $capacity;
            return $this;
        }

        public function getWidth(): float
        {
            return $this->width;
        }

        public function setWidth(float $width): self
        {
            $this->width = $width;
            return $this;
        }

        public function getLength(): float
        {
            return $this->length;
        }

        public function setLength(float $length): self
        {
            $this->length = $length;
            return $this;
        }

        public function getArea(): float
        {
            return $this->width * $this->length;
        }

        public function getDescription(): ?string
        {
            return $this->description;
        }

        public function setDescription(?string $description): self
        {
            $this->description = $description;
            return $this;
        }

        public function getPhoto(): ?string
        {
            return $this->photo;
        }

        public function setPhoto(?string $photo): self
        {
            $this->photo = $photo;
            return $this;
        }


        public function getStatus(): ?Status
        {
            return $this->status;
        }

        public function setStatus(Status $status): static
        {
            $this->status = $status;

            return $this;
        }

        public function isActive(): bool
        {
            return $this->status === Status::ACTIVE;
        }

        public function activate(): self
        {
            $this->status = Status::ACTIVE;
            return $this;
        }

        public function inactivate(): self
        {
            $this->status = Status::INACTIVE;
            return $this;
        }

        public function getCreatedAt(): DateTime
        {
            return $this->createdAt;
        }

        public function getUpdatedAt(): ?DateTime
        {
            return $this->updatedAt;
        }

        public function setUpdatedAt(?DateTime $updatedAt): self
        {
            $this->updatedAt = $updatedAt;
            return $this;
        }

        public function getReservations(): Collection
        {
            return $this->reservations;
        }

        public function addReservation(Reservation $reservation): self
        {
            if (!$this->reservations->contains($reservation)) {
                $this->reservations->add($reservation);
                $reservation->setRoom($this);
            }

            return $this;
        }

        public function removeReservation(Reservation $reservation): self
        {
            if ($this->reservations->removeElement($reservation)) {
                // Set the owning side to null (unless already changed)
                if ($reservation->getRoom() === $this) {
                    $reservation->setRoom(null);
                }
            }

            return $this;
        }


        /**
         * @return Collection<int, WeekSchedules>
         */
        public function getWeekSchedules(): Collection
        {
            return $this->weekSchedules;
        }

        public function addWeekSchedule(WeekSchedules $weekSchedule): static
        {
            if (!$this->weekSchedules->contains($weekSchedule)) {
                $this->weekSchedules->add($weekSchedule);
                $weekSchedule->setRoom($this);
            }

            return $this;
        }

        public function removeWeekSchedule(WeekSchedules $weekSchedule): static
        {
            if ($this->weekSchedules->removeElement($weekSchedule)) {
                // set the owning side to null (unless already changed)
                if ($weekSchedule->getRoom() === $this) {
                    $weekSchedule->setRoom(null);
                }
            }

            return $this;
        }

        /**
         * @return Collection<int, DateSchedules>
         */
        public function getDateSchedules(): Collection
        {
            return $this->dateSchedules;
        }

        public function addDateSchedule(DateSchedules $dateSchedule): static
        {
            if (!$this->dateSchedules->contains($dateSchedule)) {
                $this->dateSchedules->add($dateSchedule);
                $dateSchedule->setRoom($this);
            }

            return $this;
        }

        public function removeDateSchedule(DateSchedules $dateSchedule): static
        {
            if ($this->dateSchedules->removeElement($dateSchedule)) {
                // set the owning side to null (unless already changed)
                if ($dateSchedule->getRoom() === $this) {
                    $dateSchedule->setRoom(null);
                }
            }

            return $this;
        }
    }
