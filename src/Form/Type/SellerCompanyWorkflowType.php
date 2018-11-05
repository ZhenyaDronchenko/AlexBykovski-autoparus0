<?php

namespace App\Form\Type;

use App\Entity\Client\SellerCompany;
use App\Entity\Client\SellerCompanyWorkflow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Expression;

class SellerCompanyWorkflowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isMondayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Пн",
            ])
            ->add('isTuesdayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Вт",
            ])
            ->add('isWednesdayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Ср",
            ])
            ->add('isThursdayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Чт",
            ])
            ->add('isFridayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Пт",
            ])
            ->add('isSaturdayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Сб",
            ])
            ->add('isSundayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Вс",
            ])
            ->add('weekDaysStartAt', TimeType::class, [
                'required' => false,
                'label' => "С:",
                'widget' => 'single_text',
            ])
            ->add('weekDaysEndAt', TimeType::class, [
                'required' => false,
                'label' => "_",
                'widget' => 'single_text',
            ])
            ->add('weekendStartAt', TimeType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('weekendEndAt', TimeType::class, [
                'required' => false,
                'label' => "_",
                'widget' => 'single_text',
            ])
            ->add('isCash', CheckboxType::class, [
                'required' => false,
                'label' => "Наличные деньги",
            ])
            ->add('isCashless', CheckboxType::class, [
                'required' => false,
                'label' => "Безналичный расчет",
            ])
            ->add('isCreditCard', CheckboxType::class, [
                'required' => false,
                'label' => "Кредитная карта",
            ])
            ->add('delivery', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    "Да" => true,
                    "Нет" => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SellerCompanyWorkflow::class,
            'validation_groups' => [],
        ]);
    }
}