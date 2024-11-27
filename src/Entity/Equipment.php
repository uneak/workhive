<?php

    namespace App\Entity;

    use App\Repository\EquipmentRepository;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: EquipmentRepository::class)]
    #[ORM\Table(name: 'equipments')]
    class Equipment
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        #[ORM\Column(type: 'string', length: 100)]
        private string $name;

        #[ORM\Column(type: 'text', nullable: true)]
        private ?string $description = null;

        #[ORM\Column(type: 'string', length: 255, nullable: true)]
        private ?string $photo = null;

        #[ORM\Column(type: 'integer')]
        private int $totalStock;

        #[ORM\Column(type: 'datetime')]
        private \DateTime $createdAt;

        #[ORM\Column(type: 'datetime', nullable: true)]
        private ?\DateTime $updatedAt;

        public function __construct()
        {
            $this->createdAt = new \DateTime();
        }

        // Getters and Setters
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

        public function getTotalStock(): int
        {
            return $this->totalStock;
        }

        public function setTotalStock(int $totalStock): self
        {
            $this->totalStock = $totalStock;

            return $this;
        }

        public function getCreatedAt(): \DateTime
        {
            return $this->createdAt;
        }

        public function getUpdatedAt(): ?\DateTime
        {
            return $this->updatedAt;
        }

        public function setUpdatedAt(?\DateTime $updatedAt): self
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }
    }
