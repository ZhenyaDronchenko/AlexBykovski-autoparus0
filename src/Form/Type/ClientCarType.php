<?php

namespace App\Form\Type;

use App\Entity\Client\UserCar;
use App\Form\DataTransformer\IdToBrandTransformer;
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

    /**
     * ClientCarType constructor.
     * @param ClientCarProvider $provider
     * @param IdToBrandTransformer $brandTransformer
     * @param IdToModelTransformer $modelTransformer
     * @param IdToVehicleTypeTransformer $vehicleTypeTransformer
     * @param IdToEngineTypeTransformer $engineTypeTransformer
     */
    public function __construct(
        ClientCarProvider $provider,
        IdToBrandTransformer $brandTransformer,
        IdToModelTransformer $modelTransformer,
        IdToVehicleTypeTransformer $vehicleTypeTransformer,
        IdToEngineTypeTransformer $engineTypeTransformer
    )
    {
        $this->provider = $provider;
        $this->brandTransformer = $brandTransformer;
        $this->modelTransformer = $modelTransformer;
        $this->vehicleTypeTransformer = $vehicleTypeTransformer;
        $this->engineTypeTransformer = $engineTypeTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $object = $builder->getData();

        $object = $object instanceof UserCar ? $object : new UserCar();

        $isFormSubmitted = $options["isFormSubmitted"];

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
            ]);

        if(!$isFormSubmitted) {
            $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($builder) {
                /** @var UserCar $object */
                $object = $event->getData() ?: new UserCar();
                $form = $event->getForm();

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
                    ]);
            });
        }

        $builder->get('brand')->addModelTransformer($this->brandTransformer);
        $builder->get('model')->addModelTransformer($this->modelTransformer);
        $builder->get('vehicle')->addModelTransformer($this->vehicleTypeTransformer);
        $builder->get('engineType')->addModelTransformer($this->engineTypeTransformer);
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