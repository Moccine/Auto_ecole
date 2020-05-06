<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'data' => 'Username',
                'label' => 'security.login.first_name',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2]),
                ],
            ])
            ->add('firstName', null, [
                'data' => 'firstName',
                'label' => 'security.login.first_name',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2]),
                ],
            ])
            ->add('lastName', null, [
                'data' => 'lastName',
                'label' => 'security.login.last_name',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2]),
                ],
            ])
            ->add('birthDate', DateType::class, [
                'data' => new \DateTime(),
                'label' => 'security.login.birth_date',
                'widget' => 'single_text',
                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,
                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('zipCode', null, [
                'data' => '92700',
                'label' => 'security.login.zip_code',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'max' => 5,
                    ]),
                ],
            ])
            ->add('phone', null, [
                'data' => '0770186892',
                'label' => 'security.login.phone'
            ])
            ->add('city', null, [
                'data' => 'Colombes',
                'label' => 'security.login.city',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3,
                    ]),
                ],
            ])
            ->add('email', null, [
                'data' => sprintf('mo%d@gmail.com', rand(1, 100)),
                'label' => 'security.login.email',
                'constraints' => [
                    new NotBlank(),
                    new Email(['strict' => true]),
                ],
            ])
            ->add('address', null, [
                'data' => '1 rue toto',
                'label' => 'security.login.address'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => ['attr' =>
                    ['autocomplete' => 'new-password']
                ],
                'first_options' => ['label' => 'form.password'],
                'second_options' => ['label' => 'form.password_confirmation'],
                'invalid_message' => 'form.password.mismatch',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }


    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}
