<?php

namespace App\Form;

use App\Entity\Shop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'shop.name',
                    'required' => true,
                ]
            )
            ->add('hour', MoneyType::class, [
                'label' => 'shop.hour',
                'required' => false,

            ])
            ->add('price', MoneyType::class, [
                'label' => 'shop.price',
                'required' => true,

            ])
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'shop.description',
                    'required' => false,

                ]
            )
            ->add('priority', IntegerType::class, [
                'label' => 'shop.priority',
                'required' => true,

            ])
            ->add('courseNumber', IntegerType::class, [
                'label' => 'shop.course_number',
                'required' => true,

            ])
            ->add(
                'complementDescription',
                TextareaType::class,
                [
                    'label' => 'shop.complementDescription',
                    'required' => false,

                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Shop::class,
        ]);
    }
}
