<?php

namespace App\Form\Type;

use App\Entity\Client\SellerCompany;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\NotNull;

class SellerCompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isFullForm = $options["isFullForm"];

        $builder
            ->add('isSeller', CheckboxType::class, [
                'required' => false,
                'label' => "Авторазборка, магазин, продавец<br>(товары, запчасти, тюнинг)",
            ])
            ->add('isService', CheckboxType::class, [
                'required' => false,
                'label' => "СТО, автосервис, шиномонтаж<br>(услиги, ремонт, обслуживание)",
            ])
            ->add('isNews', CheckboxType::class, [
                'required' => false,
                'label' => "Новости, блоги, статьи",
            ])
            ->add('unp', TextType::class, [
                'required' => true,
                'label' => false,
                'constraints' => new NotNull(['message' =>'Заполните поле']),
            ])
            ->add('companyName', TextType::class, [
                'required' => true,
                'label' => false,
                'constraints' => new NotNull(['message' =>'Заполните поле']),
            ])
            ->add('address', TextType::class, [
                'required' => true,
                'label' => false,
                'constraints' => new NotNull(['message' =>'Заполните поле']),
            ])
            ->add('workflow', SellerCompanyWorkflowType::class, ["isFullForm" => $isFullForm])
            ->add('submit', SubmitType::class, [])
        ;

        if($isFullForm){
            $builder
                ->add('activityDescription', TextareaType::class, ['required' => false])
                ->add('additionalPhone', TextType::class, [
                    'label' => "Дополнительный телефон:",
                    'required' => false,
                ])
            ;
        }
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SellerCompany::class,
            'validation_groups' => [],
            'isFullForm' => false,
        ]);
    }
}