<?php

    namespace App\Entity;

    use App\Core\Model\PaymentMethodModel;
    use App\Core\Model\UserModel;
    use App\Core\Services\Payment\Options\PaymentOptionsInterface;
    use App\Repository\PaymentMethodRepository;
    use DateTime;
    use Doctrine\ORM\Mapping as ORM;
    use OpenApi\Attributes as OA;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Represents a payment method entity for processing payments.
     *
     * This entity stores payment method information associated with a user,
     * such as credit cards, PayPal accounts, or other payment options.
     * It ensures secure storage of payment details for future transactions.
     *
     * Groups:
     * - read: Global read access for basic payment method information
     * - write: Global write access for creating/updating payment methods
     * - payment-method:read: Specific read access for payment method details
     * - payment-method:write: Specific write access for payment method management
     */
    #[OA\Schema(
        title: 'PaymentMethod',
        description: 'Represents a payment method available for processing transactions',
        type: 'object'
    )]
    #[ORM\Entity(repositoryClass: PaymentMethodRepository::class)]
    #[ORM\Table(name: 'payment_methods')]
    class PaymentMethod implements PaymentMethodModel
    {
        /**
         * The unique identifier of the payment method.
         * Auto-generated primary key for the payment method entity.
         *
         * @var int|null
         */
        #[OA\Property(
            property: 'id',
            description: 'The unique identifier of the payment method',
            type: 'integer',
            example: 1
        )]
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        #[Groups(PaymentMethodModel::READ_GROUPS)]
        private ?int $id = null;

        /**
         * The user who owns this payment method.
         * Represents the many-to-one relationship with the User entity.
         *
         * @var UserModel
         */
        #[OA\Property(
            ref: new Model(type: User::class),
            description: 'The user who owns this payment method'
        )]
        #[ORM\ManyToOne(targetEntity: User::class)]
        #[ORM\JoinColumn(nullable: false)]
        #[Assert\NotNull(message: 'User is required')]
        #[Groups(PaymentMethodModel::WRITE_GROUPS)]
        private UserModel $user;

        /**
         * The display name or description of the payment method.
         * Examples: "Personal Visa Card", "Business PayPal Account"
         *
         * @var string
         */
        #[OA\Property(
            property: 'label',
            description: 'The display name or description of the payment method',
            type: 'string',
            maxLength: 100,
            example: 'Personal Visa Card'
        )]
        #[ORM\Column(type: 'string', length: 100)]
        #[Groups(PaymentMethodModel::RW_GROUPS)]
        #[Assert\NotBlank(message: 'Payment method label is required')]
        #[Assert\Length(
            max: 100,
            maxMessage: 'Payment method label cannot be longer than {{ limit }} characters'
        )]
        private string $label;

        /**
         * The type identifier for the payment method.
         * Defines the payment processing system to be used.
         * Examples: "credit_card", "paypal", "stripe"
         *
         * @var string
         */
        #[OA\Property(
            property: 'type',
            description: 'The type identifier for the payment method (e.g., credit_card, paypal, stripe)',
            type: 'string',
            maxLength: 50,
            example: 'credit_card'
        )]
        #[ORM\Column(type: 'string', length: 50)]
        #[Groups(PaymentMethodModel::RW_GROUPS)]
        #[Assert\NotBlank(message: 'Payment method type is required')]
        #[Assert\Length(
            max: 50,
            maxMessage: 'Payment method type cannot be longer than {{ limit }} characters'
        )]
        private string $type;

        /**
         * Secure storage for payment method specific details.
         * Contains encrypted or tokenized payment information.
         * Structure varies based on payment method type.
         *
         * @var array
         */
        #[OA\Property(
            property: 'data',
            description: 'Secure storage for payment method specific details (encrypted/tokenized)',
            type: 'object',
            example: ['last4' => '1234', 'brand' => 'visa']
        )]
        #[ORM\Column(type: 'json')]
        #[Groups(PaymentMethodModel::RW_GROUPS)]
        private array $data;

        /**
         * The timestamp when the payment method was created.
         *
         * @var DateTime
         */
        #[OA\Property(
            property: 'createdAt',
            description: 'The timestamp when the payment method was created',
            type: 'string',
            format: 'date-time',
            example: '2024-01-01T12:00:00+00:00'
        )]
        #[ORM\Column(type: 'datetime')]
        #[Groups(PaymentMethodModel::READ_GROUPS)]
        private DateTime $createdAt;

        /**
         * The timestamp when the payment method was last updated.
         *
         * @var DateTime|null
         */
        #[OA\Property(
            property: 'updatedAt',
            description: 'The timestamp when the payment method was last updated',
            type: 'string',
            format: 'date-time',
            example: '2024-01-02T15:30:00+00:00',
            nullable: true
        )]
        #[ORM\Column(type: 'datetime', nullable: true)]
        #[Groups(PaymentMethodModel::READ_GROUPS)]
        private ?DateTime $updatedAt;

        /**
         * Initializes the payment method with the creation timestamp.
         */
        public function __construct()
        {
            $this->createdAt = new DateTime();
        }

        /**
         * Get the unique identifier of the payment method.
         *
         * @return int|null
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get the user associated with this payment method.
         *
         * @return UserModel
         */
        public function getUser(): UserModel
        {
            return $this->user;
        }

        /**
         * Set the user associated with this payment method.
         *
         * @param UserModel $user
         *
         * @return $this
         */
        public function setUser(UserModel $user): static
        {
            $this->user = $user;

            return $this;
        }

        /**
         * Get the display name or description of the payment method.
         *
         * @return string
         */
        public function getLabel(): string
        {
            return $this->label;
        }

        /**
         * Set the display name or description of the payment method.
         *
         * @param string $label
         *
         * @return $this
         */
        public function setLabel(string $label): static
        {
            $this->label = $label;

            return $this;
        }

        /**
         * Get the type identifier for the payment method.
         *
         * @return string
         */
        public function getType(): string
        {
            return $this->type;
        }

        /**
         * Set the type identifier for the payment method.
         *
         * @param string $type
         *
         * @return $this
         */
        public function setType(string $type): static
        {
            $this->type = $type;

            return $this;
        }

        /**
         * Get secure storage for payment method specific details.
         *
         * @return array
         */
        public function getData(): array
        {
            return $this->data;
        }

        /**
         * Set secure storage for payment method specific details.
         *
         * @param array|PaymentOptionsInterface $data
         *
         * @return $this
         */
        public function setData(array|PaymentOptionsInterface $data): static
        {
            $this->data = ($data instanceof PaymentOptionsInterface) ? $data->_toArray() : $data;

            return $this;
        }

        /**
         * Get the timestamp when the payment method was created.
         *
         * @return DateTime
         */
        public function getCreatedAt(): DateTime
        {
            return $this->createdAt;
        }

        /**
         * Set the timestamp when the payment method was created.
         *
         * @param DateTime $createdAt
         *
         * @return void
         */
        public function setCreatedAt(DateTime $createdAt): void
        {
            $this->createdAt = $createdAt;
        }

        /**
         * Get the timestamp when the payment method was last updated.
         *
         * @return DateTime|null
         */
        public function getUpdatedAt(): ?DateTime
        {
            return $this->updatedAt;
        }

        /**
         * Set the timestamp when the payment method was last updated.
         *
         * @param DateTime|null $updatedAt
         *
         * @return $this
         */
        public function setUpdatedAt(?DateTime $updatedAt): static
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }

        #[Groups(PaymentMethodModel::READ_GROUPS)]
        public function getUserId(): ?int
        {
            return $this->user->getId();
        }
    }
