<?php

namespace App\Form;

use App\Entity\Bubble;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\PersonaToNumberTransformer;

class BubbleType extends AbstractType
{
    private $transformer;

    public function __construct(PersonaToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('text')
          ->add('persona', HiddenType::class, ['empty_data' => 2105])
          ->add('x', HiddenType::class, ['empty_data' => 0])
          ->add('y', HiddenType::class, ['empty_data' => 0])
          ->add('orientation', HiddenType::class, ['empty_data' => 0])
          ->add('style', HiddenType::class)
          ->get('persona')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => Bubble::class,
        ]);
    }

}
