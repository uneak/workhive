<?php

    namespace App\Entity;

    use App\Enum\ReservationStatus;
    use DateTime;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: 'reservations')]
    class Reservation
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        #[ORM\ManyToOne(targetEntity: Room::class)]
        #[ORM\JoinColumn(nullable: false)]
        private ?Room $room;

        #[ORM\ManyToOne(targetEntity: User::class)]
        #[ORM\JoinColumn(nullable: false)]
        private ?User $user;

        #[ORM\Column(type: 'datetime')]
        private DateTime $startAt;

        #[ORM\Column(type: 'datetime')]
        private DateTime $endAt;

        #[ORM\Column(enumType: ReservationStatus::class)]
        private ?ReservationStatus $status;

        #[ORM\Column(type: 'datetime')]
        private DateTime $createdAt;

        #[ORM\Column(type: 'datetime', nullable: true)]
        private ?DateTime $updatedAt;

        public function __construct()
        {
            $this->status = ReservationStatus::PENDING;
            $this->createdAt = new DateTime();
        }

        public function getId(): ?int
        {
            return $this->id;
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

        public function getUser(): ?User
        {
            return $this->user;
        }

        public function setUser(?User $user): static
        {
            $this->user = $user;

            return $this;
        }

        public function getStartAt(): DateTime
        {
            return $this->startAt;
        }

        public function setStartAt(DateTime $startAt): static
        {
            $this->startAt = $startAt;

            return $this;
        }

        public function getEndAt(): DateTime
        {
            return $this->endAt;
        }

        public function setEndAt(DateTime $endAt): static
        {
            $this->endAt = $endAt;

            return $this;
        }


        public function getStatus(): ?ReservationStatus
        {
            return $this->status;
        }

        public function setStatus(ReservationStatus $status): static
        {
            $this->status = $status;

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

        public function setUpdatedAt(?DateTime $updatedAt): static
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }
    }
