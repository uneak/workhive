<?php

    namespace App\Form\PaymentOptions;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class PaypalOptionsType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'PayPal Email',
                    'attr'  => [
                        'class'       => 'form-control',
                        'placeholder' => 'Enter your PayPal email',
                    ],
                ])
                ->add('password', PasswordType::class, [
                    'label' => 'PayPal Password',
                    'attr'  => [
                        'class'       => 'form-control',
                        'placeholder' => 'Enter your PayPal password',
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
