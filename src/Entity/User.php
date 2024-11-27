<?php

    namespace App\Entity;

    use App\Enum\Status;
    use App\Enum\UserRole;
    use DateTime;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: 'users')]
    class User
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        #[ORM\Column(type: 'string', length: 50)]
        private string $firstName;

        #[ORM\Column(type: 'string', length: 50)]
        private string $lastName;

        #[ORM\Column(type: 'string', length: 255, nullable: true)]
        private ?string $photo;

        #[ORM\Column(enumType: UserRole::class)]
        private ?UserRole $userRole;

        #[ORM\Column(type: 'string', length: 15, nullable: true)]
        private ?string $phone;

        #[ORM\Column(type: 'string', length: 100, unique: true)]
        private string $email;

        #[ORM\Column(type: 'string')]
        private string $password;

        #[ORM\Column(enumType: Status::class)]
        private ?Status $status = null;

        #[ORM\Column(type: 'datetime')]
        private DateTime $createdAt;

        #[ORM\Column(type: 'datetime', nullable: true)]
        private ?DateTime $updatedAt;

        #[ORM\OneToMany(targetEntity: PaymentMethod::class, mappedBy: 'user')]
        private Collection $paymentMethods;

        #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
        private Collection $reservations;

        public function __construct()
        {
            $this->userRole = UserRole::USER;
            $this->status = Status::ACTIVE;
            $this->createdAt = new DateTime();
            $this->paymentMethods = new ArrayCollection();
            $this->reservations = new ArrayCollection();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getFirstName(): string
        {
            return $this->firstName;
        }

        public function setFirstName(string $firstName): static
        {
            $this->firstName = $firstName;

            return $this;
        }

        public function getLastName(): string
        {
            return $this->lastName;
        }

        public function setLastName(string $lastName): static
        {
            $this->lastName = $lastName;

            return $this;
        }

        public function getPhoto(): ?string
        {
            return $this->photo;
        }

        public function setPhoto(?string $photo): static
        {
            $this->photo = $photo;

            return $this;
        }

        public function getUserRole(): ?UserRole
        {
            return $this->userRole;
        }

        public function setUserRole(UserRole $role): static
        {
            $this->userRole = $role;

            return $this;
        }

        public function getPhone(): ?string
        {
            return $this->phone;
        }

        public function setPhone(?string $phone): static
        {
            $this->phone = $phone;

            return $this;
        }

        public function getEmail(): string
        {
            return $this->email;
        }

        public function setEmail(string $email): static
        {
            $this->email = $email;

            return $this;
        }

        public function getPassword(): string
        {
            return $this->password;
        }

        public function setPassword(string $password): static
        {
            $this->password = $password;

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

        public function getPaymentMethods(): Collection
        {
            return $this->paymentMethods;
        }

        public function addPaymentMethod(PaymentMethod $paymentMethod): self
        {
            if (!$this->paymentMethods->contains($paymentMethod)) {
                $this->paymentMethods[] = $paymentMethod;
                $paymentMethod->setUser($this);
            }

            return $this;
        }

        public function removePaymentMethod(PaymentMethod $paymentMethod): static
        {
            if ($this->paymentMethods->removeElement($paymentMethod)) {
                // Set the owning side to null (unless already changed)
                if ($paymentMethod->getUser() === $this) {
                    $paymentMethod->setUser(null);
                }
            }

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
                $reservation->setUser($this);
            }

            return $this;
        }

        public function removeReservation(Reservation $reservation): self
        {
            if ($this->reservations->removeElement($reservation)) {
                // Set the owning side to null (unless already changed)
                if ($reservation->getUser() === $this) {
                    $reservation->setUser(null);
                }
            }

            return $this;
        }
    }
