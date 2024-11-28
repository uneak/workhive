<?php

    namespace App\Form;

    use App\Entity\Reservation;
    use App\Entity\Room;
    use App\Entity\User;
    use App\Enum\ReservationStatus;
    use App\Enum\UserRole;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class ReservationType extends AbstractType
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
                ->add('user', EntityType::class, [
                    'class' => User::class,
                    'choice_label' => fn(User $user) => $user->getFirstName() . ' ' . $user->getLastName(),
                    'label' => 'User',
                    'placeholder' => 'Select a user',
                    'attr' => [
                        'class' => 'form-select',
                    ],
                ])
                ->add('startAt', DateTimeType::class, [
                    'widget' => 'single_text',
                    'label' => 'Start Date and Time',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
                ->add('endAt', DateTimeType::class, [
                    'widget' => 'single_text',
                    'label' => 'End Date and Time',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
                ->add('status', ChoiceType::class, [
                    'label' => 'Reservation Status',
                    'choices' => array_combine(
                        array_map(fn($status) => $status->value, ReservationStatus::cases()),
                        ReservationStatus::cases()
                    ),
                    'choice_label' => fn(ReservationStatus $status) => $status->value,
                    'placeholder' => 'Select a status',
                    'attr' => [
                        'class' => 'form-select',
                    ],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Reservation::class,
            ]);
        }
    }
