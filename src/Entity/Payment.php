<?php

    namespace App\Entity;

    use App\Repository\PaymentRepository;
    use App\Enum\PaymentStatus;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * Represents a payment made for a reservation.
     */
    #[ORM\Entity(repositoryClass: PaymentRepository::class)]
    #[ORM\Table(name: 'payments')]
    class Payment
    {
        /**
         * The unique identifier of the payment.
         *
         * @var int|null
         */
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private ?int $id = null;

        /**
         * The reservation associated with this payment.
         *
         * @var Reservation|null
         */
        #[ORM\ManyToOne(targetEntity: Reservation::class, inversedBy: 'payments')]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        private ?Reservation $reservation = null;

        /**
         * The payment method used for this payment.
         *
         * @var PaymentMethod|null
         */
        #[ORM\ManyToOne(targetEntity: PaymentMethod::class)]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        private ?PaymentMethod $paymentMethod = null;

        /**
         * The amount paid.
         *
         * @var float
         */
        #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
        private float $amount;

        /**
         * The current status of the payment.
         *
         * @var PaymentStatus
         */
        #[ORM\Column(type: 'string', enumType: PaymentStatus::class)]
        private PaymentStatus $status = PaymentStatus::PENDING;

        /**
         * The timestamp when the payment was created.
         *
         * @var \DateTime
         */
        #[ORM\Column(type: 'datetime')]
        private \DateTime $createdAt;

        /**
         * The timestamp when the payment was last updated.
         *
         * @var \DateTime|null
         */
        #[ORM\Column(type: 'datetime', nullable: true)]
        private ?\DateTime $updatedAt = null;

        /**
         * Initializes the payment with default values.
         */
        public function __construct()
        {
            $this->createdAt = new \DateTime();
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
         * @return Reservation|null The associated reservation.
         */
        public function getReservation(): ?Reservation
        {
            return $this->reservation;
        }

        /**
         * Set the reservation associated with this payment.
         *
         * @param Reservation|null $reservation The reservation to associate.
         * @return $this
         */
        public function setReservation(?Reservation $reservation): self
        {
            $this->reservation = $reservation;

            return $this;
        }

        /**
         * Get the payment method used for this payment.
         *
         * @return PaymentMethod|null The payment method.
         */
        public function getPaymentMethod(): ?PaymentMethod
        {
            return $this->paymentMethod;
        }

        /**
         * Set the payment method used for this payment.
         *
         * @param PaymentMethod|null $paymentMethod The payment method to set.
         * @return $this
         */
        public function setPaymentMethod(?PaymentMethod $paymentMethod): self
        {
            $this->paymentMethod = $paymentMethod;

            return $this;
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
        public function setAmount(float $amount): self
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
        public function setStatus(PaymentStatus $status): self
        {
            $this->status = $status;

            return $this;
        }

        /**
         * Get the timestamp when the payment was created.
         *
         * @return \DateTime The creation timestamp.
         */
        public function getCreatedAt(): \DateTime
        {
            return $this->createdAt;
        }

        /**
         * Set the timestamp when the payment was created.
         *
         * @param \DateTime $createdAt The creation timestamp.
         * @return $this
         */
        public function setCreatedAt(\DateTime $createdAt): self
        {
            $this->createdAt = $createdAt;

            return $this;
        }

        /**
         * Get the timestamp when the payment was last updated.
         *
         * @return \DateTime|null The last update timestamp.
         */
        public function getUpdatedAt(): ?\DateTime
        {
            return $this->updatedAt;
        }

        /**
         * Set the timestamp when the payment was last updated.
         *
         * @param \DateTime|null $updatedAt The update timestamp.
         * @return $this
         */
        public function setUpdatedAt(?\DateTime $updatedAt): self
        {
            $this->updatedAt = $updatedAt;

            return $this;
        }
    }
