<?php

namespace App\Form\Type;

use App\Entity\Client\SellerCompany;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\NotNull;

class SellerCompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isSeller', CheckboxType::class, [
                'required' => false,
                'label' => "Авторазборка, магазин, продавец<br>(товары, запчасти, тюнинг)",
                'attr' => ['class' => "visually-hidden filter-input filter-input-checkbox"],
            ])
            ->add('isService', CheckboxType::class, [
                'required' => false,
                'label' => "СТО, автосервис, шиномонтаж<br>(услиги, ремонт, обслуживание)",
                'attr' => ['class' => "visually-hidden filter-input filter-input-checkbox"],
            ])
            ->add('isNews', CheckboxType::class, [
                'required' => false,
                'label' => "Новости, блоги, статьи",
                'attr' => ['class' => "visually-hidden filter-input filter-input-checkbox"],
            ])
            ->add('unp', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => ['class' => "choice-input choice-unp",],
                'constraints' => new NotNull(['message' =>'Заполните поле']),
            ])
            ->add('companyName', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => ['class' => "choice-input",],
                'constraints' => new NotNull(['message' =>'Заполните поле']),
            ])
            ->add('address', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => ['class' => "choice-input",],
                'constraints' => new NotNull(['message' =>'Заполните поле']),
            ])
            ->add('workflow', SellerCompanyWorkflowType::class)
            ->add('submit', SubmitType::class, [
                'label' => "Сохранить",
                'attr' => [
                    "class" => "btn-filter"
                ]
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SellerCompany::class,
            'validation_groups' => []
        ]);
    }
}