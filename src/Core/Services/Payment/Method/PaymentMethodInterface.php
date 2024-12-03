<?php

    namespace App\Core\Services\Payment\Method;

    use App\Core\Services\Payment\Options\PaymentOptionsInterface;

    interface PaymentMethodInterface
    {
        public function pay(float $amount, ?PaymentOptionsInterface $options = null): void;
    }
