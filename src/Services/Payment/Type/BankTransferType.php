<?php

    namespace App\Services\Payment\Type;

    use App\Form\PaymentOptions\BankTransfertOptionsType;
    use App\Services\Payment\Method\BankTransferPayment;
    use App\Services\Payment\Options\BankTransfertOptions;
    use Symfony\Component\Form\FormTypeInterface;

    class BankTransferType implements PaymentTypeInterface
    {
        public function getId(): string
        {
            return "bank_transfer";
        }

        public function getName(): string
        {
            return "Virement bancaire";
        }

        /**
         * @return class-string<\App\Services\Payment\Method\PaymentMethodInterface>
         */
        public function getMethod(): string
        {
            return BankTransferPayment::class;
        }

        /**
         * @return class-string<\App\Services\Payment\Options\PaymentOptionsInterface>
         */
        public function getOptions(): string
        {
            return BankTransfertOptions::class;
        }

        /**
         * @return class-string<FormTypeInterface>
         */
        public function getFormType(): string
        {
            return BankTransfertOptionsType::class;
        }
    }
