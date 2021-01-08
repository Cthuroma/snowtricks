<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTrickAssetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                'by_reference' => false,
            ]);
    }
}
