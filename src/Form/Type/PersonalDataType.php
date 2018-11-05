<?php

namespace App\Form\Type;

use App\Entity\Client\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class PersonalDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => "Имя",
                'constraints' => new NotBlank(['message' =>'Заполните поле']),
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'label' => "Телефон",
                'constraints' => new NotBlank(['message' =>'Заполните поле']),
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => "E-mail",
                'constraints' => [
                    new NotBlank(['message' =>'Заполните поле']),
                    new Email(['message' =>'Некорректный email'])
                ],
            ])
            ->add('country', TextType::class, [
                'required' => true,
                'label' => "Страна",
                'data' => "Беларусь",
                'attr' => [
                    'class' => "first-part",
                ]
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'label' => "Город",
                'attr' => [
                    'class' => "first-part",
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Подтвердить",
                'attr' => [
                    "class" => "btn-filter"
                ]
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'validation_groups' => [],
        ]);
    }
}