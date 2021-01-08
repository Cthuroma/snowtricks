<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', TextType::class, ([
                'constraints' => [
                    new Regex([
                        'pattern' => '/(?:https?:\/\/)?(?:www\.)?youtu(be\.com\/embed\/)(\S{11})/',
                        'message' => 'Please link a YouTube video !'
                    ])
                ],
                'label' => 'Youtube Link'
            ]))
            ->add('description');

        $builder->get('url')
            ->addModelTransformer(new CallbackTransformer(
                  function ($extractedSrc) {
                      return $extractedSrc;
                  },
                  function ($iframe) {
                      if(strpos($iframe, 'iframe')){
                          preg_match('/src="([^"]+)"/', $iframe, $match);
                          dump($iframe,$match);
                          return $match[1]??$iframe;
                      }else if(strpos($iframe, 'watch?v=')){
                          return str_replace('watch?v=', 'embed/', $iframe);
                      }else if(strpos($iframe, '//youtu.be/')){
                          return str_replace('//youtu.be/', '//www.youtube.com/embed/', $iframe);
                      }
                      return $iframe;
                  }
              ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
