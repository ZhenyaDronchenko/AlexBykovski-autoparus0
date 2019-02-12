<?php

namespace App\Form\Type;

use App\Entity\Client\UserCar;
use App\Form\DataTransformer\IdToBrandTransformer;
use App\Form\DataTransformer\IdToDriveTypeTransformer;
use App\Form\DataTransformer\IdToGearBoxTypeTransformer;
use App\Form\DataTransformer\IdToModelTransformer;
use App\Form\DataTransformer\IdToEngineTypeTransformer;
use App\Form\DataTransformer\IdToVehicleTypeTransformer;
use App\Provider\Form\ClientCarProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ClientCarType extends AbstractType
{
    /** @var ClientCarProvider */
    private $provider;

    /** @var IdToBrandTransformer */
    private $brandTransformer;

    /** @var IdToModelTransformer */
    private $modelTransformer;

    /** @var IdToVehicleTypeTransformer */
    private $vehicleTypeTransformer;

    /** @var IdToEngineTypeTransformer */
    private $engineTypeTransformer;

    /** @var IdToDriveTypeTransformer */
    private $driveTypeTransformer;

    /** @var IdToGearBoxTypeTransformer */
    private $gearBoxTypeTransformer;

    /**
     * ClientCarType constructor.
     * @param ClientCarProvider $provider
     * @param IdToBrandTransformer $brandTransformer
     * @param IdToModelTransformer $modelTransformer
     * @param IdToVehicleTypeTransformer $vehicleTypeTransformer
     * @param IdToEngineTypeTransformer $engineTypeTransformer
     * @param IdToDriveTypeTransformer $driveTypeTransformer
     * @param IdToGearBoxTypeTransformer $gearBoxTypeTransformer
     */
    public function __construct(
        ClientCarProvider $provider,
        IdToBrandTransformer $brandTransformer,
        IdToModelTransformer $modelTransformer,
        IdToVehicleTypeTransformer $vehicleTypeTransformer,
        IdToEngineTypeTransformer $engineTypeTransformer,
        IdToDriveTypeTransformer $driveTypeTransformer,
        IdToGearBoxTypeTransformer $gearBoxTypeTransformer
    )
    {
        $this->provider = $provider;
        $this->brandTransformer = $brandTransformer;
        $this->modelTransformer = $modelTransformer;
        $this->vehicleTypeTransformer = $vehicleTypeTransformer;
        $this->engineTypeTransformer = $engineTypeTransformer;
        $this->driveTypeTransformer = $driveTypeTransformer;
        $this->gearBoxTypeTransformer = $gearBoxTypeTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $object = $builder->getData();

        $object = $object instanceof UserCar ? $object : new UserCar();

        $isFormSubmitted = $options["isFormSubmitted"];
        $engineType = $object->getEngineType() ? $object->getEngineType()->getType() : null;

        $builder
            ->add('brand', ChoiceType::class, [
                'label' => "Марка",
                'choices' => $this->provider->getAllBrands(),
                'constraints' => [
                    new NotNull(['message' => 'Выберите марку']),
                    new NotBlank(['message' => 'Выберите марку']),
                ],
            ])
            ->add('model', ChoiceType::class, [
                'label' => "Модель",
                'choices' => $this->provider->getModels($object->getBrand(), true),
            ])
            ->add('year', ChoiceType::class, [
                'label' => "Год",
                'choices' => $this->provider->getYears($object->getModel(), true),
            ])
            ->add('vehicle', ChoiceType::class, [
                'label' => "Тип Кузова",
                'choices' => $this->provider->getVehicleTypes($object->getModel(), true),
            ])
            ->add('engineType', ChoiceType::class, [
                'label' => "Тип ДВС",
                'choices' => $this->provider->getEngineTypes($object->getModel(), true),
            ])
            ->add('capacity', ChoiceType::class, [
                'label' => "Объём",
                'choices' => $this->provider->getCapacities($object->getModel(), $object->getEngineType(), true),
            ])
            ->add('engineName', ChoiceType::class, [
                'label' => "Марка двигателя",
                'choices' => $this->provider->getEngineNames($object->getModel(), $engineType, null, true),
            ])
            ->add('gearBoxType', ChoiceType::class, [
                'label' => "Тип КПП",
                'choices' => $this->provider->getGearBoxTypes($object->getModel(), true),
            ])
            ->add('driveType', ChoiceType::class, [
                'label' => "Тип Привода",
                'choices' => $this->provider->getDriveTypes($object->getModel(), true),
            ])
        ;

        if(!$isFormSubmitted) {
            $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($builder) {
                /** @var UserCar $object */
                $object = $event->getData() ?: new UserCar();
                $form = $event->getForm();

                $engineType = $object->getEngineType() ? $object->getEngineType()->getType() : null;

                $form
                    ->add('brand', ChoiceType::class, [
                        'label' => "Марка",
                        'choices' => $this->provider->getAllBrands(),
                        'constraints' => [
                            new NotNull(['message' => 'Выберите марку']),
                            new NotBlank(['message' => 'Выберите марку']),
                        ],
                    ])
                    ->add('model', ChoiceType::class, [
                        'label' => "Модель",
                        'choices' => $this->provider->getModels($object->getBrand()),
                    ])
                    ->add('year', ChoiceType::class, [
                        'label' => "Год",
                        'choices' => $this->provider->getYears($object->getModel()),
                    ])
                    ->add('vehicle', ChoiceType::class, [
                        'label' => "Тип Кузова",
                        'choices' => $this->provider->getVehicleTypes($object->getModel()),
                        'attr' => ["class" => "name-select car-form-choice-vehicle"]
                    ])
                    ->add('engineType', ChoiceType::class, [
                        'label' => "Тип ДВС",
                        'choices' => $this->provider->getEngineTypes($object->getModel()),
                        'attr' => ["class" => "name-select car-form-choice-engine-type"]
                    ])
                    ->add('capacity', ChoiceType::class, [
                        'label' => "Объём",
                        'choices' => $this->provider->getCapacities($object->getModel(), $object->getEngineType()),
                        'attr' => ["class" => "name-select car-form-choice-capacity"]
                    ])
                    ->add('engineName', ChoiceType::class, [
                        'label' => "Марка двигателя",
                        'choices' => $this->provider->getEngineNames($object->getModel(), $engineType, null),
                    ])
                    ->add('gearBoxType', ChoiceType::class, [
                        'label' => "Тип КПП",
                        'choices' => $this->provider->getGearBoxTypes($object->getModel()),
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
        $builder->get('vehicle')->addModelTransformer($this->vehicleTypeTransformer);
        $builder->get('engineType')->addModelTransformer($this->engineTypeTransformer);
        $builder->get('gearBoxType')->addModelTransformer($this->gearBoxTypeTransformer);
        $builder->get('driveType')->addModelTransformer($this->driveTypeTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserCar::class,
            'validation_groups' => [],
            'isFormSubmitted' => false,
        ]);
    }
}