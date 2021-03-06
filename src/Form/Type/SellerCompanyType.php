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
                'label' => "Авто-мото запчасти <span>(Авторазборки, магазины и точки запчастей б/у и новых)</span>",
            ])
            ->add('isService', CheckboxType::class, [
                'required' => false,
                'label' => "Услуги / СТО / Сервисы <span>(Авто-мото сервисы, мастерские, станции тех. обслуживания)</span>",
            ])
            ->add('isAutoSeller', CheckboxType::class, [
                'required' => false,
                'label' => "Авто-мото транспорт <span>(Продавцы авто и мото транспорта, стоянки продажи авто)</span>",
            ])
            ->add('isNews', CheckboxType::class, [
                'required' => false,
                'label' => "Новости / Статьи <span>(Статьи о Вашей компании, автосервисе, магазине, турагенстве)</span>",
            ])
            ->add('isTourism', CheckboxType::class, [
                'required' => false,
                'label' => "Туризм / Путешествия <span>(Туристические компании, турагенства, агроусадьбы, гиды)</span>",
            ])
            ->add('unp', TextType::class, [
                'required' => true,
                'label' => false,
                'constraints' => new NotNull(['message' =>'Заполните УНП']),
            ])
            ->add('companyName', TextType::class, [
                'required' => true,
                'label' => false,
                'constraints' => new NotNull(['message' =>'Заполните данные']),
            ])
            ->add('city', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => $this->getCitiesChoices(),
                'data' => $city,
                'constraints' => new NotNull(['message' =>'Сделайте выбор']),
            ])
            ->add('address', TextType::class, [
                'required' => true,
                'label' => false,
                'constraints' => new NotNull(['message' =>'Заполните поле адреса']),
            ])
            ->add('siteAddress', TextType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('linkImportAdverts', TextType::class, [
                'label' => false,
                'required' => false,
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