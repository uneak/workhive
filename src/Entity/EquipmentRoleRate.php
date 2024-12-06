<?php

    namespace App\Entity;

    use App\Core\Enum\UserRole;
    use App\Core\Model\EquipmentModel;
    use App\Core\Model\EquipmentRoleRateModel;
    use App\Repository\EquipmentRoleRateRepository;
    use Doctrine\ORM\Mapping as ORM;
    use OpenApi\Attributes as OA;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Represents the hourly rate for using a specific equipment, based on the user's role.
     *
     * Groups:
     * - read: Global read group
     * - write: Global write group
     * - equipment-rate:read: Equipment rate-specific read group
     * - equipment-rate:write: Equipment rate-specific write group
     */
    #[OA\Schema(
        title: 'EquipmentRoleRate',
        description: 'Represents the hourly rate for using a specific equipment, based on the user\'s role',
        type: 'object'
    )]
    #[ORM\Entity(repositoryClass: EquipmentRoleRateRepository::class)]
    #[ORM\Table(name: 'equipment_role_rate')]
    class EquipmentRoleRate implements EquipmentRoleRateModel
    {
        /**
         * The unique identifier of the equipment role rate.
         *
         * @var int|null
         */
        #[OA\Property(
            property: 'id',
            description: 'The unique identifier of the equipment role rate',
            type: 'integer',
            example: 1
        )]
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        #[Groups(EquipmentRoleRateModel::READ_GROUPS)]
        private ?int $id = null;

        /**
         * The equipment associated with this rate.
         *
         * @var EquipmentModel
         */
        #[OA\Property(
            ref: new Model(type: Equipment::class),
            description: 'The equipment associated with this rate'
        )]
        #[ORM\ManyToOne(targetEntity: Equipment::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        #[Groups(EquipmentRoleRateModel::WRITE_GROUPS)]
        #[Assert\NotNull(message: 'Equipment is required')]
        private EquipmentModel $equipment;

        /**
         * The role of the user for which this rate applies.
         *
         * @var UserRole
         */
        #[OA\Property(
            property: 'userRole',
            ref: new Model(type: UserRole::class),
            description: 'The role of the user for which this rate applies'
        )]
        #[ORM\Column(type: 'string', enumType: UserRole::class)]
        #[Groups(EquipmentRoleRateModel::RW_GROUPS)]
        #[Assert\NotBlank(message: 'User role is required')]
        private UserRole $userRole;

        /**
         * The hourly rate for using the equipment.
         *
         * @var float
         */
        #[OA\Property(
            property: 'hourlyRate',
            description: 'The hourly rate for using the equipment',
            type: 'number',
            format: 'float',
            minimum: 0,
            example: 25.50
        )]
        #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
        #[Groups(EquipmentRoleRateModel::RW_GROUPS)]
        #[Assert\NotNull(message: 'Hourly rate is required')]
        #[Assert\GreaterThanOrEqual(
            value: 0,
            message: 'Hourly rate must be greater than or equal to zero'
        )]
        private float $hourlyRate;

        /**
         * Get the unique identifier of the equipment role rate.
         *
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get the equipment associated with this rate.
         *
         * @return EquipmentModel
         */
        public function getEquipment(): EquipmentModel
        {
            return $this->equipment;
        }

        /**
         * Set the equipment associated with this rate.
         *
         * @param EquipmentModel $equipment
         *
         * @return $this
         */
        public function setEquipment(EquipmentModel $equipment): static
        {
            $this->equipment = $equipment;

            return $this;
        }

        /**
         * Get the ID of the equipment associated with this rate.
         *
         * @return int|null
         */
        #[OA\Property(
            property: 'equipmentId',
            description: 'The ID of the equipment associated with this rate',
            type: 'integer',
            example: 1
        )]
        #[Groups(EquipmentRoleRateModel::READ_GROUPS)]
        public function getEquipmentId(): ?int
        {
            return $this->equipment?->getId();
        }

        /**
         * Get the role of the user for which this rate applies.
         *
         * @return UserRole
         */
        public function getUserRole(): UserRole
        {
            return $this->userRole;
        }

        /**
         * Set the role of the user for which this rate applies.
         *
         * @param UserRole $userRole
         *
         * @return $this
         */
        public function setUserRole(UserRole $userRole): static
        {
            $this->userRole = $userRole;

            return $this;
        }

        /**
         * Get the hourly rate for using the equipment.
         *
         * @return float
         */
        public function getHourlyRate(): float
        {
            return $this->hourlyRate;
        }

        /**
         * Set the hourly rate for using the equipment.
         *
         * @param float $hourlyRate
         *
         * @return $this
         */
        public function setHourlyRate(float $hourlyRate): static
        {
            $this->hourlyRate = $hourlyRate;

            return $this;
        }
    }
