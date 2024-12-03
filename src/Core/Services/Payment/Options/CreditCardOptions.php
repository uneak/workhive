<?php

    namespace App\Core\Services\Payment\Options;

    class CreditCardOptions implements PaymentOptionsInterface
    {
        private string $number;
        private string $expiration;
        private string $cvv;

        public function __construct(?array $data = null)
        {
            if ($data !== null) {
                $this->number = $data['number'];
                $this->expiration = $data['expiration'];
                $this->cvv = $data['cvv'];
            }
        }

        public function getNumber(): string
        {
            return $this->number;
        }

        public function getExpiration(): string
        {
            return $this->expiration;
        }

        public function getCvv(): string
        {
            return $this->cvv;
        }

        public function setNumber(string $number): void
        {
            $this->number = $number;
        }

        public function setExpiration(string $expiration): void
        {
            $this->expiration = $expiration;
        }

        public function setCvv(string $cvv): void
        {
            $this->cvv = $cvv;
        }

        public function _toArray(): array
        {
            return [
                'number'     => $this->number,
                'expiration' => $this->expiration,
                'cvv'        => $this->cvv
            ];
        }
    }
