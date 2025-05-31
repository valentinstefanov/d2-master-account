<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ChangePasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Current Password',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your current password']),
                ],
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'New Password',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a new password']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 64,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirm New Password',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please confirm your new password']),
                ],
            ])
        ;
    }
}
