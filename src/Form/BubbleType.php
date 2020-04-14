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
          ->add('persona', HiddenType::class)
          ->add('x', HiddenType::class)
          ->add('y', HiddenType::class)
          ->add('orientation', HiddenType::class)
          ->get('persona')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => Bubble::class,
        ]);
    }

}