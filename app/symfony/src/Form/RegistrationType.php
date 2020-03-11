<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
            ->remove('username')
            ->add('firstName', null, [
                'label' => 'security.login.first_name',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2]),
                ],
            ])
            ->add('lastName', null, [
                'label' => 'security.login.last_name',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2]),
                ],
            ])
            ->add('birthDate', DateType::class, [
                'label' => 'security.login.birth_date',
                'widget' => 'single_text',

                // prevents rendering it as type="date", to avoid HTML5 date pickers
                'html5' => false,

                // adds a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('zipCode', null, [
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
                'label' => 'security.login.phone'
            ])
            ->add('city', null, [
                'label' => 'security.login.city',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 3,
                    ]),
                ],
            ])
            ->add('email', null, [
                'label' => 'security.login.email',
                'constraints' => [
                    new NotBlank(),
                    new Email(['strict' => true]),
                ],

            ])
            ->add('address', null, [
                'label' => 'security.login.address'
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'security.login.password',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 8, 'max' => 12]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}
