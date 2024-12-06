<?php

    namespace App\Entity;

    use App\Core\Model\EquipmentModel;
    use App\Repository\EquipmentRepository;
    use DateTime;
    use Doctrine\ORM\Mapping as ORM;
    use OpenApi\Attributes as OA;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;
    use Vich\UploaderBundle\Mapping\Annotation as Vich;
    use Symfony\Component\HttpFoundation\File\File;
    use Symfony\Component\HttpFoundation\File\UploadedFile;

    /**
     * Represents a piece of equipment that can be associated with rooms or reservations.
     *
     * Groups:
     * - read: Global read group
     * - write: Global write group
     * - equipment:read: Equipment-specific read group
     * - equipment:write: Equipment-specific write group
     */
    #[OA\Schema(
        title: 'Equipment',
        description: 'Represents a piece of equipment that can be associated with rooms or reservations',
        type: 'object'
    )]
    #[ORM\Entity(repositoryClass: EquipmentRepository::class)]
    #[ORM\Table(name: 'equipments')]
    #[Vich\Uploadable]
    class Equipment implements EquipmentModel
    {
        /**
         * The unique identifier of the equipment.
         *
         * @var int|null
         */
        #[OA\Property(
            property: 'id',
            description: 'The unique identifier of the equipment',
            type: 'integer',
            example: 1
        )]
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        #[Groups(EquipmentModel::READ_GROUPS)]
        private ?int $id = null;

        /**
         * The name of the equipment.
         *
         * @var string
         */
        #[OA\Property(
            property: 'name',
            description: 'The name of the equipment',
            type: 'string',
            maxLength: 100,
            minLength: 2,
            example: 'Projector'
        )]
        #[ORM\Column(type: 'string', length: 100)]
        #[Groups(EquipmentModel::RW_GROUPS)]
        #[Assert\NotBlank(message: 'Equipment name is required')]
        #[Assert\Length(
            min: 2,
            max: 100,
            minMessage: 'Equipment name must be at least {{ limit }} characters long',
            maxMessage: 'Equipment name cannot be longer than {{ limit }} characters'
        )]
        private string $name;

        /**
         * A brief description of the equipment.
         *
         * @var string|null
         */
        #[OA\Property(
            property: 'description',
            description: 'A brief description of the equipment',
            type: 'string',
            maxLength: 1000,
            example: 'High-definition projector with HDMI and VGA inputs',
            nullable: true
        )]
        #[ORM\Column(type: 'text', nullable: true)]
        #[Groups(EquipmentModel::RW_GROUPS)]
        #[Assert\Length(
            max: 1000,
            maxMessage: 'Description cannot be longer than {{ limit }} characters'
        )]
        private ?string $description = null;

        /**
         * A path or URL to a photo representing the equipment.
         *
         * @var string|null
         */
        #[OA\Property(
            property: 'photo',
            description: 'A path or URL to a photo representing the equipment',
            type: 'string',
            example: '/uploads/equipment/projector.jpg',
            nullable: true
        )]
        #[ORM\Column(type: 'string', length: 255, nullable: true)]
        #[Groups(EquipmentModel::RW_GROUPS)]
        private ?string $photo = null;

        #[Vich\UploadableField(mapping: 'rooms', fileNameProperty: 'photo')]
        private ?File $photoFile = null;


        /**
         * The total stock available for the equipment.
         *
         * @var int
         */
        #[OA\Property(
            property: 'totalStock',
            description: 'The total stock available for the equipment',
            type: 'integer',
            minimum: 0,
            example: 5
        )]
        #[ORM\Column(type: 'integer')]
        private int $totalStock;

        /**
         * The timestamp when the equipment was created.
         *
         * @var \DateTime
         */
        #[OA\Property(
            property: 'createdAt',
            description: 'The timestamp when the equipment was created',
            type: 'string',
            format: 'date-time',
            example: '2024-01-01T12:00:00+00:00'
        )]
        #[ORM\Column(type: 'datetime')]
        #[Groups(EquipmentModel::READ_GROUPS)]
        private DateTime $createdAt;

        /**
         * The timestamp when the equipment was last updated.
         *
         * @var \DateTime|null
         */
        #[OA\Property(
            property: 'updatedAt',
            description: 'The timestamp when the equipment was last updated',
            type: 'string',
            format: 'date-time',
            example: '2024-01-02T15:30:00+00:00',
            nullable: true
        )]
        #[ORM\Column(type: 'datetime', nullable: true)]
        #[Groups(EquipmentModel::READ_GROUPS)]
        private ?DateTime $updatedAt;

        /**
         * Initializes the equipment with a creation timestamp.
         */
        public function __construct()
        {
            $this->createdAt = new DateTime();
        }

        /**
         * Get the unique identifier of the equipment.
         *
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get the name of the equipment.
         *
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }

        /**
         * Set the name of the equipment.
         *
         * @param string $name
         *
         * @return $this
         */
        public function setName(string $name): static
        {
            $this->name = $name;

            return $this;
        }

        /**
         * Get a brief description of the equipment.
         *
         * @return string|null
         */
        public function getDescription(): ?string
        {
            return $this->description;
        }

        /**
         * Set a brief description of the equipment.
         *
         * @param string|null $description
         *
         * @return $this
         */
        public function setDescription(?string $description): static
        {
            $this->description = $description;

            return $this;
        }

        /**
         * Get the path or URL to a photo representing the equipment.
         *
         * @return string|null
         */
        public function getPhoto(): ?string
        {
            return $this->photo;
        }

        /**
         * Set the path or URL to a photo representing the equipment.
         *
         * @param string|null $photo
         *
         * @return $this
         */
        public function setPhoto(?string $photo): static
        {
            $this->photo = $photo;

            return $this;
        }

        /**
         * Get the total stock available for the equipment.
         *
         * @return int
         */
        public function getTotalStock(): int
        {
            return $this->totalStock;
        }

        /**
         * Set the total stock available for the equipment.
         *
         * @param int $totalStock
         *
         * @return $this
         */
        public function setTotalStock(int $totalStock): static
        {
            $this->totalStock = $totalStock;

            return $this;
        }

        /**
         * Get the timestamp when the equipment was created.
         *
         * @return \DateTime
         */
        public function getCreatedAt(): DateTime
        {
            return $this->createdAt;
        }

        /**
         * Set the timestamp when the equipment was created.
         *
         * @param \DateTime $createdAt
         *
         * @return void
         */
        public function setCreatedAt(DateTime $createdAt): void
        {
            $this->createdAt = $createdAt;
        }

        /**
         * Get the timestamp when the equipment was last updated.
         *
         * @return \DateTime|null
         */
        public function getUpdatedAt(): ?DateTime
        {
            return $this->updatedAt;
        }

        /**
         * Set the timestamp when the equipment was last updated.
         *
         * @param \DateTime|null $updatedAt
         *
         * @return $this
         */
        public function setUpdatedAt(?DateTime $updatedAt): static
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }


        /**
         * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
         * of 'UploadedFile' is injected into this setter to trigger the update. If this
         * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
         * must be able to accept an instance of 'File' as the bundle will inject one here
         * during Doctrine hydration.
         *
         * @param File|UploadedFile|null $file
         */
        public function setPhotoFile(File|UploadedFile|null $file = null): void
        {
            $this->photoFile = $file;

            if (null !== $file) {
                // It is required that at least one field changes if you are using doctrine
                // otherwise the event listeners won't be called and the file is lost
                $this->updatedAt = new DateTime();
            }
        }

        public function getPhotoFile(): ?File
        {
            return $this->photoFile;
        }
    }
