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
        public const CREATE_GROUPS = [ObjectModel::CREATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::CREATE_PREFIX];
        public const UPDATE_GROUPS = [ObjectModel::UPDATE_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::UPDATE_PREFIX];
        public const WRITE_GROUPS = [...self::CREATE_GROUPS, ...self::UPDATE_GROUPS];
        public const READ_GROUPS = [ObjectModel::READ_PREFIX, self::GROUP_PREFIX . ':' . ObjectModel::READ_PREFIX];
        public const RW_GROUPS = [...self::READ_GROUPS, ...self::WRITE_GROUPS];

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
         * @param UserModel $user
         *
         * @return static
         */
        public function setUser(UserModel $user): static;

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
