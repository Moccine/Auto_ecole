<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\MettingPoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'address',
                null,
                [
                'label' => 'location.form.address'
            ]
            )
            ->add('postalCode', null, [
                'label' => 'location.form.postal_code'
            ])
            ->add('city', null, [
                'label' => 'location.form.city'
            ])
            ->add('activated', null, [
                'label' => 'location.form.activated',
                'attr' => [
                    'checked' => 'checked'
                ]
            ])
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MettingPoint::class,
        ]);
    }
}
