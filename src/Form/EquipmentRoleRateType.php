<?php

    namespace App\Form;

    use App\Entity\EquipmentRoleRate;
    use App\Entity\Equipment;
    use App\Enum\UserRole;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\MoneyType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class EquipmentRoleRateType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('equipment', EntityType::class, [
                    'class' => Equipment::class,
                    'choice_label' => 'name',
                    'label' => 'Equipment',
                    'placeholder' => 'Select an equipment',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
                ->add('userRole', ChoiceType::class, [
                    'label' => 'User Role',
                    'choices' => array_combine(
                        array_map(fn($role) => ucfirst(strtolower($role->name)), UserRole::cases()),
                        UserRole::cases()
                    ),
                    'choice_label' => fn(UserRole $role) => ucfirst(strtolower($role->name)),
                    'placeholder' => 'Select a user role',
                    'attr' => [
                        'class' => 'form-select',
                    ],
                ])
                ->add('hourlyRate', MoneyType::class, [
                    'label' => 'Hourly Rate',
                    'currency' => 'USD', // Adjust currency as needed
                    'scale' => 2,
                    'attr' => [
                        'placeholder' => 'Enter the hourly rate',
                        'class' => 'form-control',
                    ],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => EquipmentRoleRate::class,
            ]);
        }
    }
