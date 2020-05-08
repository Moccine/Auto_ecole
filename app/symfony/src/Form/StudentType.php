<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('firstName')
            //->add('password', PasswordType::class)
            ->add('lastName')
            ->add(
                'birthDate',
                null,
                [
                    'label' => 'security.login.birth_date',
                    'widget' => 'single_text',

                    // prevents rendering it as type="date", to avoid HTML5 date pickers
                    'html5' => false,

                    // adds a class that can be selected in JavaScript
                    'attr' => ['class' => 'js-datepicker'],
                ]
            )
            ->add('zipCode')
            ->add('phone')
            ->add('address')
            ->add('registrationNumber')
            ->add('city')
            ->add('qualification')
            ->add('photoFile', FileType::class, [
                'label' => 'Photo',
                'data_class' => null,
                'required' => false
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
