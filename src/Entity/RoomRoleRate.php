<?php

    namespace App\Entity;

    use App\Core\Enum\UserRole;
    use App\Core\Model\RoomModel;
    use App\Core\Model\RoomRoleRateModel;
    use App\Repository\RoomRoleRateRepository;
    use Doctrine\ORM\Mapping as ORM;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use OpenApi\Attributes as OA;


    /**
     * Represents the hourly rate for a room based on the user's role.
     */
    #[ORM\Entity(repositoryClass: RoomRoleRateRepository::class)]
    #[ORM\Table(name: 'room_role_rate')]
    class RoomRoleRate implements RoomRoleRateModel
    {
        /**
         * The unique identifier of the room-role rate.
         *
         * @var int|null
         */
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        /**
         * The room associated with this rate.
         *
         * @var RoomModel
         */
        #[ORM\ManyToOne(targetEntity: Room::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        private RoomModel $room;

        /**
         * The user role for which this rate applies.
         *
         * @var UserRole
         */
        #[ORM\Column(type: 'string', enumType: UserRole::class)]
        #[OA\Property(ref: new Model(type: UserRole::class))]
        private UserRole $userRole;

        /**
         * The hourly rate for the room based on the user role.
         *
         * @var float
         */
        #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
        private float $hourlyRate;

        /**
         * Get the unique identifier of the room-role rate.
         *
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get the room associated with this rate.
         *
         * @return RoomModel
         */
        public function getRoom(): RoomModel
        {
            return $this->room;
        }

        /**
         * Set the room associated with this rate.
         *
         * @param RoomModel $room The room to associate with this rate.
         *
         * @return $this
         */
        public function setRoom(RoomModel $room): static
        {
            $this->room = $room;

            return $this;
        }

        /**
         * Get the user role for which this rate applies.
         *
         * @return UserRole
         */
        public function getUserRole(): UserRole
        {
            return $this->userRole;
        }

        /**
         * Set the user role for which this rate applies.
         *
         * @param UserRole $userRole The user role to set.
         *
         * @return $this
         */
        public function setUserRole(UserRole $userRole): static
        {
            $this->userRole = $userRole;

            return $this;
        }

        /**
         * Get the hourly rate for the room based on the user role.
         *
         * @return float
         */
        public function getHourlyRate(): float
        {
            return $this->hourlyRate;
        }

        /**
         * Set the hourly rate for the room based on the user role.
         *
         * @param float $hourlyRate The hourly rate to set.
         *
         * @return $this
         */
        public function setHourlyRate(float $hourlyRate): static
        {
            $this->hourlyRate = $hourlyRate;

            return $this;
        }
    }
