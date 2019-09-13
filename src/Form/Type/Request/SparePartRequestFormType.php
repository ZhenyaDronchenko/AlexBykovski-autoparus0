<?php

namespace App\Form\Type\Request;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Request\SparePartRequest;
use App\Form\DataTransformer\IdToBrandTransformer;
use App\Form\DataTransformer\IdToDriveTypeTransformer;
use App\Form\DataTransformer\IdToGearBoxTypeTransformer;
use App\Form\DataTransformer\IdToModelTransformer;
use App\Form\DataTransformer\IdToVehicleTypeTransformer;
use App\Form\DataTransformer\NameToSparePartTransformer;
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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\NotNull;

class SparePartRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sparePartNumber', TextType::class, [])
            ->add('comment', TextareaType::class, [
                "attr" => [
                    "rows" => 5
                ]
            ])
            ->add('sparePartText', TextType::class, [
                "mapped" => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SparePartRequest::class,
            'validation_groups' => [],
        ]);
    }
}