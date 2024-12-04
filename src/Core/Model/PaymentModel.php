<?php

    namespace App\Core\Model;

    use App\Core\Enum\PaymentStatus;

    /**
     * Interface for Payment.
     */
    interface PaymentModel extends ObjectModel
    {
        public const GROUP_PREFIX = 'payment';

        /**
         * Get the reservation associated with this payment.
         *
         * @return ReservationModel|null The associated reservation.
         */
        public function getReservation(): ?ReservationModel;

        /**
         * Get the ID of the reservation associated with this payment.
         */
        public function getReservationId(): ?int;

        /**
         * Set the reservation associated with this payment.
         *
         * @param ReservationModel|null $reservation The reservation to associate.
         *
         * @return static
         */
        public function setReservation(?ReservationModel $reservation): static;

        /**
         * Get the payment method used for this payment.
         *
         * @return PaymentMethodModel|null The payment method.
         */
        public function getPaymentMethod(): ?PaymentMethodModel;

        /**
         * Get the ID of the payment method used for this payment.
         */
        public function getPaymentMethodId(): ?int;

        /**
         * Set the payment method used for this payment.
         *
         * @param PaymentMethodModel|null $paymentMethod The payment method to set.
         *
         * @return static
         */
        public function setPaymentMethod(?PaymentMethodModel $paymentMethod): static;

        /**
         * Get the amount paid.
         *
         * @return float The amount.
         */
        public function getAmount(): float;

        /**
         * Set the amount paid.
         *
         * @param float $amount The amount to set.
         *
         * @return static
         */
        public function setAmount(float $amount): static;

        /**
         * Get the current status of the payment.
         *
         * @return PaymentStatus The payment status.
         */
        public function getStatus(): PaymentStatus;

        /**
         * Set the current status of the payment.
         *
         * @param PaymentStatus $status The status to set.
         *
         * @return static
         */
        public function setStatus(PaymentStatus $status): static;
    }
