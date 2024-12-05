<?php

    namespace App\Form;

    use App\Core\Enum\Status;
    use App\Entity\Room;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\NumberType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Vich\UploaderBundle\Form\Type\VichImageType;

    class RoomType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('name', TextType::class, [
                    'label' => 'Room Name',
                    'attr'  => [
                        'placeholder' => 'Enter the room name',
                    ],
                ])
                ->add('capacity', NumberType::class, [
                    'label' => 'Capacity',
                    'attr'  => [
                        'placeholder' => 'Enter the capacity of the room',
                        'min'         => 1,
                    ],
                ])
                ->add('width', NumberType::class, [
                    'label'    => 'Width (meters)',
                    'required' => true, // Si le champ est obligatoire
                    'scale'    => 2, // Nombre de décimales autorisées
                    'attr'     => [
                        'placeholder' => 'Enter the width in meters',
                        'step'        => 0.01, // Pour des incréments en décimales
                        'min'         => 0, // Valeur minimale (si applicable)
                    ],
                ])
                ->add('length', NumberType::class, [
                    'label'    => 'Length (meters)',
                    'required' => true, // Si le champ est obligatoire
                    'scale'    => 2, // Nombre de décimales autorisées
                    'attr'     => [
                        'placeholder' => 'Enter the length of the room',
                        'step'        => 0.01,
                        'min'         => 0, // Valeur minimale (si applicable)
                    ],
                ])
                ->add('status', ChoiceType::class, [
                    'label'        => 'Status',
                    'choices'      => array_combine(
                        array_map(fn($case) => $case->value, Status::cases()), // Labels
                        Status::cases() // Enum cases as values
                    ),
                    'choice_label' => fn(?Status $status) => $status->value, // Maps enum to label
                    'placeholder'  => 'Choose a status',
                ])
                ->add('description', TextareaType::class, [
                    'label'    => 'Description',
                    'required' => false,
                    'attr'     => [
                        'placeholder' => 'Optional description of the room',
                    ],
                ])
                ->add('photoFile', VichImageType::class, [
                    'required'        => false,
                    'allow_delete'    => true,
                    'delete_label'    => 'delete',
                    'download_label'  => 'download',
                    'download_uri'    => true,
                    'image_uri'       => true,
                    'asset_helper'    => true,
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Room::class,
            ]);
        }
    }
