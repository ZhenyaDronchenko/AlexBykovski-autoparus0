<?php

namespace App\Form\Type\Request;

use App\Entity\Request\CityCatalogRequest;
use App\Form\DataTransformer\NameToSparePartTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class CityCatalogRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $cityCatalogRequest = $builder->getData();

        $builder
            ->add('sparePartRequests', CollectionType::class, [
                'label' => false,
                'entry_type' => SparePartRequestFormType::class,
                //'data' => $spareParts,
                'allow_add' => true,
                'allow_delete' => false,
                'prototype_name' => '__index__',
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => $cityCatalogRequest->getClient() ? [] : [
                    new NotBlank(['message' => 'Введите адрес электронной почты']),
                    new Email(['message' => 'Неверный формат']),
                ],
            ])
            ->add('phoneBY', TextType::class, [
                'required' => true,
            ])
            ->add('phoneRU', TextType::class, [
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Отправить заказ-заявку"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CityCatalogRequest::class,
            'validation_groups' => [],
        ]);
    }
}