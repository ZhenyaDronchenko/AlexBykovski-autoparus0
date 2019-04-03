<?php

namespace App\Form\Type\Admin;

use App\Entity\General\MainPageAction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MainPageActionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => "Имя",
            ])
            ->add('link', TextType::class, [
                'required' => true,
                'label' => "Ссылка",
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MainPageAction::class,
            'validation_groups' => [],
        ]);
    }
}