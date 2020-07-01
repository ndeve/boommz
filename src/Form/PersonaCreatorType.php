<?php

namespace App\Form;

use App\Form\DataTransformer\PersonaToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PersonaCreatorType extends AbstractType
{
	private $transformer;

	public function __construct(PersonaToNumberTransformer $transformer)
	{
		$this->transformer = $transformer;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('persona', HiddenType::class, ['empty_data' => '/persona/creator/women/0000.png'])
          ->add('eyes', HiddenType::class)
          ->add('nose', HiddenType::class)
          ->add('top', HiddenType::class)
          ->add('trousers', HiddenType::class)
          ->add('vest', HiddenType::class)
          ->add('starz', HiddenType::class)
          ->add('mouth', HiddenType::class)
          ->add('hair', HiddenType::class)
          ->add('hat', HiddenType::class)
	        ->get('starz')->addModelTransformer($this->transformer)
        ;
    }

	/**
	 * @param OptionsResolver $resolver
	 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
