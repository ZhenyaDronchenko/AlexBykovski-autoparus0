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
                'attr' => ['class' => "visually-hidden filter-input checkbox-custom"],
            ])
            ->add('isTuesdayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Вт",
                'attr' => ['class' => "visually-hidden filter-input checkbox-custom"],
            ])
            ->add('isWednesdayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Ср",
                'attr' => ['class' => "visually-hidden filter-input checkbox-custom"],
            ])
            ->add('isThursdayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Чт",
                'attr' => ['class' => "visually-hidden filter-input checkbox-custom"],
            ])
            ->add('isFridayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Пт",
                'attr' => ['class' => "visually-hidden filter-input checkbox-custom"],
            ])
            ->add('isSaturdayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Сб",
                'attr' => ['class' => "visually-hidden filter-input checkbox-custom"],
            ])
            ->add('isSundayWork', CheckboxType::class, [
                'required' => false,
                'label' => "Вс",
                'attr' => ['class' => "visually-hidden filter-input checkbox-custom"],
            ])
            ->add('weekDaysStartAt', TimeType::class, [
                'required' => false,
                'label' => "С:",
                'attr' => ['class' => "time_work"],
                'widget' => 'single_text',
            ])
            ->add('weekDaysEndAt', TimeType::class, [
                'required' => false,
                'label' => "По:",
                'attr' => ['class' => "time_work"],
                'widget' => 'single_text',
            ])
            ->add('weekendStartAt', TimeType::class, [
                'required' => false,
                'label' => "С:",
                'attr' => ['class' => "time_work"],
                'widget' => 'single_text',
            ])
            ->add('weekendEndAt', TimeType::class, [
                'required' => false,
                'label' => "По:",
                'attr' => ['class' => "time_work"],
                'widget' => 'single_text',
            ])
            ->add('isCash', CheckboxType::class, [
                'required' => false,
                'label' => "Наличные деньги",
                'attr' => ['class' => "visually-hidden filter-input filter-input-checkbox"],
            ])
            ->add('isCashless', CheckboxType::class, [
                'required' => false,
                'label' => "Безналичный расчет",
                'attr' => ['class' => "visually-hidden filter-input filter-input-checkbox"],
            ])
            ->add('isCreditCard', CheckboxType::class, [
                'required' => false,
                'label' => "Кредитная карта",
                'attr' => ['class' => "visually-hidden filter-input filter-input-checkbox"],
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