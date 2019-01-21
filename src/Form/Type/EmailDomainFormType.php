<?php

namespace App\Form\Type;

use App\Entity\General\EmailDomain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailDomainFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emailEnd', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => "Окончание адреса",
                    'style' => "width: 20%; float: left;",
                ]
            ])
            ->add('domain', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => "Адрес почтового домена",
                    'style' => "width: 20%; float: left; margin-left: 30px;",
                ]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EmailDomain::class,
            'validation_groups' => [],
        ]);
    }
}