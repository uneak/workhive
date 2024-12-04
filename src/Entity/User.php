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
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User Entity
 * 
 * This entity represents a user in the application with their personal information,
 * authentication details, and role-based access control.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['email'], message: 'An account already exists with this email')]
class User implements UserModel, UserInterface, PasswordAuthenticatedUserInterface
{
    public const READ_GROUPS = ['read', UserModel::GROUP_PREFIX . ':read'];
    public const WRITE_GROUPS = ['write', UserModel::GROUP_PREFIX . ':write'];

    /**
     * The unique identifier of the user.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(self::READ_GROUPS)]
    private ?int $id = null;

    /**
     * The first name of the user.
     * Must be between 2 and 50 characters.
     */
    #[ORM\Column(type: 'string', length: 50)]
    #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
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
     * Must be between 2 and 50 characters.
     */
    #[ORM\Column(type: 'string', length: 50)]
    #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
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
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
    private ?string $photo;

    /**
     * The role of the user in the application.
     * This determines the user's permissions and access levels.
     */
    #[ORM\Column(enumType: UserRole::class)]
    #[Groups(self::READ_GROUPS)]
    #[Assert\NotNull(message: 'User role is required')]
    private ?UserRole $userRole;

    /**
     * The phone number of the user (optional).
     * Must be a valid phone number format.
     */
    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
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
     * Must be unique and in valid email format.
     */
    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email address')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Email cannot be longer than {{ limit }} characters'
    )]
    private string $email;

    /**
     * The hashed password of the user.
     * Must be at least 6 characters long.
     */
    #[ORM\Column(type: 'string')]
    #[Groups(self::WRITE_GROUPS)]
    #[Assert\NotBlank(message: 'Password is required')]
    #[Assert\Length(
        min: 6,
        minMessage: 'Password must be at least {{ limit }} characters long'
    )]
    private ?string $password;

    /**
     * The current status of the user account.
     * Indicates whether the account is active or inactive.
     */
    #[ORM\Column(enumType: Status::class)]
    #[Groups(self::READ_GROUPS)]
    #[Assert\NotNull(message: 'Status is required')]
    private ?Status $status = null;

    /**
     * The timestamp when the user was created.
     */
    #[ORM\Column(type: 'datetime')]
    #[Groups(self::READ_GROUPS)]
    private DateTime $createdAt;

    /**
     * The timestamp when the user was last updated.
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(self::READ_GROUPS)]
    private ?DateTime $updatedAt;

    /**
     * The collection of payment methods associated with the user.
     *
     * @var Collection<int, PaymentMethod>
     */
    #[ORM\OneToMany(targetEntity: PaymentMethod::class, mappedBy: 'user')]
    private Collection $paymentMethods;

    /**
     * The collection of reservations made by the user.
     *
     * @var Collection<int, Reservation>
     */
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
    public function addPaymentMethod(PaymentMethod $paymentMethod): self
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
    public function addReservation(Reservation $reservation): self
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
    public function removeReservation(Reservation $reservation): self
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
     * @return $this
     */
    public function setUpdatedAt(?\DateTime $updatedAt): self
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
//            $this->setPassword(null);
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
}
