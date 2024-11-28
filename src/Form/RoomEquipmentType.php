<?php

    namespace App\Form;

    use App\Entity\RoomEquipment;
    use App\Entity\Room;
    use App\Entity\Equipment;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class RoomEquipmentType extends AbstractType
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
                ->add('equipment', EntityType::class, [
                    'class' => Equipment::class,
                    'choice_label' => 'name',
                    'label' => 'Equipment',
                    'placeholder' => 'Select an equipment',
                    'attr' => [
                        'class' => 'form-select',
                    ],
                ])
                ->add('quantity', IntegerType::class, [
                    'label' => 'Quantity',
                    'attr' => [
                        'placeholder' => 'Enter the quantity',
                        'class' => 'form-control',
                        'min' => 1, // Ensures a positive quantity
                    ],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => RoomEquipment::class,
            ]);
        }
    }
