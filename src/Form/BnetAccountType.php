<?php

namespace App\Form;

use App\Entity\Bnet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class BnetAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('acctUsername', TextType::class, [
                'label' => 'Username',
                'constraints' => [
                    new NotBlank(['message' => 'Username is required']),
                    new Length([
                        'min' => 3,
                        'max' => 32,
                        'minMessage' => 'Username must be at least {{ limit }} characters',
                        'maxMessage' => 'Username cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Password is required']),
                    new Length([
                        'min' => 6,
                        'max' => 64,
                        'minMessage' => 'Password must be at least {{ limit }} characters',
                        'maxMessage' => 'Password cannot be longer than {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('verifyPassword', PasswordType::class, [
                'label' => 'Verify Password',
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please verify your password']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bnet::class,
        ]);
    }
}
