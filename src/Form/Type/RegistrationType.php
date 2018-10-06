<?php

namespace App\Form\Type;

use App\Entity\Client\Client;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Заполните имя']),
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Введите адрес электронной почты']),
                    new Email(['message' => 'Неверный формат']),
                ],
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Введите телефон']),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'invalid_message' => 'Пароли должны совпадать',
                'type' => PasswordType::class,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Введите пароль']),
                ],
            ])
            ->add('captcha', CaptchaType::class, [
                'length' => 6,
                'width' => '400',
                'height' => '200',
                'distortion' => false
            ])
            ->add('submit', SubmitType::class, [
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'validation_groups' => [],
        ]);
    }
}