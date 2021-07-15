<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\IdenticalTo;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [new IdenticalTo([
                    'value' => $options['username'],
                    'message' => 'The username must be identical to yours'
                ])]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [new Length([
                    'min' => 6,
                    'max' => 100,
                    'minMessage' => "Your username must be at least {{ limit }} characters long.",
                    'maxMessage' => "Your username cannot be longer than {{ limit }} characters."
                ])]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'username' => null,
            ])
            ->setRequired('username')
            ->setAllowedTypes('username', 'string');
    }
}
