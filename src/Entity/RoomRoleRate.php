<?php

    namespace App\Entity;

    use App\Core\Enum\UserRole;
    use App\Core\Model\RoomModel;
    use App\Core\Model\RoomRoleRateModel;
    use App\Repository\RoomRoleRateRepository;
    use Doctrine\ORM\Mapping as ORM;
    use OpenApi\Attributes as OA;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Represents the hourly rate for a room based on the user's role.
     *
     * This entity defines pricing rates for rooms based on user roles,
     * allowing different pricing strategies for different user categories.
     *
     * Groups:
     * - read: Global read access for basic rate information
     * - write: Global write access for creating/updating rates
     * - room_role_rate:read: Specific read access for room role rate details
     * - room_role_rate:write: Specific write access for room role rate management
     */
    #[OA\Schema(
        title: 'RoomRoleRate',
        description: 'Represents the hourly rate for a room based on the user\'s role',
        type: 'object'
    )]
    #[ORM\Entity(repositoryClass: RoomRoleRateRepository::class)]
    #[ORM\Table(name: 'room_role_rate')]
    class RoomRoleRate implements RoomRoleRateModel
    {
        public const READ_GROUPS = ['read', RoomRoleRateModel::GROUP_PREFIX . ':read'];
        public const WRITE_GROUPS = ['write', RoomRoleRateModel::GROUP_PREFIX . ':write'];

        /**
         * The unique identifier of the room-role rate.
         *
         * @var int|null
         */
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        #[Groups(self::READ_GROUPS)]
        private ?int $id = null;

        /**
         * The room associated with this rate.
         *
         * @var RoomModel
         */
        #[OA\Property(
            ref: new Model(type: Room::class),
            description: 'The room associated with this rate'
        )]
        #[ORM\ManyToOne(targetEntity: Room::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        #[Groups(self::WRITE_GROUPS)]
        #[Assert\NotNull(message: 'Room is required')]
        private RoomModel $room;

        /**
         * The user role for which this rate applies.
         *
         * @var UserRole
         */
        #[OA\Property(
            ref: new Model(type: UserRole::class),
            description: 'The user role for which this rate applies'
        )]
        #[ORM\Column(type: 'string', enumType: UserRole::class)]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\NotBlank(message: 'User role is required')]
        private UserRole $userRole;

        /**
         * The hourly rate for the room based on the user role.
         *
         * @var float
         */
        #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\NotNull(message: 'Hourly rate is required')]
        #[Assert\GreaterThanOrEqual(
            value: 0,
            message: 'Hourly rate must be greater than or equal to zero'
        )]
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
         * Get the ID of the room associated with this rate.
         *
         * @return int|null
         */
        #[Groups(self::READ_GROUPS)]
        public function getRoomId(): ?int
        {
            return $this->room->getId();
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
