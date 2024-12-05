<?php

    namespace App\Form;

    use App\Entity\Equipment;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Vich\UploaderBundle\Form\Type\VichImageType;

    class EquipmentType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('name', TextType::class, [
                    'label' => 'Equipment Name',
                    'attr' => [
                        'placeholder' => 'Enter the equipment name',
                        'class' => 'form-control',
                    ],
                ])
                ->add('description', TextareaType::class, [
                    'label' => 'Description',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Optional: provide a brief description',
                        'class' => 'form-control',
                        'rows' => 4,
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
                ])
                ->add('totalStock', IntegerType::class, [
                    'label' => 'Total Stock',
                    'attr' => [
                        'placeholder' => 'Enter the total stock available',
                        'class' => 'form-control',
                        'min' => 0,
                    ],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Equipment::class,
            ]);
        }
    }
