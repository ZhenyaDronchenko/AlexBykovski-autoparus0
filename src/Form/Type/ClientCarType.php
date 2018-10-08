<?php

namespace App\Form\Type;

use App\Entity\Client\UserCar;
use App\Form\DataTransformer\NameToBrandTransformer;
use App\Form\DataTransformer\NameToModelTransformer;
use App\Form\DataTransformer\TypeToEngineTypeTransformer;
use App\Form\DataTransformer\TypeToVehicleTypeTransformer;
use App\Provider\Form\ClientCarProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientCarType extends AbstractType
{
    /** @var ClientCarProvider */
    private $provider;

    /** @var NameToBrandTransformer */
    private $brandTransformer;

    /** @var NameToModelTransformer */
    private $modelTransformer;

    /** @var TypeToVehicleTypeTransformer */
    private $vehicleTypeTransformer;

    /** @var TypeToEngineTypeTransformer */
    private $engineTypeTransformer;

    /**
     * ClientCarType constructor.
     * @param ClientCarProvider $provider
     * @param NameToBrandTransformer $brandTransformer
     * @param NameToModelTransformer $modelTransformer
     * @param TypeToVehicleTypeTransformer $vehicleTypeTransformer
     * @param TypeToEngineTypeTransformer $engineTypeTransformer
     */
    public function __construct(
        ClientCarProvider $provider,
        NameToBrandTransformer $brandTransformer,
        NameToModelTransformer $modelTransformer,
        TypeToVehicleTypeTransformer $vehicleTypeTransformer,
        TypeToEngineTypeTransformer $engineTypeTransformer
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

        $builder
            ->add('brand', ChoiceType::class, [
                'label' => "Марка",
                'choices' => $this->provider->getAllBrands(),
                'attr' => ["class" => "name-select"]
            ])
            ->add('model', ChoiceType::class, [
                'label' => "Модель",
                'choices' => $this->provider->getModels($object->getBrand()),
                'attr' => ["class" => "name-select"]
            ])
            ->add('year', ChoiceType::class, [
                'label' => "Год",
                'choices' => $this->provider->getYears($object->getModel()),
                'attr' => ["class" => "name-select"]
            ])
            ->add('vehicle', ChoiceType::class, [
                'label' => "Тип Кузова",
                'choices' => $this->provider->getVehicleTypes($object->getModel()),
                'attr' => ["class" => "name-select"]
            ])
            ->add('engineType', ChoiceType::class, [
                'label' => "Тип ДВС",
                'choices' => $this->provider->getEngineTypes($object->getModel()),
                'attr' => ["class" => "name-select"]
            ])
            ->add('capacity', ChoiceType::class, [
                'label' => "Объём",
                'choices' => $this->provider->getCapacities($object->getModel(), $object->getEngineType()),
                'attr' => ["class" => "name-select"]
            ]);

//        if(!array_key_exists("client_cars", $_POST)) {
//            $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($builder) {
//                /** @var UserCar $object */
//                $object = $event->getData() ?: new UserCar();
//                $form = $event->getForm();
//
//                $form
//                    ->add('brand', ChoiceType::class, [
//                        'label' => "Марка",
//                        'choices' => $this->provider->getAllBrands(),
//                        'attr' => ["class" => "name-select"]
//                    ])
//                    ->add('model', ChoiceType::class, [
//                        'label' => "Модель",
//                        'choices' => $this->provider->getModels($object->getBrand()),
//                        'attr' => ["class" => "name-select"]
//                    ])
//                    ->add('year', ChoiceType::class, [
//                        'label' => "Год",
//                        'choices' => $this->provider->getYears($object->getModel()),
//                        'attr' => ["class" => "name-select"]
//                    ])
//                    ->add('vehicle', ChoiceType::class, [
//                        'label' => "Тип Кузова",
//                        'choices' => $this->provider->getVehicleTypes($object->getModel()),
//                        'attr' => ["class" => "name-select"]
//                    ])
//                    ->add('engineType', ChoiceType::class, [
//                        'label' => "Тип ДВС",
//                        'choices' => $this->provider->getEngineTypes($object->getModel()),
//                        'attr' => ["class" => "name-select"]
//                    ])
//                    ->add('capacity', ChoiceType::class, [
//                        'label' => "Объём",
//                        'choices' => $this->provider->getCapacities($object->getModel(), $object->getEngineType()),
//                        'attr' => ["class" => "name-select"]
//                    ]);
//            });
//        }

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
        ]);
    }
}