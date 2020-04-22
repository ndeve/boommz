<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonaCreatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('persona', HiddenType::class, ['empty_data' => '/persona/creator/women/0000.png'])
          ->add('hair', HiddenType::class)
          ->add('eyes', HiddenType::class)
          ->add('nose', HiddenType::class)
          ->add('mouth', HiddenType::class)
          ->add('hat', HiddenType::class)
          ->add('trousers', HiddenType::class)
          ->add('top', HiddenType::class)
          ->add('vest', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
