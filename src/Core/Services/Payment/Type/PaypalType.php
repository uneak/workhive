<?php

    namespace App\Core\Services\Payment\Type;

    use App\Core\Services\Payment\Method\PayPalPayment;
    use App\Core\Services\Payment\Options\PaypalOptions;
    use App\Form\PaymentOptions\PaypalOptionsType;
    use Symfony\Component\Form\FormTypeInterface;

    class PaypalType implements PaymentTypeInterface
    {
        public function getId(): string
        {
            return "paypal";
        }

        public function getName(): string
        {
            return "Paypal";
        }

        /**
         * @return class-string<\App\Core\Services\Payment\Method\PaymentMethodInterface>
         */
        public function getMethod(): string {
            return PayPalPayment::class;
        }

        /**
         * @return class-string<\App\Core\Services\Payment\Options\PaymentOptionsInterface>
         */
        public function getOptions(): string {
            return PaypalOptions::class;
        }

        /**
         * @return class-string<FormTypeInterface>
         */
        public function getFormType(): string
        {
            return PaypalOptionsType::class;
        }
    }
