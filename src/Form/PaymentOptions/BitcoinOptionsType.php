<?php

    namespace App\Form\PaymentOptions;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class BitcoinOptionsType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('address', TextType::class, [
                    'label' => 'Bitcoin Address',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Enter the Bitcoin address',
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
