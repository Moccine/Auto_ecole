<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->add('firstName', null, [
                'label' => 'security.login.first_name'
            ])
            ->add('lastName', null, [
                'label' => 'security.login.last_name'
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
                'label' => 'security.login.zip_code'
            ])
            ->add('phone', null, [
                'label' => 'security.login.phone'
            ])
            ->add('city', null, [
                'label' => 'security.login.city'
            ])
            ->add('email', null, [
                'label' => 'security.login.email'
            ])
            ->add('address', null, [
                'label' => 'security.login.address'
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
