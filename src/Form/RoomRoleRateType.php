<?php

    namespace App\Form;

    use App\Core\Enum\UserRole;
    use App\Entity\Room;
    use App\Entity\RoomRoleRate;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\MoneyType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class RoomRoleRateType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('room', EntityType::class, [
                    'class' => Room::class,
                    'choice_label' => 'name',
                    'label' => 'Room',
                    'placeholder' => 'Select a room',
                    'attr' => [
                        'class' => 'form-select',
                    ],
                ])
                ->add('userRole', ChoiceType::class, [
                    'label' => 'User Role',
                    'choices' => array_combine(
                        array_map(fn($role) => $role->value, UserRole::cases()),
                        UserRole::cases()
                    ),
                    'choice_label' => fn(UserRole $role) => $role->value,
                    'placeholder' => 'Select a user role',
                    'attr' => [
                        'class' => 'form-select',
                    ],
                ])
                ->add('hourlyRate', MoneyType::class, [
                    'label' => 'Hourly Rate',
                    'currency' => 'EUR',
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
                'data_class' => RoomRoleRate::class,
            ]);
        }
    }
