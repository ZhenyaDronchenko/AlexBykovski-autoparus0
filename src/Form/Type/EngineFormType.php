<?php

namespace App\Form\Type;

use App\Entity\Engine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EngineFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => "engine-capacity-field-1"
                ]
            ])
            ->add('capacity', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => "engine-capacity-field-2",
                ]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Engine::class,
            'validation_groups' => [],
        ]);
    }
}