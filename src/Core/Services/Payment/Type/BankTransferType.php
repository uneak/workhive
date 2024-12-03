<?php

    namespace App\Core\Services\Payment\Type;

    use App\Core\Services\Payment\Method\BankTransferPayment;
    use App\Core\Services\Payment\Options\BankTransfertOptions;
    use App\Form\PaymentOptions\BankTransfertOptionsType;
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
         * @return class-string<\App\Core\Services\Payment\Method\PaymentMethodInterface>
         */
        public function getMethod(): string
        {
            return BankTransferPayment::class;
        }

        /**
         * @return class-string<\App\Core\Services\Payment\Options\PaymentOptionsInterface>
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
