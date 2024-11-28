<?php

    namespace App\Form;

    use App\Entity\ReservationEquipment;
    use App\Entity\Reservation;
    use App\Entity\Equipment;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class ReservationEquipmentType extends AbstractType
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
                        'class' => 'form-select',
                    ],
                ])
                ->add('quantity', IntegerType::class, [
                    'label' => 'Quantity',
                    'attr' => [
                        'placeholder' => 'Enter the quantity',
                        'class' => 'form-control',
                        'min' => 1, // Ensure positive quantity
                    ],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => ReservationEquipment::class,
            ]);
        }
    }
