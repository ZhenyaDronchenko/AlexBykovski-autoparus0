<?php

namespace App\Form\Type;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Client\SellerAdvertDetail;
use App\Form\DataTransformer\IdToBrandTransformer;
use App\Form\DataTransformer\IdToDriveTypeTransformer;
use App\Form\DataTransformer\IdToGearBoxTypeTransformer;
use App\Form\DataTransformer\IdToModelTransformer;
use App\Form\DataTransformer\IdToVehicleTypeTransformer;
use App\Provider\Form\SparePartAdvertDataProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class SparePartSpecificAdvertType extends AbstractType
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
        $object = $builder->getData();

        $object = $object instanceof AutoSparePartSpecificAdvert ? $object : new AutoSparePartSpecificAdvert(new SellerAdvertDetail());

        $isFormSubmitted = $options["isFormSubmitted"];

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
                'choices' => $this->provider->getModels($object->getBrand(), true),
                'constraints' => [
                    new NotNull(['message' => 'Выберите модель']),
                ],
            ])
            ->add('year', ChoiceType::class, [
                'label' => "Год",
                'choices' => $this->provider->getYears($object->getModel(), true),
                'constraints' => [
                    new NotNull(['message' => 'Выберите год']),
                ],
            ])
            ->add('sparePart', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotNull(['message' => 'Выберите запчасть']),
                ],
            ])
            ->add('engineType', ChoiceType::class, [
                'label' => "Тип Двигателя",
                'choices' => $this->provider->getEngineTypes($object->getModel(), true),
            ])
            ->add('engineCapacity', ChoiceType::class, [
                'label' => "Объем",
                'choices' => $this->provider->getEngineCapacities($object->getModel(), $object->getEngineType(), true),
            ])
            ->add('engineName', ChoiceType::class, [
                'label' => "Марка двигателя",
                'choices' => $this->provider->getEngineNames($object->getModel(), $object->getEngineType(), null, true),
            ])
            ->add('gearBoxType', ChoiceType::class, [
                'label' => "Тип КПП",
                'choices' => $this->provider->getGearBoxTypes($object->getModel(), true),
            ])
            ->add('vehicleType', ChoiceType::class, [
                'label' => "Тип Кузова",
                'choices' => $this->provider->getVehicleTypes($object->getModel(), true),
            ])
            ->add('driveType', ChoiceType::class, [
                'label' => "Тип Привода",
                'choices' => $this->provider->getDriveTypes($object->getModel(), true),
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
            ->add('sparePartNumber', TextType::class, [
                'label' => "Номер запчасти",
            ])
            ->add('comment', TextareaType::class, [
                'label' => "Описание, комментарий:",
            ])
            ->add('image', HiddenType::class, [
                'label' => "Добавить изображение:",
            ])
            ->add('cost', TextType::class, [
                'label' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Добавить объявление"
            ])
            ->add('submitAdd', SubmitType::class, [
                'label' => "Добавить и продолжить добавление"
            ])
            ->add('submitAutoContinue', SubmitType::class, [
                'label' => "Добавить и продолжить добавление с этим авто"
            ])
            ->add('submitSparePartContinue', SubmitType::class, [
                'label' => "Добавить и продолжить добавление с этой запчастью"
            ])
            ->add('submitButtonName', HiddenType::class, [
                'mapped' => false,
            ]);

        if(!$isFormSubmitted) {
            $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($builder) {
                /** @var AutoSparePartSpecificAdvert $object */
                $object = $event->getData() ?: new AutoSparePartSpecificAdvert(new SellerAdvertDetail());
                $form = $event->getForm();

                $form
                    ->add('model', ChoiceType::class, [
                        'label' => "Модель",
                        'choices' => $this->provider->getModels($object->getBrand()),
                        'constraints' => [
                            new NotNull(['message' => 'Выберите модель']),
                        ],
                    ])
                    ->add('year', ChoiceType::class, [
                        'label' => "Год",
                        'choices' => $this->provider->getYears($object->getModel()),
                        'constraints' => [
                            new NotNull(['message' => 'Выберите год']),
                        ],
                    ])
                    ->add('engineType', ChoiceType::class, [
                        'label' => "Тип Двигателя",
                        'choices' => $this->provider->getEngineTypes($object->getModel()),
                    ])
                    ->add('engineCapacity', ChoiceType::class, [
                        'label' => "Объем Двигателя",
                        'choices' => $this->provider->getEngineCapacities($object->getModel(), $object->getEngineType()),
                    ])
                    ->add('engineName', ChoiceType::class, [
                        'label' => "Марка двигателя",
                        'choices' => $this->provider->getEngineNames($object->getModel(), $object->getEngineType(), null),
                    ])
                    ->add('gearBoxType', ChoiceType::class, [
                        'label' => "Тип КПП",
                        'choices' => $this->provider->getGearBoxTypes($object->getModel()),
                    ])
                    ->add('vehicleType', ChoiceType::class, [
                        'label' => "Тип Кузова",
                        'choices' => $this->provider->getVehicleTypes($object->getModel()),
                    ])
                    ->add('driveType', ChoiceType::class, [
                        'label' => "Тип Привода",
                        'choices' => $this->provider->getDriveTypes($object->getModel()),
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
            'data_class' => AutoSparePartSpecificAdvert::class,
            'validation_groups' => [],
            'isFormSubmitted' => false,
        ]);
    }
}