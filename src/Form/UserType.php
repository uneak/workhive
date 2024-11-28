<?php

    namespace App\Form;

    use App\Entity\User;
    use App\Enum\Status;
    use App\Enum\UserRole;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

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
                ->add('photo', FileType::class, [
                    'label'    => 'Profile Photo',
                    'required' => false,
                    'mapped'   => false, // Not mapped to the entity
                    'attr'     => [
                        'accept' => 'image/*',
                    ],
                ])
                ->add('userRole', ChoiceType::class, [
                    'label'        => 'Role',
                    'choices'      => array_combine(
                        array_map(fn($role) => ucfirst(strtolower($role->name)), UserRole::cases()),
                        UserRole::cases()
                    ),
                    'choice_label' => fn(UserRole $role) => ucfirst(strtolower($role->name)),
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
                        array_map(fn($status) => ucfirst(strtolower($status->name)), Status::cases()),
                        Status::cases()
                    ),
                    'choice_label' => fn(Status $status) => ucfirst(strtolower($status->name)),
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
