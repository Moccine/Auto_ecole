<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminEditType extends AbstractType
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
            ->add('email')
            ->add('phone', null, [
                'data' => '0770186892',
                'label' => 'security.login.phone'
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
            ->add('qualification')
            ->add('photoFile', FileType::class, [
                'label' => 'Photo',
                'data_class' => null,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
