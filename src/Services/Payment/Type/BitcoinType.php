<?php

    namespace App\Services\Payment\Type;

    use App\Form\PaymentOptions\BankTransfertOptionsType;
    use App\Form\PaymentOptions\BitcoinOptionsType;
    use App\Services\Payment\Method\BitcoinPayment;
    use App\Services\Payment\Options\BitcoinOptions;
    use Symfony\Component\Form\FormTypeInterface;

    class BitcoinType implements PaymentTypeInterface
    {
        public function getId(): string
        {
            return "bitcoin";
        }

        public function getName(): string
        {
            return "Bitcoin";
        }

        /**
         * @return class-string<\App\Services\Payment\Method\PaymentMethodInterface>
         */
        public function getMethod(): string {
            return BitcoinPayment::class;
        }

        /**
         * @return class-string<\App\Services\Payment\Options\PaymentOptionsInterface>
         */
        public function getOptions(): string {
            return BitcoinOptions::class;
        }

        /**
         * @return class-string<FormTypeInterface>
         */
        public function getFormType(): string
        {
            return BitcoinOptionsType::class;
        }
    }
