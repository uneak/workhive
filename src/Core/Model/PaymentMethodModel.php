<?php

    namespace App\Core\Model;

    use App\Core\Services\Payment\Options\PaymentOptionsInterface;
    use DateTime;

    /**
     * Interface for PaymentMethod.
     */
    interface PaymentMethodModel extends ObjectModel
    {
        public const GROUP_PREFIX = 'payment_method';

        /**
         * Get the user associated with this payment method.
         *
         * @return UserModel|null
         */
        public function getUser(): ?UserModel;

        /**
         * Get the ID of the user associated with this payment method.
         */
        public function getUserId(): ?int;

        /**
         * Set the user associated with this payment method.
         *
         * @param UserModel|null $user
         *
         * @return static
         */
        public function setUser(?UserModel $user): static;

        /**
         * Get the type of the payment method.
         *
         * @return string
         */
        public function getType(): string;

        /**
         * Set the type of the payment method.
         *
         * @param string $type
         *
         * @return static
         */
        public function setType(string $type): static;

        /**
         * Get additional data for the payment method.
         *
         * @return array
         */
        public function getData(): array;

        /**
         * Set additional data for the payment method.
         *
         * @param array|PaymentOptionsInterface $data
         *
         * @return static
         */
        public function setData(array|PaymentOptionsInterface $data): static;
    }
