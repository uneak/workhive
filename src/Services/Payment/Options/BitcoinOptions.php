<?php

    namespace App\Services\Payment\Options;

    class BitcoinOptions implements PaymentOptionsInterface
    {
        private string $address;

        public function __construct(?array $data = null) {
            if ($data !== null) {
                $this->address = $data['address'];
            }
        }

        public function getAddress(): string
        {
            return $this->address;
        }

        public function setAddress(string $address): void
        {
            $this->address = $address;
        }


        public function _toArray(): array
        {
            return [
                'address' => $this->address
            ];
        }
    }
