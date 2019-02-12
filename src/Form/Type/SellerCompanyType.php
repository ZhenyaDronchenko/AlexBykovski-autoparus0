<?php

namespace App\Form\Type;

use App\Entity\City;
use App\Entity\Client\SellerCompany;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class SellerCompanyType extends AbstractType
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * SellerCompanyType constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var SellerCompany $sellerCompany */
        $sellerCompany = $builder->getData();

        $city = $sellerCompany->getCity() ?: ($sellerCompany->getSellerData() ? $sellerCompany->getSellerData()->getClient()->getCity() : null);

        $builder
            ->add('isSparePartSeller', CheckboxType::class, [
                'required' => false,
                'label' => "Автозапчасти, авторазборка, магазин запчастей для авто, тюнинг, товары для автомобилей.",
            ])
            ->add('isService', CheckboxType::class, [
                'required' => false,
                'label' => "СТО, автосервис, шиномонтаж, услиги, ремонт, обслуживание автомобилей.",
            ])
            ->add('isAutoSeller', CheckboxType::class, [
                'required' => false,
                'label' => "Продажа автомобилей, других транспортных средств, спецтехника.",
            ])
            ->add('isNews', CheckboxType::class, [
                'required' => false,
                'label' => "Новости, блоги, статьи.",
            ])
            ->add('unp', TextType::class, [
                'required' => true,
                'label' => false,
                'constraints' => new NotNull(['message' =>'Заполните поле']),
            ])
            ->add('companyName', TextType::class, [
                'required' => true,
                'label' => false,
                'constraints' => new NotNull(['message' =>'Заполните поле']),
            ])
            ->add('city', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => $this->getCitiesChoices(),
                'data' => $city,
                'constraints' => new NotNull(['message' =>'Выберите город']),
            ])
            ->add('address', TextType::class, [
                'required' => true,
                'label' => false,
                'constraints' => new NotNull(['message' =>'Укажите адрес']),
            ])
            ->add('workflow', SellerCompanyWorkflowType::class)
            ->add('activityDescription', TextareaType::class, ['required' => false])
            ->add('additionalPhone', TextType::class, [
                'label' => "Номера телефонов для связи",
                'required' => false,
            ])
            ->add('additionalPhone2', TextType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('additionalPhone3', TextType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SellerCompany::class,
            'validation_groups' => [],
        ]);
    }

    public function getCitiesChoices()
    {
        $capital = $this->em->getRepository(City::class)->findOneBy(["type" => City::CAPITAL_TYPE]);
        $regionalCities = $this->em->getRepository(City::class)->findBy(["type" => City::REGIONAL_CITY_TYPE], ["name" => "ASC"]);
        $othersCities = $this->em->getRepository(City::class)->findBy(["type" => City::OTHERS_TYPE], ["name" => "ASC"]);

        $cityChoices = ["Выберите город" => null];

        if($capital instanceof City){
            $cityChoices[$capital->getName()] = $capital->getName();
        }

        /** @var City $city */
        foreach ($regionalCities as $city){
            $cityChoices[$city->getName()] = $city->getName();
        }

        /** @var City $city */
        foreach ($othersCities as $city){
            $cityChoices[$city->getName()] = $city->getName();
        }

        return $cityChoices;
    }
}