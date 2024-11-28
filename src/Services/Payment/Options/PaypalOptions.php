<?php

    namespace App\Services\Payment\Options;

    class PaypalOptions implements PaymentOptionsInterface
    {
        private string $email;
        private string $password;

        public function __construct(?array $data = null)
        {
            if ($data !== null) {
                $this->email = $data['email'];
                $this->password = $data['password'];
            }
        }

        public function getEmail(): string
        {
            return $this->email;
        }

        public function getPassword(): string
        {
            return $this->password;
        }

        public function setEmail(string $email): void
        {
            $this->email = $email;
        }

        public function setPassword(string $password): void
        {
            $this->password = $password;
        }

        public function _toArray(): array
        {
            return [
                'email' => $this->email,
                'password' => $this->password
            ];
        }
    }
