<?php

    namespace App\Form;

    use App\Entity\WeekSchedules;
    use App\Entity\Room;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\TimeType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class WeekSchedulesType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
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
                ->add('weekDay', ChoiceType::class, [
                    'label' => 'Day of the Week',
                    'choices' => [
                        'Sunday' => 0,
                        'Monday' => 1,
                        'Tuesday' => 2,
                        'Wednesday' => 3,
                        'Thursday' => 4,
                        'Friday' => 5,
                        'Saturday' => 6,
                    ],
                    'placeholder' => 'Select a day',
                    'attr' => [
                        'class' => 'form-select',
                    ],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => WeekSchedules::class,
            ]);
        }
    }
