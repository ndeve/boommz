<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Box;
use App\Form\BubbleType;
use App\Entity\Background;
use App\Form\DataTransformer\BackgroundToNumberTransformer;

class BoxType extends AbstractType
{
    private $transformer;

    public function __construct(BackgroundToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('orderBox', HiddenType::class)
          ->add('size', HiddenType::class, ['empty_data' => 'one-third'])
          ->add('height', HiddenType::class, ['empty_data' => '1'])
          ->add('background', HiddenType::class)
          ->add('level', HiddenType::class)
          ->add('bubbles', CollectionType::class, [
            'entry_type' => BubbleType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
          ])
          ->get('background')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => Box::class,
        ]);
    }

}
