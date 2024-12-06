<?php

    namespace App\Entity;

    use App\Core\Enum\Status;
    use App\Core\Enum\UserRole;
    use App\Core\Model\UserModel;
    use App\Repository\UserRepository;
    use DateTime;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Symfony\Component\HttpFoundation\File\File;
    use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use OpenApi\Attributes as OA;
    use Vich\UploaderBundle\Mapping\Annotation as Vich;
    use Symfony\Component\HttpFoundation\File\UploadedFile;

    /**
     * Represents a user in the system with authentication and authorization capabilities.
     */
    #[OA\Schema(
        title: 'User',
        description: 'Represents a user in the system with their personal information, authentication details, and role-based access control.',
        type: 'object'
    )]
    #[Vich\Uploadable]
    #[ORM\Entity(repositoryClass: UserRepository::class)]
    #[ORM\Table(name: 'users')]
    #[UniqueEntity(fields: ['email'], message: 'An account already exists with this email')]
    class User implements UserModel, UserInterface, PasswordAuthenticatedUserInterface
    {
        /**
         * The unique identifier of the user.
         *
         * @var int|null
         */
        #[OA\Property(
            description: 'The unique identifier of the user',
            type: 'integer',
            format: 'int64',
            example: 1
        )]
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        #[Groups(UserModel::READ_GROUPS)]
        private ?int $id = null;

        /**
         * The first name of the user.
         *
         * @var string
         */
        #[OA\Property(
            description: 'The first name of the user',
            type: 'string',
            maxLength: 50,
            minLength: 2,
            example: 'John'
        )]
        #[ORM\Column(type: 'string', length: 50)]
        #[Groups(UserModel::RW_GROUPS)]
        #[Assert\NotBlank(message: 'First name is required')]
        #[Assert\Length(
            min: 2,
            max: 50,
            minMessage: 'First name must be at least {{ limit }} characters long',
            maxMessage: 'First name cannot be longer than {{ limit }} characters'
        )]
        private string $firstName;

        /**
         * The last name of the user.
         *
         * @var string
         */
        #[OA\Property(
            description: 'The last name of the user',
            type: 'string',
            maxLength: 50,
            minLength: 2,
            example: 'Doe'
        )]
        #[ORM\Column(type: 'string', length: 50)]
        #[Groups(UserModel::RW_GROUPS)]
        #[Assert\NotBlank(message: 'Last name is required')]
        #[Assert\Length(
            min: 2,
            max: 50,
            minMessage: 'Last name must be at least {{ limit }} characters long',
            maxMessage: 'Last name cannot be longer than {{ limit }} characters'
        )]
        private string $lastName;

        /**
         * The photo URL of the user (optional).
         */
        #[OA\Property(
            description: 'The photo URL of the user',
            type: 'string',
            maxLength: 255,
            example: 'https://example.com/photo.jpg',
            nullable: true
        )]
        #[ORM\Column(type: 'string', length: 255, nullable: true)]
        #[Groups(UserModel::RW_GROUPS)]
        private ?string $photo = null;

        #[Vich\UploadableField(mapping: 'users', fileNameProperty: 'photo')]
        private ?File $photoFile = null;

        /**
         * The role of the user in the application.
         * This determines the user's permissions and access levels.
         */
        #[OA\Property(
            ref: new Model(type: UserRole::class),
            description: 'The role of the user'
        )]
        #[ORM\Column(enumType: UserRole::class)]
        #[Groups(UserModel::READ_GROUPS)]
        #[Assert\NotNull(message: 'User role is required')]
        private ?UserRole $userRole;

        /**
         * The phone number of the user (optional).
         * Must be a valid phone number format.
         */
        #[OA\Property(
            description: 'The phone number of the user',
            type: 'string',
            maxLength: 15,
            example: '+1234567890',
            nullable: true
        )]
        #[ORM\Column(type: 'string', length: 15, nullable: true)]
        #[Groups(UserModel::RW_GROUPS)]
        #[Assert\Regex(
            pattern: '/^[0-9\+\-\(\)\/\s]*$/',
            message: 'Please enter a valid phone number'
        )]
        #[Assert\Length(
            max: 15,
            maxMessage: 'Phone number cannot be longer than {{ limit }} characters'
        )]
        private ?string $phone;

        /**
         * The email address of the user.
         *
         * @var string
         */
        #[OA\Property(
            description: 'The email address of the user',
            type: 'string',
            format: 'email',
            example: 'john.doe@example.com'
        )]
        #[ORM\Column(type: 'string', length: 100, unique: true)]
        #[Groups(UserModel::RW_GROUPS)]
        #[Assert\NotBlank(message: 'Email is required')]
        #[Assert\Email(message: 'The email {{ value }} is not a valid email address')]
        #[Assert\Length(
            max: 100,
            maxMessage: 'Email cannot be longer than {{ limit }} characters'
        )]
        private string $email;

        /**
         * The hashed password of the user.
         *
         * @var ?string
         */
        #[OA\Property(
            description: 'The password of the user (only required during creation/update)',
            type: 'string',
            format: 'password',
            minLength: 6,
            example: 'StrongP@ssw0rd'
        )]
        #[Groups([...UserModel::WRITE_GROUPS])]
        #[Assert\NotBlank(message: 'Password is required', groups: UserModel::CREATE_GROUPS)]
        #[Assert\Length(
            min: 6,
            minMessage: 'Password must be at least {{ limit }} characters long'
        )]
        private ?string $plainPassword = null;

        #[ORM\Column(type: 'string')]
        private ?string $password;

        /**
         * The status of the user (active or inactive).
         *
         * @var Status|null
         */
        #[OA\Property(
            ref: new Model(type: Status::class),
            description: 'The status of the user'
        )]
        #[ORM\Column(enumType: Status::class)]
        #[Groups(UserModel::READ_GROUPS)]
        #[Assert\NotNull(message: 'Status is required')]
        private ?Status $status = null;

        /**
         * The timestamp when the user was created.
         *
         * @var DateTime
         */
        #[OA\Property(
            description: 'The timestamp when the user was created',
            type: 'string',
            format: 'date-time',
            example: '2024-01-01T12:00:00+00:00'
        )]
        #[ORM\Column(type: 'datetime')]
        #[Groups(UserModel::READ_GROUPS)]
        private DateTime $createdAt;

        /**
         * The timestamp when the user was last updated.
         *
         * @var DateTime|null
         */
        #[OA\Property(
            description: 'The timestamp when the user was last updated',
            type: 'string',
            format: 'date-time',
            example: '2024-01-02T15:30:00+00:00',
            nullable: true
        )]
        #[ORM\Column(type: 'datetime', nullable: true)]
        #[Groups(UserModel::READ_GROUPS)]
        private ?DateTime $updatedAt;

        /**
         * The collection of payment methods associated with the user.
         *
         * @var Collection<int, PaymentMethod>
         */
        #[OA\Property(
            description: 'The payment methods associated with the user',
            type: 'array',
            items: new OA\Items(ref: new Model(type: PaymentMethod::class))
        )]
        #[ORM\OneToMany(targetEntity: PaymentMethod::class, mappedBy: 'user')]
        private Collection $paymentMethods;

        /**
         * The collection of reservations made by the user.
         *
         * @var Collection<int, Reservation>
         */
        #[OA\Property(
            description: 'The reservations made by the user',
            type: 'array',
            items: new OA\Items(ref: new Model(type: Reservation::class))
        )]
        #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
        private Collection $reservations;

        #[ORM\Column]
        private bool $isVerified = false;

        /**
         * Initializes the user with default values and empty collections.
         */
        public function __construct()
        {
            $this->userRole = UserRole::ROLE_USER;
            $this->status = Status::ACTIVE;
            $this->createdAt = new DateTime();
            $this->paymentMethods = new ArrayCollection();
            $this->reservations = new ArrayCollection();
        }

        /**
         * Get the unique identifier of the user.
         *
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get the first name of the user.
         *
         * @return string
         */
        public function getFirstName(): string
        {
            return $this->firstName;
        }

        /**
         * Set the first name of the user.
         *
         * @param string $firstName
         *
         * @return $this
         */
        public function setFirstName(string $firstName): static
        {
            $this->firstName = $firstName;

            return $this;
        }

        /**
         * Get the last name of the user.
         *
         * @return string
         */
        public function getLastName(): string
        {
            return $this->lastName;
        }

        /**
         * Set the last name of the user.
         *
         * @param string $lastName
         *
         * @return $this
         */
        public function setLastName(string $lastName): static
        {
            $this->lastName = $lastName;

            return $this;
        }

        /**
         * Get the photo of the user.
         *
         * @return string|null
         */
        public function getPhoto(): ?string
        {
            return $this->photo;
        }

        /**
         * Set the photo of the user.
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
         * Get the role of the user.
         *
         * @return UserRole|null
         */
        public function getUserRole(): ?UserRole
        {
            return $this->userRole;
        }

        /**
         * Set the role of the user.
         *
         * @param UserRole $role
         *
         * @return $this
         */
        public function setUserRole(UserRole $role): static
        {
            $this->userRole = $role;

            return $this;
        }

        /**
         * Get the phone number of the user.
         *
         * @return string|null
         */
        public function getPhone(): ?string
        {
            return $this->phone;
        }

        /**
         * Set the phone number of the user.
         *
         * @param string|null $phone
         *
         * @return $this
         */
        public function setPhone(?string $phone): static
        {
            $this->phone = $phone;

            return $this;
        }

        /**
         * Get the email address of the user.
         *
         * @return string
         */
        public function getEmail(): string
        {
            return $this->email;
        }

        /**
         * Set the email address of the user.
         *
         * @param string $email
         *
         * @return $this
         */
        public function setEmail(string $email): static
        {
            $this->email = $email;

            return $this;
        }

        /**
         * Get the hashed password of the user.
         *
         * @return ?string
         */
        public function getPassword(): ?string
        {
            return $this->password;
        }

        /**
         * Set the hashed password of the user.
         *
         * @param ?string $password
         *
         * @return $this
         */
        public function setPassword(?string $password): static
        {
            $this->password = $password;

            return $this;
        }

        /**
         * Get the status of the user.
         *
         * @return Status|null
         */
        public function getStatus(): ?Status
        {
            return $this->status;
        }

        /**
         * Set the status of the user.
         *
         * @param Status $status
         *
         * @return $this
         */
        public function setStatus(Status $status): static
        {
            $this->status = $status;

            return $this;
        }

        /**
         * Get the payment methods associated with the user.
         *
         * @return Collection<int, PaymentMethod>
         */
        public function getPaymentMethods(): Collection
        {
            return $this->paymentMethods;
        }

        /**
         * Add a payment method to the user.
         *
         * @param PaymentMethod $paymentMethod
         *
         * @return $this
         */
        public function addPaymentMethod(PaymentMethod $paymentMethod): static
        {
            if (!$this->paymentMethods->contains($paymentMethod)) {
                $this->paymentMethods[] = $paymentMethod;
                $paymentMethod->setUser($this);
            }

            return $this;
        }

        /**
         * Remove a payment method from the user.
         *
         * @param PaymentMethod $paymentMethod
         *
         * @return $this
         */
        public function removePaymentMethod(PaymentMethod $paymentMethod): static
        {
            if ($this->paymentMethods->removeElement($paymentMethod)) {
                if ($paymentMethod->getUser() === $this) {
                    $paymentMethod->setUser(null);
                }
            }

            return $this;
        }

        /**
         * Get the reservations made by the user.
         *
         * @return Collection<int, Reservation>
         */
        public function getReservations(): Collection
        {
            return $this->reservations;
        }

        /**
         * Add a reservation made by the user.
         *
         * @param Reservation $reservation
         *
         * @return $this
         */
        public function addReservation(Reservation $reservation): static
        {
            if (!$this->reservations->contains($reservation)) {
                $this->reservations->add($reservation);
                $reservation->setUser($this);
            }

            return $this;
        }

        /**
         * Remove a reservation made by the user.
         *
         * @param Reservation $reservation
         *
         * @return $this
         */
        public function removeReservation(Reservation $reservation): static
        {
            if ($this->reservations->removeElement($reservation)) {
                if ($reservation->getUser() === $this) {
                    $reservation->setUser(null);
                }
            }

            return $this;
        }

        /**
         * Get the timestamp when this record was created.
         *
         * @return \DateTime
         */
        public function getCreatedAt(): \DateTime
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
        public function setCreatedAt(\DateTime $createdAt): void
        {
            $this->createdAt = $createdAt;
        }

        /**
         * Get the timestamp when this record was last updated.
         *
         * @return \DateTime|null
         */
        public function getUpdatedAt(): ?\DateTime
        {
            return $this->updatedAt;
        }

        /**
         * Set the timestamp when this record was last updated.
         *
         * @param \DateTime|null $updatedAt
         *
         * @return $this
         */
        public function setUpdatedAt(?\DateTime $updatedAt): static
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }

        public function getRoles(): array
        {
            return [$this->getUserRole()->name];
        }

        public function eraseCredentials(): void
        {
            $this->setPlainPassword(null);
        }

        public function getUserIdentifier(): string
        {
            return $this->email;
        }

        public function isVerified(): bool
        {
            return $this->isVerified;
        }

        public function setVerified(bool $isVerified): static
        {
            $this->isVerified = $isVerified;

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

        public function getPlainPassword(): ?string
        {
            return $this->plainPassword;
        }

        public function setPlainPassword(?string $plainPassword): void
        {
            $this->plainPassword = $plainPassword;
        }
    }
