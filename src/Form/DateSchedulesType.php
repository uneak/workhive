<?php

    namespace App\Form;

    use App\Entity\DateSchedules;
    use App\Entity\Room;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TimeType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class DateSchedulesType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('name', TextType::class, [
                    'label' => 'Schedule Name',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Optional schedule name',
                    ],
                ])
                ->add('date', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Schedule Date',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
                ->add('startedAt', TimeType::class, [
                    'widget' => 'single_text',
                    'label' => 'Start Time',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
                ->add('endedAt', TimeType::class, [
                    'widget' => 'single_text',
                    'label' => 'End Time',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ])
                ->add('isOpen', CheckboxType::class, [
                    'label' => 'Is Open',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-check-input',
                    ],
                ])
                ->add('room', EntityType::class, [
                    'class' => Room::class,
                    'choice_label' => 'name',
                    'label' => 'Room',
                    'placeholder' => 'Select a room',
                    'attr' => [
                        'class' => 'form-select',
                    ],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => DateSchedules::class,
            ]);
        }
    }
