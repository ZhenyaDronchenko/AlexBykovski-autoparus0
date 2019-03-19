<?php

namespace App\Form\Type;

use App\Entity\UserData\PotentialUserCheckFine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PotentialUserCheckFineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $notBlank = new NotBlank([
            "message" => "Поле обязательное для заполнения"
        ]);

        $builder
            ->add('firstName', TextType::class, ["constraints" => self::getFIOConstraints()])
            ->add('lastName', TextType::class, ["constraints" => self::getFIOConstraints()])
            ->add('patronymic', TextType::class, ["constraints" => self::getFIOConstraints()])
            ->add('series', TextType::class, ["constraints" => self::getSeriesConstraints()])
            ->add('number', TextType::class, ["constraints" => self::getNumberConstraints()])
            ->add('phone', TextType::class, ["constraints" => self::getPhoneConstraints()])
            ->add('email', EmailType::class, ["constraints" => self::getEmailConstraints()])
            ->add('submit', SubmitType::class, ["label" => "Подтвердить"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PotentialUserCheckFine::class,
            'validation_groups' => [],
        ]);
    }

    static function getNotBlankConstraint()
    {
        return new NotBlank([
            "message" => "Поле обязательное для заполнения"
        ]);
    }

    static function getFIOConstraints()
    {
        return [
            self::getNotBlankConstraint(),
            new Regex([
                "pattern" => "/^[а-яА-ЯЁё\-]+$/u",
                "message" => "Только русские буквы и \"-\""
            ])
        ];
    }

    static function getSeriesConstraints()
    {
        return [
            self::getNotBlankConstraint(),
            new Regex([
                "pattern" => "/^[А-ЯЁ]{3}$/u",
                "message" => "Серия - только 3 русские буквы"
            ])
        ];
    }

    static function getNumberConstraints()
    {
        return [
            self::getNotBlankConstraint(),
            new Regex([
                "pattern" => "/^\d{6}$/",
                "message" => "Номер - только 6 цифр"
            ])
        ];
    }

    static function getPhoneConstraints()
    {
        return [
            self::getNotBlankConstraint(),
            new Regex([
                "pattern" => "/^\+375 {2}\(\d{2}\) {2}\d{3} \- \d{2} \- \d{2}$/",
                "message" => "Некорректный номер телефона"
            ])
        ];
    }

    static function getEmailConstraints()
    {
        return [
            self::getNotBlankConstraint(),
            new Email([
                "message" => "Некорректный email"
            ])
        ];
    }
}