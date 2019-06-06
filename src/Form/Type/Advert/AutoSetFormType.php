<?php

namespace App\Form\Type\Advert;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Form\DataTransformer\IdToBrandTransformer;
use App\Form\DataTransformer\IdToDriveTypeTransformer;
use App\Form\DataTransformer\IdToGearBoxTypeTransformer;
use App\Form\DataTransformer\IdToModelTransformer;
use App\Form\DataTransformer\IdToVehicleTypeTransformer;
use App\Provider\Form\SparePartAdvertDataProvider;
use App\Type\AutoSetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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

class AutoSetFormType extends AbstractType
{
    /** @var SparePartAdvertDataProvider */
    private $provider;

    /** @var IdToBrandTransformer */
    private $brandTransformer;

    /** @var IdToModelTransformer */
    private $modelTransformer;

    /** @var IdToVehicleTypeTransformer */
    private $vehicleTypeTransformer;

    /** @var IdToDriveTypeTransformer */
    private $driveTypeTransformer;

    /** @var IdToGearBoxTypeTransformer */
    private $gearBoxTypeTransformer;

    /**
     * SparePartSpecificAdvertType constructor.
     *
     * @param SparePartAdvertDataProvider $provider
     * @param IdToBrandTransformer $brandTransformer
     * @param IdToModelTransformer $modelTransformer
     * @param IdToVehicleTypeTransformer $vehicleTypeTransformer
     * @param IdToDriveTypeTransformer $driveTypeTransformer
     * @param IdToGearBoxTypeTransformer $gearBoxTypeTransformer
     */
    public function __construct(
        SparePartAdvertDataProvider $provider,
        IdToBrandTransformer $brandTransformer,
        IdToModelTransformer $modelTransformer,
        IdToVehicleTypeTransformer $vehicleTypeTransformer,
        IdToDriveTypeTransformer $driveTypeTransformer,
        IdToGearBoxTypeTransformer $gearBoxTypeTransformer
    )
    {
        $this->provider = $provider;
        $this->brandTransformer = $brandTransformer;
        $this->modelTransformer = $modelTransformer;
        $this->vehicleTypeTransformer = $vehicleTypeTransformer;
        $this->driveTypeTransformer = $driveTypeTransformer;
        $this->gearBoxTypeTransformer = $gearBoxTypeTransformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isFormSubmitted = $options["isFormSubmitted"];
        $spareParts = $this->provider->getSparePartsForAutoSet();

        $builder
            ->add('brand', ChoiceType::class, [
                'label' => "Марка",
                'choices' => $this->provider->getAllBrands(),
                'constraints' => [
                    new NotNull(['message' => 'Выберите марку']),
                ],
            ])
            ->add('model', ChoiceType::class, [
                'label' => "Модель",
                'choices' => $this->provider->getModels(null, true),
            ])
            ->add('year', ChoiceType::class, [
                'label' => "Год",
                'choices' => $this->provider->getYears(null, true),
            ])
            ->add('engineType', ChoiceType::class, [
                'label' => "Тип Двигателя",
                'choices' => $this->provider->getEngineTypes(null, true),
            ])
            ->add('engineCapacity', ChoiceType::class, [
                'label' => "Объем Двигателя",
                'choices' => $this->provider->getEngineCapacities(null, null, true),
            ])
            ->add('engineName', ChoiceType::class, [
                'label' => "Марка двигателя",
                'choices' => $this->provider->getEngineNames(null, null, null, true),
            ])
            ->add('engineNameEmpty', TextType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false,
            ])
            ->add('gearBoxType', ChoiceType::class, [
                'label' => "Тип КПП",
                'choices' => $this->provider->getGearBoxTypes(null, true),
            ])
            ->add('vehicleType', ChoiceType::class, [
                'label' => "Тип Кузова",
                'choices' => $this->provider->getVehicleTypes(null, true),
            ])
            ->add('driveType', ChoiceType::class, [
                'label' => "Тип Привода",
                'choices' => $this->provider->getDriveTypes(null, true),
            ])
            ->add('condition', ChoiceType::class, [
                'label' => "Состояние",
                'choices' => array_flip(AutoSparePartSpecificAdvert::CONDITIONS_FORM),
                'expanded' => true,
                'constraints' => [
                    new NotNull(['message' => 'Выберите состояние']),
                ],
            ])
            ->add('stockType', ChoiceType::class, [
                'label' => "Наличие",
                'choices' => array_flip(AutoSparePartSpecificAdvert::STOCK_TYPES_FORM),
                'expanded' => true,
                'constraints' => [
                    new NotNull(['message' => 'Выберите наличие']),
                ],
            ])
            ->add('comment', TextareaType::class, [
                'label' => "Описание:",
                'constraints' => [
                    new NotBlank(['message' => 'Введите описание']),
                ],
            ])
            ->add('image', HiddenType::class, [
                'label' => "Добавить изображение:",
            ])
            ->add('spareParts', CollectionType::class, [
                'label' => false,
                'entry_type' => SparePartCostFormType::class,
                'data' => $spareParts,
                'allow_add' => false,
                'allow_delete' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Добавить выбранные позиции"
            ])
            ->add('submitContinue', SubmitType::class, [
                'label' => "Добавить и продолжить добавление с выбранными позициями запчастей"
            ])
            ->add('submitButtonName', HiddenType::class, [
                'mapped' => false,
            ]);
        ;

