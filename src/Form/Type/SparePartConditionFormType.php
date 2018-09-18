<?php

namespace App\Form\Type;

use App\Entity\SparePartCondition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SparePartConditionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            /** @var SparePartCondition $object */
            $object = $event->getData();
            $form = $event->getForm();

            $form
                ->add('isActive', CheckboxType::class, [
                    'label' => $object->getDescription(),
                    'attr' => [
                        "class" => "spare-part-checkboxes-after-label"
                    ],
                ])
                ->add('description', HiddenType::class, [
                    'label' => false,
                ])
                ->add('spCondition', TextType::class, [
                    'required' => true,
                    'label' => false,
                    'attr' => [
                        "class" => "simple-width-field"
                    ]
                ])
                ->add('singleAdjective', TextType::class, [
                    'required' => true,
                    'label' => "Какой (какая, какое)? [SINGLE_ZAP_CONDITION]",
                    'label_attr' => [
                        "style" => $object->getDescription() != SparePartCondition::USED_DESCRIPTION ? "opacity: 0" : "",
                    ],
                    'attr' => [
                        "class" => "simple-width-field"
                    ]
                ])
                ->add('pluralAdjective', TextType::class, [
                    'required' => true,
                    'label' => "Какие? [PLURAL_ZAP_CONDITION]",
                    'label_attr' => [
                        "style" => $object->getDescription() != SparePartCondition::USED_DESCRIPTION ? "opacity: 0" : "",
                    ],
                    'attr' => [
                        "class" => "simple-width-field"
                    ]
                ]);
        });
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SparePartCondition::class,
            'validation_groups' => [],
        ]);
    }
}