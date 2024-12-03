<?php

    namespace App\Form\PaymentOptions;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class BankTransfertOptionsType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('iban', TextType::class, [
                    'label' => 'IBAN',
                    'attr'  => [
                        'class'       => 'form-control',
                        'placeholder' => 'Enter the IBAN',
                    ],
                ])
                ->add('bic', TextType::class, [
                    'label' => 'BIC',
                    'attr'  => [
                        'class'       => 'form-control',
                        'placeholder' => 'Enter the BIC',
                    ],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'csrf_protection' => false, // Optional, if this is part of a sub-form
            ]);
        }
    }
