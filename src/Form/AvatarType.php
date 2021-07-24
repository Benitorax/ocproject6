<?php

namespace App\Form;

use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use App\Event\Subscriber\AvatarFormSubscriber;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', ImageType::class)
            ->add('delete', SubmitType::class, ['label' => 'Delete'])
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->addEventSubscriber(new AvatarFormSubscriber())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
