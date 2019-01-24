<?php

namespace App\Form\Type;

use App\Entity\Client\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Введите данные']),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Введите пароль']),
                ],
            ])
            ->add('rememberMe', CheckboxType::class, [
                'label' => 'Запомнить пароль',
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Войти",
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'validation_groups' => [],
        ]);
    }
}