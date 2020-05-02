<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraintsOptions = [
            'message' => 'security.current_password.invalid',
        ];

        if (!empty($options['validation_groups'])) {
            $constraintsOptions['groups'] = [
                reset($options['validation_groups'])
            ];
        }

        $builder->add('current_password', PasswordType::class, [
            'constraints' => [
                new NotBlank(),
                new UserPassword($constraintsOptions),
            ],
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'attr' => [
                'autocomplete' => 'current-password',
            ],
        ]);

        $builder->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'options' => array(
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'autocomplete' => 'new-password',
                ),
            ),
            'first_options' => ['label' => 'form.new_password'],
            'second_options' =>['label' => 'form.new_password_confirmation'],
            'invalid_message' => 'fos_user.password.mismatch',
        ));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_token_id' => 'change_password',
        ]);
    }

}
