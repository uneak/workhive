<?php

    namespace App\Core\Services\Payment\Type;

    use App\Core\Services\Payment\Method\BitcoinPayment;
    use App\Core\Services\Payment\Options\BitcoinOptions;
    use App\Form\PaymentOptions\BitcoinOptionsType;
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
         * @return class-string<\App\Core\Services\Payment\Method\PaymentMethodInterface>
         */
        public function getMethod(): string {
            return BitcoinPayment::class;
        }

        /**
         * @return class-string<\App\Core\Services\Payment\Options\PaymentOptionsInterface>
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
