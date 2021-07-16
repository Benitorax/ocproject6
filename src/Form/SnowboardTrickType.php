<?php

namespace App\Form;

use App\Entity\SnowboardTrick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SnowboardTrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('category', ChoiceType::class, [
                'choices'  => [
                    'Select a category' => 0,
                    'Straight airs' => 1,
                    'Grabs' => 2,
                    'Spins' => 3,
                    'Flips and inverted rotations' => 4,
                    'Inverted hand plants' => 5,
                    'Slides' => 6,
                    'Stalls' => 7,
                    'Tweaks and variations' => 8,
                    'Miscellaneous tricks and identifiers' => 9,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SnowboardTrick::class,
        ]);
    }
}
