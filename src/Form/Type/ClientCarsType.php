<?php

namespace App\Form\Type;

use App\Entity\Client\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ClientCarsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cars', CollectionType::class, [
                'label' => false,
                'entry_type' => ClientCarType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype_name' => '__index__',
                'by_reference' => false,
                'entry_options'  => [
                    'isFormSubmitted' => $options["isFormSubmitted"],
                ],
            ])
            ->add('submit', SubmitType::class, [])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'validation_groups' => [],
            'isFormSubmitted' => false,
        ]);
    }
}