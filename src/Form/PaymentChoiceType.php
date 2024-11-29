<?php

    namespace App\Form;

    use App\Entity\User;
    use App\Repository\PaymentMethodRepository;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class PaymentChoiceType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $paymentMethods = $options['user']->getPaymentMethods();

            $choices = [];
            foreach ($paymentMethods as $method) {
                $choices[$method->getLabel()] = $method;
            }

            $builder
                ->add('payment_method', ChoiceType::class, [
                    'choices'      => $choices,
                    'choice_label' => function ($choice) {
                        return $choice->getLabel();
                    },
                    'placeholder'  => 'Choisissez un moyen de paiement',
                    'attr'         => ['class' => 'form-select'],
                ])
                ->add('startAt', DateTimeType::class, [
                    'widget' => 'single_text',
                    'label'  => 'Date et heure de début',
                    'attr'   => [
                        'class' => 'form-control',
                    ],
                ])
                ->add('endAt', DateTimeType::class, [
                    'widget' => 'single_text',
                    'label'  => 'Date et heure de fin',
                    'attr'   => [
                        'class' => 'form-control',
                    ],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([]);
            $resolver->setRequired(['user']);
        }
    }
