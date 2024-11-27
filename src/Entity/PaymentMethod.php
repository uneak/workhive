<?php

    namespace App\Entity;

    use DateTime;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: 'payment_methods')]
    class PaymentMethod
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        #[ORM\ManyToOne(targetEntity: User::class)]
        #[ORM\JoinColumn(nullable: false)]
        private User $user;

        #[ORM\Column(type: 'string', length: 100)]
        private string $label;

        #[ORM\Column(type: 'string', length: 50)]
        private string $type;

        #[ORM\Column(type: 'json')]
        private array $data;

        #[ORM\Column(type: 'datetime')]
        private DateTime $createdAt;

        #[ORM\Column(type: 'datetime', nullable: true)]
        private ?DateTime $updatedAt;

        public function __construct()
        {
            $this->createdAt = new DateTime();
        }

        public function getId(): ?int
        {
            return $this->id;
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

        public function getLabel(): string
        {
            return $this->label;
        }

        public function setLabel(string $label): static
        {
            $this->label = $label;

            return $this;
        }

        public function getType(): string
        {
            return $this->type;
        }

        public function setType(string $type): static
        {
            $this->type = $type;

            return $this;
        }

        public function getData(): array
        {
            return $this->data;
        }

        public function setData(array $data): static
        {
            $this->data = $data;

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
