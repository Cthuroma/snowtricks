<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Entity\Group;
use Symfony\Component\Validator\Constraints\Count;

class TrickFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')

            ->add('description')

            ->add('category', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
                'choice_value' => 'id'
            ])

            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'label' => false,
                'entry_options' => ['label' => false],
                'error_bubbling' => false,
                'allow_add' => true,
                'by_reference' => false
            ])

            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'label' => false,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false
            ])

            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'error_mapping' => ['images' => 'images'],
        ]);
    }
}
