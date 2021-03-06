<?php

namespace App\Form;

use App\Form\ImageType;
use App\Form\VideoType;
use App\Entity\Category;
use App\Entity\SnowboardTrick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Event\Subscriber\SnowboardTrickFormSubscriber;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SnowboardTrickType extends AbstractType
{
    private SnowboardTrickFormSubscriber $subscriber;

    public function __construct(SnowboardTrickFormSubscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('category', ChoiceType::class, [
                'choices'  => $this->getChoices(),
                'choice_attr' => [
                    'Select a category' => [
                        'readonly' => true,
                        'hidden' => true
                    ],
                ],
            ])
            ->add('hasIllustration', CheckboxType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('illustration', ImageType::class, [
                'required' => false
            ])
            ->add('images', CollectionType::class, [
                'label' => 'Images',
                'entry_type' => ImageType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->add('videos', CollectionType::class, [
                'label' => 'Videos',
                'entry_type' => VideoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->addEventSubscriber($this->subscriber)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SnowboardTrick::class,
        ]);
    }

    /**
     * Return an array of categories with select option at first key.
     */
    private function getChoices(): array
    {
        return array_merge(
            ['Select a category' => ''],
            Category::$categories
        );
    }
}
