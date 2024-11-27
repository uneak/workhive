<?php

    namespace App\Entity;

    use App\Enum\UserRole;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity]
    #[ORM\Table(name: 'room_role_rate')]
    class RoomRoleRate
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        #[ORM\ManyToOne(targetEntity: Room::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        private Room $room;

        #[ORM\Column(type: 'string', enumType: UserRole::class)]
        private UserRole $userRole;

        #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
        private float $hourlyRate;


        public function getId(): ?int
        {
            return $this->id;
        }

        public function getRoom(): Room
        {
            return $this->room;
        }

        public function setRoom(Room $room): self
        {
            $this->room = $room;

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
