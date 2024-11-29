<?php

    namespace App\Form;

    use App\Entity\PaymentMethod;
    use App\Services\Payment\PaymentFactory;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class PaymentMethodType extends AbstractType
    {
        public function __construct(
            private readonly PaymentFactory $paymentFactory
        ) {
        }

        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('label', TextType::class, [
                    'label' => 'Payment Method Label',
                    'attr'  => [
                        'class'       => 'form-control',
                        'placeholder' => 'Enter a label (e.g., Visa, PayPal)',
                    ],
                ]);

            // Listen to the POST_SET_DATA event to dynamically add the `data` subform
            $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $paymentMethod = $event->getData();

                if (!$paymentMethod instanceof PaymentMethod || !$paymentMethod->getType()) {
                    return;
                }

                $paymentType = $this->paymentFactory->getPaymentType($paymentMethod->getType())->getFormType();
                $form->add('data', $paymentType, [
                    'label' => false,
                ]);
            });
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => PaymentMethod::class,
            ]);
        }
    }
