<?php

    namespace App\Entity;

    use App\Core\Enum\PaymentStatus;
    use App\Core\Model\PaymentMethodModel;
    use App\Core\Model\PaymentModel;
    use App\Core\Model\ReservationModel;
    use App\Repository\PaymentRepository;
    use DateTime;
    use Doctrine\ORM\Mapping as ORM;
    use OpenApi\Attributes as OA;
    use Nelmio\ApiDocBundle\Attribute\Model;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * Represents a payment made for a reservation.
     *
     * This entity represents a payment transaction in the application.
     * It tracks payment details, status, and associated reservation.
     *
     * Groups:
     * - read: Global read group
     * - write: Global write group
     * - payment:read: Payment-specific read group
     * - payment:write: Payment-specific write group
     */
    #[OA\Schema(
        title: 'Payment',
        description: 'Represents a payment transaction for a reservation',
        type: 'object'
    )]
    #[ORM\Entity(repositoryClass: PaymentRepository::class)]
    #[ORM\Table(name: 'payments')]
    class Payment implements PaymentModel
    {
        public const READ_GROUPS = ['read', PaymentModel::GROUP_PREFIX . ':read'];
        public const WRITE_GROUPS = ['write', PaymentModel::GROUP_PREFIX . ':write'];

        /**
         * The unique identifier of the payment.
         *
         * @var int|null
         */
        #[OA\Property(
            property: 'id',
            description: 'The unique identifier of the payment',
            type: 'integer',
            example: 1
        )]
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        #[Groups(self::READ_GROUPS)]
        private ?int $id = null;

        /**
         * The reservation associated with this payment.
         *
         * @var ReservationModel|null
         */
        #[OA\Property(
            ref: new Model(type: Reservation::class),
            description: 'The reservation associated with this payment'
        )]
        #[ORM\ManyToOne(targetEntity: Reservation::class, inversedBy: 'payments')]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        #[Groups(self::WRITE_GROUPS)]
        #[Assert\NotNull(message: 'Reservation is required')]
        private ?ReservationModel $reservation = null;

        /**
         * The payment method used for this payment.
         *
         * @var PaymentMethodModel|null
         */
        #[OA\Property(
            ref: new Model(type: PaymentMethod::class),
            description: 'The payment method used for this payment'
        )]
        #[ORM\ManyToOne(targetEntity: PaymentMethod::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        #[Groups(self::WRITE_GROUPS)]
        #[Assert\NotNull(message: 'Payment method is required')]
        private ?PaymentMethodModel $paymentMethod = null;

        /**
         * The amount paid.
         *
         * @var float
         */
        #[OA\Property(
            property: 'amount',
            description: 'The amount paid',
            type: 'number',
            format: 'float',
            minimum: 0,
            example: 99.99
        )]
        #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
        #[Groups([...self::READ_GROUPS, ...self::WRITE_GROUPS])]
        #[Assert\NotNull(message: 'Amount is required')]
        #[Assert\Positive(message: 'Amount must be positive')]
        private float $amount;

        /**
         * The current status of the payment.
         *
         * @var PaymentStatus
         */
        #[OA\Property(
            ref: new Model(type: PaymentStatus::class),
            description: 'The current status of the payment'
        )]
        #[ORM\Column(type: 'string', enumType: PaymentStatus::class)]
        #[Groups(self::READ_GROUPS)]
        #[Assert\NotNull(message: 'Payment status is required')]
        private PaymentStatus $status = PaymentStatus::PENDING;

        /**
         * The timestamp when the payment was created.
         *
         * @var \DateTime
         */
        #[OA\Property(
            property: 'createdAt',
            description: 'The timestamp when the payment was created',
            type: 'string',
            format: 'date-time',
            example: '2024-01-01T12:00:00+00:00'
        )]
        #[ORM\Column(type: 'datetime')]
        #[Groups(self::READ_GROUPS)]
        private DateTime $createdAt;

        /**
         * The timestamp when the payment was last updated.
         *
         * @var \DateTime|null
         */
        #[OA\Property(
            property: 'updatedAt',
            description: 'The timestamp when the payment was last updated',
            type: 'string',
            format: 'date-time',
            example: '2024-01-02T15:30:00+00:00',
            nullable: true
        )]
        #[ORM\Column(type: 'datetime', nullable: true)]
        #[Groups(self::READ_GROUPS)]
        private ?DateTime $updatedAt = null;

        /**
         * Initializes the payment with default values.
         */
        public function __construct()
        {
            $this->createdAt = new DateTime();
            $this->status = PaymentStatus::PENDING;
        }

        /**
         * Get the unique identifier of the payment.
         *
         * @return int|null The payment ID.
         */
        public function getId(): ?int
        {
            return $this->id;
        }

        /**
         * Get the reservation associated with this payment.
         *
         * @return ReservationModel|null The associated reservation.
         */
        public function getReservation(): ?ReservationModel
        {
            return $this->reservation;
        }

        /**
         * Set the reservation associated with this payment.
         *
         * @param ReservationModel|null $reservation The reservation to associate.
         * @return $this
         */
        public function setReservation(?ReservationModel $reservation): static
        {
            $this->reservation = $reservation;

            return $this;
        }

        /**
         * Get the ID of the reservation associated with this payment.
         *
         * @return int|null
         */
        #[OA\Property(
            property: 'reservationId',
            description: 'The ID of the reservation associated with this payment',
            type: 'integer',
            example: 1
        )]
        #[Groups(self::READ_GROUPS)]
        public function getReservationId(): ?int
        {
            return $this->reservation?->getId();
        }

        /**
         * Get the payment method used for this payment.
         *
         * @return PaymentMethodModel|null The payment method.
         */
        public function getPaymentMethod(): ?PaymentMethodModel
        {
            return $this->paymentMethod;
        }

        /**
         * Set the payment method used for this payment.
         *
         * @param PaymentMethodModel|null $paymentMethod The payment method to set.
         *
         * @return static
         */
        public function setPaymentMethod(?PaymentMethodModel $paymentMethod): static
        {
            $this->paymentMethod = $paymentMethod;

            return $this;
        }

        /**
         * Get the ID of the payment method used for this payment.
         *
         * @return int|null
         */
        #[OA\Property(
            property: 'paymentMethodId',
            description: 'The ID of the payment method used for this payment',
            type: 'integer',
            example: 1
        )]
        #[Groups(self::READ_GROUPS)]
        public function getPaymentMethodId(): ?int
        {
            return $this->paymentMethod?->getId();
        }

        /**
         * Get the amount paid.
         *
         * @return float The amount.
         */
        public function getAmount(): float
        {
            return $this->amount;
        }

        /**
         * Set the amount paid.
         *
         * @param float $amount The amount to set.
         * @return $this
         */
        public function setAmount(float $amount): static
        {
            $this->amount = $amount;

            return $this;
        }

        /**
         * Get the current status of the payment.
         *
         * @return PaymentStatus The payment status.
         */
        public function getStatus(): PaymentStatus
        {
            return $this->status;
        }

        /**
         * Set the current status of the payment.
         *
         * @param PaymentStatus $status The status to set.
         * @return $this
         */
        public function setStatus(PaymentStatus $status): static
        {
            $this->status = $status;

            return $this;
        }

        /**
         * Get the timestamp when the payment was created.
         *
         * @return \DateTime The creation timestamp.
         */
        public function getCreatedAt(): DateTime
        {
            return $this->createdAt;
        }

        /**
         * Set the timestamp when the payment was created.
         *
         * @param \DateTime $createdAt The creation timestamp.
         * @return $this
         */
        public function setCreatedAt(DateTime $createdAt): static
        {
            $this->createdAt = $createdAt;

            return $this;
        }

        /**
         * Get the timestamp when the payment was last updated.
         *
         * @return \DateTime|null The last update timestamp.
         */
        public function getUpdatedAt(): ?DateTime
        {
            return $this->updatedAt;
        }

        /**
         * Set the timestamp when the payment was last updated.
         *
         * @param \DateTime|null $updatedAt The update timestamp.
         * @return $this
         */
        public function setUpdatedAt(?DateTime $updatedAt): static
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }
    }
