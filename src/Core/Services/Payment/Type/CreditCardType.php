<?php

    namespace App\Core\Services\Payment\Type;

    use App\Core\Services\Payment\Method\CreditCardPayment;
    use App\Core\Services\Payment\Options\CreditCardOptions;
    use App\Form\PaymentOptions\CreditCardOptionsType;
    use Symfony\Component\Form\FormTypeInterface;

    class CreditCardType implements PaymentTypeInterface
    {
        public function getId(): string
        {
            return "credit_card";
        }

        public function getName(): string
        {
            return "Credit Card";
        }

        /**
         * @return class-string<\App\Core\Services\Payment\Method\PaymentMethodInterface>
         */
        public function getMethod(): string {
            return CreditCardPayment::class;
        }

        /**
         * @return class-string<\App\Core\Services\Payment\Options\PaymentOptionsInterface>
         */
        public function getOptions(): string {
            return CreditCardOptions::class;
        }

        /**
         * @return class-string<FormTypeInterface>
         */
        public function getFormType(): string
        {
            return CreditCardOptionsType::class;
        }
    }
