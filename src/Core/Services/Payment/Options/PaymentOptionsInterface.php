<?php

    namespace App\Core\Services\Payment\Options;

    interface PaymentOptionsInterface
    {
        public function __construct(?array $data = null);
        public function _toArray(): array;
    }
