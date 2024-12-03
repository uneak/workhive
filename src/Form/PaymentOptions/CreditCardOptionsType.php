<?php

    namespace App\Form\PaymentOptions;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class CreditCardOptionsType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('number', TextType::class, [
                    'label' => 'Card Number',
                    'attr'  => [
                        'class'       => 'form-control',
                        'placeholder' => 'Enter the card number',
                    ],
                ])
                ->add('expiration', TextType::class, [
                    'label' => 'Expiration Date',
                    'attr'  => [
                        'class'       => 'form-control',
                        'placeholder' => 'MM/YY',
                    ],
                ])
                ->add('cvv', TextType::class, [
                    'label' => 'CVV',
                    'attr'  => [
                        'class'       => 'form-control',
                        'placeholder' => 'Enter CVV',
                        'maxlength'   => 4,
                    ],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'csrf_protection' => false, // Optional, if part of a larger form
            ]);
        }
    }