        if(!$isFormSubmitted) {
            $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($builder, $spareParts) {
                /** @var AutoSetType $object */
                $object = $event->getData();
                $form = $event->getForm();
                $brand = $object->getBrand();
                $model = $object->getModel();
                $engineType = $object->getEngineType();
                $years = $this->provider->getYears($model);

                $form
                    ->add('model', ChoiceType::class, [
                        'label' => "Модель",
                        'choices' => $this->provider->getModels($object->getBrand()),
                        'constraints' => !$brand ? [] :[
                            new NotNull(['message' => 'Выберите модель']),
                            new NotBlank(['message' => 'Выберите модель']),
                        ],
                    ])
                    ->add('year', ChoiceType::class, [
                        'label' => "Год",
                        'choices' => $years,
                        'constraints' => !$model ? [] : [
                            new NotNull(['message' => 'Выберите год']),
                            new NotEqualTo(['value' => 0, 'message' => 'Выберите год']),
                        ],
                    ])
                    ->add('engineType', ChoiceType::class, [
                        'label' => "Тип Двигателя",
                        'choices' => $this->provider->getEngineTypes($object->getModel()),
                        'constraints' => !$model ? [] : [
                            new NotNull(['message' => 'Выберите тип двигателя']),
                            new NotBlank(['message' => 'Выберите тип двигателя']),
                        ],
                    ])
                    ->add('engineCapacity', ChoiceType::class, [
                        'label' => "Объем Двигателя",
                        'choices' => $this->provider->getEngineCapacities($object->getModel(), $object->getEngineType()),
                        'constraints' => !$engineType ? [] : [
                            new NotNull(['message' => 'Выберите объём']),
                            new NotBlank(['message' => 'Выберите объём']),
                        ],
                    ])
                    ->add('engineName', ChoiceType::class, [
                        'label' => "Марка двигателя",
                        'choices' => $this->provider->getEngineNames($object->getModel(), $object->getEngineType(), null),
                    ])
                    ->add('gearBoxType', ChoiceType::class, [
                        'label' => "Тип КПП",
                        'choices' => $this->provider->getGearBoxTypes($object->getModel()),
                        'constraints' => !$model ? [] : [
                            new NotNull(['message' => 'Выберите КПП']),
                            new NotBlank(['message' => 'Выберите КПП']),

                        ],
                    ])
                    ->add('vehicleType', ChoiceType::class, [
                        'label' => "Тип Кузова",
                        'choices' => $this->provider->getVehicleTypes($object->getModel()),
                        'constraints' => !$model ? [] : [
                            new NotNull(['message' => 'Выберите Тип кузова']),
                            new NotBlank(['message' => 'Выберите Тип кузова']),
                        ],
                    ])
                    ->add('driveType', ChoiceType::class, [
                        'label' => "Тип Привода",
                        'choices' => $this->provider->getDriveTypes($object->getModel()),
                        'constraints' => !$model ? [] : [
                            new NotNull(['message' => 'Выберите Тип привода']),
                            new NotBlank(['message' => 'Выберите Тип привода']),
                        ],
                    ])
                    ->add('spareParts', CollectionType::class, [
                        'label' => false,
                        'entry_type' => SparePartCostFormType::class,
                        'data' => $object->getSpareParts() && count($object->getSpareParts()) ? $object->getSpareParts() : $spareParts,
                        'allow_add' => false,
                        'allow_delete' => false,
                    ])
                ;
            });
        }

        $builder->get('brand')->addModelTransformer($this->brandTransformer);
        $builder->get('model')->addModelTransformer($this->modelTransformer);
        $builder->get('gearBoxType')->addModelTransformer($this->gearBoxTypeTransformer);
        $builder->get('vehicleType')->addModelTransformer($this->vehicleTypeTransformer);
        $builder->get('driveType')->addModelTransformer($this->driveTypeTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AutoSetType::class,
            'validation_groups' => [],
            'isFormSubmitted' => false,
        ]);
    }
}