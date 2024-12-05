<?php

    namespace App\Form;

    use App\Core\Enum\Status;
    use App\Core\Enum\UserRole;
    use App\Entity\User;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Vich\UploaderBundle\Form\Type\VichImageType;

    class UserType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('firstName', TextType::class, [
                    'label' => 'First Name',
                    'attr'  => [
                        'placeholder' => 'Enter first name',
                    ],
                ])
                ->add('lastName', TextType::class, [
                    'label' => 'Last Name',
                    'attr'  => [
                        'placeholder' => 'Enter last name',
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
                ->add('userRole', ChoiceType::class, [
                    'label'        => 'Role',
                    'choices'      => array_combine(
                        array_map(fn($role) => $role->value, UserRole::cases()),
                        UserRole::cases()
                    ),
                    'choice_label' => fn(UserRole $role) => $role->value,
                    'placeholder'  => 'Select a role',
                ])
                ->add('phone', TextType::class, [
                    'label'    => 'Phone Number',
                    'required' => false,
                    'attr'     => [
                        'placeholder' => 'Enter phone number',
                    ],
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Email Address',
                    'attr'  => [
                        'placeholder' => 'Enter email address',
                    ],
                ])
                ->add('password', PasswordType::class, [
                    'label' => 'Password',
                    'attr'  => [
                        'placeholder' => 'Enter password',
                    ],
                ])
                ->add('status', ChoiceType::class, [
                    'label'        => 'Status',
                    'choices'      => array_combine(
                        array_map(fn($status) => $status->value, Status::cases()),
                        Status::cases()
                    ),
                    'choice_label' => fn(Status $status) => $status->value,
                    'placeholder'  => 'Select a status',
                ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => User::class,
            ]);
        }
    }
