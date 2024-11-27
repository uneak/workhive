<?php

    namespace App\Entity;

    use App\Enum\UserRole;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: 'equipment_role_rate')]
    class EquipmentRoleRate
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        #[ORM\ManyToOne(targetEntity: Equipment::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        private Equipment $equipment;

        #[ORM\Column(type: 'string', enumType: UserRole::class)]
        private UserRole $userRole;

        #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
        private float $hourlyRate;

        // Getters and Setters
        public function getId(): ?int
        {
            return $this->id;
        }

        public function getEquipment(): Equipment
        {
            return $this->equipment;
        }

        public function setEquipment(Equipment $equipment): self
        {
            $this->equipment = $equipment;

            return $this;
        }

        public function getUserRole(): UserRole
        {
            return $this->userRole;
        }

        public function setUserRole(UserRole $userRole): self
        {
            $this->userRole = $userRole;

            return $this;
        }

        public function getHourlyRate(): float
        {
            return $this->hourlyRate;
        }

        public function setHourlyRate(float $hourlyRate): self
        {
            $this->hourlyRate = $hourlyRate;

            return $this;
        }
    }
