<?php

    namespace App\Services\Payment\Options;

    /**
     * Represents the payment options for a bank transfer.
     */
    class BankTransfertOptions implements PaymentOptionsInterface
    {
        /**
         * The International Bank Account Number (IBAN).
         *
         * @var string
         */
        private string $iban;

        /**
         * The Bank Identifier Code (BIC).
         *
         * @var string
         */
        private string $bic;

        /**
         * Initializes the bank transfer options from an array of data.
         *
         * @param array $data The associative array containing 'iban' and 'bic'.
         */
        public function __construct(?array $data = null) {
            if ($data !== null) {
                $this->iban = $data['iban'];
                $this->bic = $data['bic'];
            }
        }

        /**
         * Get the IBAN for the bank transfer.
         *
         * @return string The IBAN.
         */
        public function getIban(): string
        {
            return $this->iban;
        }

        /**
         * Get the BIC for the bank transfer.
         *
         * @return string The BIC.
         */
        public function getBic(): string
        {
            return $this->bic;
        }

        public function setIban(string $iban): void
        {
            $this->iban = $iban;
        }

        public function setBic(string $bic): void
        {
            $this->bic = $bic;
        }


        /**
         * Convert the bank transfer options to an associative array.
         *
         * @return array The array containing 'iban' and 'bic'.
         */
        public function _toArray(): array
        {
            return [
                'iban' => $this->iban,
                'bic' => $this->bic,
            ];
        }
    }
