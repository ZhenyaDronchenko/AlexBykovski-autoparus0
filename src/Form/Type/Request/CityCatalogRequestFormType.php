<?php

namespace App\Form\Type\Advert;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Request\CityCatalogRequest;
use App\Form\DataTransformer\IdToBrandTransformer;
use App\Form\DataTransformer\IdToDriveTypeTransformer;
use App\Form\DataTransformer\IdToGearBoxTypeTransformer;
use App\Form\DataTransformer\IdToModelTransformer;
use App\Form\DataTransformer\IdToVehicleTypeTransformer;
use App\Form\Type\Request\SparePartRequestFormType;
use App\Provider\Form\SparePartAdvertDataProvider;
use App\Type\AutoSetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\NotNull;

class CityCatalogRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('sparePartRequests', CollectionType::class, [
                'label' => false,
                'entry_type' => SparePartRequestFormType::class,
                //'data' => $spareParts,
                'allow_add' => true,
                'allow_delete' => false,
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Введите адрес электронной почты']),
                    new Email(['message' => 'Неверный формат']),
                ],
            ])
            ->add('phoneBY', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Введите телефон']),
                ],
            ])
            ->add('phoneRU', TextType::class, [
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
            ->add('submit', SubmitType::class, [
                'label' => "Отправить заказ-заявку"
            ])
            ->add('submitPasswords', SubmitType::class, [
                'label' => "Подтвердить"
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