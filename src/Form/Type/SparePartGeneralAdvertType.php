<?php

namespace App\Form\Type;

use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Entity\Brand;
use App\Form\DataTransformer\IdsToModelsTransformer;
use App\Form\DataTransformer\IdsToSparePartsTransformer;
use App\Form\DataTransformer\IdToBrandTransformer;
use App\Provider\Form\AutoAdvertDataProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class SparePartGeneralAdvertType extends AbstractType
{
    /** @var AutoAdvertDataProvider */
    private $provider;

    /** @var IdToBrandTransformer */
    private $brandTransformer;

    /** @var IdsToModelsTransformer */
    private $modelsTransformer;

    /** @var IdsToSparePartsTransformer */
    private $sparePartsTransformer;

    /**
     * SparePartGeneralAdvertType constructor.
     * @param AutoAdvertDataProvider $provider
     * @param IdToBrandTransformer $brandTransformer
     * @param IdsToModelsTransformer $modelsTransformer
     * @param IdsToSparePartsTransformer $sparePartsTransformer
     */
    public function __construct(
        AutoAdvertDataProvider $provider,
        IdToBrandTransformer $brandTransformer,
        IdsToModelsTransformer $modelsTransformer,
        IdsToSparePartsTransformer $sparePartsTransformer
    )
    {
        $this->provider = $provider;
        $this->brandTransformer = $brandTransformer;
        $this->modelsTransformer = $modelsTransformer;
        $this->sparePartsTransformer = $sparePartsTransformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var AutoSparePartGeneralAdvert $advert */
        $advert = $builder->getData();

        $usedBrands = $advert->getSellerAdvertDetail()->getAutoSparePartGeneralAdvertsBrands(true);

        $isAllUsed = in_array(null, $usedBrands);

        if(!$advert->isBrandAdded() && $advert->getId() && $isAllUsed || $advert->isBrandAdded() && !$advert->getBrand()){
            $isAllUsed = false;
        }

        if($advert->isBrandAdded() && $advert->getBrand()){
            if (($key = array_search($advert->getBrand()->getId(), $usedBrands)) !== false) {
                unset($usedBrands[$key]);
            }
        }

        $brand = $options["brand"] ? $options["brand"] : $advert->getBrand();

        $choicesBrand = $this->provider->getBrands($usedBrands, $isAllUsed);

        if(!$brand && count($choicesBrand)){
            $brand = $this->provider->getBrandById(array_values($choicesBrand)[0]);
            $advert->setBrand($brand);
        }

        $builder
            ->add('condition', ChoiceType::class, [
                'required' => false,
                'choices' => array_flip(AutoSparePartGeneralAdvert::CONDITIONS_FORM),
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
            ])
            ->add('stockType', ChoiceType::class, [
                'required' => false,
                'choices' => array_flip(AutoSparePartGeneralAdvert::STOCK_TYPES_FORM),
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
            ])
            ->add('brand', ChoiceType::class, [
                'label' => false,
                'choices' => $choicesBrand,
                'help' => $brand ? $brand->getBrandEn() : null
            ])
            ->add('models', ChoiceType::class, [
                'label' => false,
                'choices' => $this->provider->getModels($brand),
                'expanded' => true,
                'multiple' => true,
                'empty_data' => false,
            ])
            ->add('spareParts', ChoiceType::class, [
                'label' => false,
                'choices' => $this->provider->getSpareParts(),
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
            ])
            ->add('submitGeneral', SubmitType::class, [
                'label' => "Подтвердить выбранное",
                'attr' => ["class" => "btn"]
            ])
            ->add('submitSparePart', SubmitType::class, [
                'label' => "Подтвердить и сохранить",
                'attr' => ["class" => "btn"]
            ])
        ;

        $builder->get('brand')->addModelTransformer($this->brandTransformer);
        $builder->get('models')->addModelTransformer($this->modelsTransformer);
        $builder->get('spareParts')->addModelTransformer($this->sparePartsTransformer);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AutoSparePartGeneralAdvert::class,
            'validation_groups' => [],
            'brand' => null,
        ]);
    }
}