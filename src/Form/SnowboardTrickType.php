<?php

namespace App\Form;

use App\Form\ImageType;
use App\Entity\SnowboardTrick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Event\Subscriber\SnowboardTrickFormSubscriber;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
            ->add('illustration', ImageType::class, [
                'required' => false
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->addEventSubscriber(new SnowboardTrickFormSubscriber())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SnowboardTrick::class,
        ]);
    }
}
