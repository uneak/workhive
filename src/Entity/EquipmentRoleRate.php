<?php

    namespace App\Entity;

    use App\Core\Enum\UserRole;
    use App\Core\Model\EquipmentModel;
    use App\Core\Model\EquipmentRoleRateModel;
    use App\Repository\EquipmentRoleRateRepository;
    use Doctrine\ORM\Mapping as ORM;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use OpenApi\Attributes as OA;


    /**
     * Represents the hourly rate for using a specific equipment, based on the user's role.
     */
    #[ORM\Entity(repositoryClass: EquipmentRoleRateRepository::class)]
    #[ORM\Table(name: 'equipment_role_rate')]
    class EquipmentRoleRate implements EquipmentRoleRateModel
    {
        /**
         * The unique identifier of the equipment role rate.
         *
         * @var int|null
         */
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        /**
         * The equipment associated with this rate.
         *
         * @var Equipment
         */
        #[ORM\ManyToOne(targetEntity: Equipment::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
        private Equipment $equipment;

        /**
         * The role of the user for which this rate applies.
         *
         * @var UserRole
         */
        #[ORM\Column(type: 'string', enumType: UserRole::class)]
        #[OA\Property(ref: new Model(type: UserRole::class))]
        private UserRole $userRole;

        /**
         * The hourly rate for using the equipment.
         *
         * @var float
         */
        #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
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
         * @return Equipment
         */
        public function getEquipment(): Equipment
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
