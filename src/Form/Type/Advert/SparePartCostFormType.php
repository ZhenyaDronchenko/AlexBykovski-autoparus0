<?php

namespace App\Form\Type\Advert;

use App\Entity\General\EmailDomain;
use App\Entity\SparePart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SparePartCostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', HiddenType::class, [
                'label' => false,
            ])
            ->add('id', HiddenType::class, [
                'label' => false,
            ])
            ->add('isChecked', CheckboxType::class, [
                'required' => false,
                'label' => false,
            ])
            ->add('cost', TextType::class, [
                'required' => false,
                'label' => "BYN (Бел Руб)",
                'attr' => [
                    'placeholder' => "Цена",
                ],
                'mapped' => false,
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