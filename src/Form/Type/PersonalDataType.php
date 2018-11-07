<?php

namespace App\Form\Type;

use App\Entity\City;
use App\Entity\Client\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class PersonalDataType extends AbstractType
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * PersonalDataType constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Client $client */
        $client = $builder->getData();

        $city = $client->getCity() ?: ($client->isSeller() ? $client->getSellerData()->getSellerCompany()->getCity() : null);
        $country = $client->getCountry() ?: "Беларусь";

        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => "Имя",
                'constraints' => new NotBlank(['message' =>'Заполните поле']),
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'label' => "Телефон",
                'constraints' => new NotBlank(['message' =>'Заполните поле']),
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => "E-mail",
                'constraints' => [
                    new NotBlank(['message' =>'Заполните поле']),
                    new Email(['message' =>'Некорректный email'])
                ],
            ])
            ->add('country', TextType::class, [
                'required' => true,
                'label' => "Страна",
                'data' => $country,
                'attr' => [
                    'class' => "first-part",
                ]
            ])
            ->add('city', ChoiceType::class, [
                'required' => true,
                'label' => "Город",
                'attr' => [
                    'class' => "first-part",
                ],
                'choices' => $this->getCitiesChoices(),
                'constraints' => new NotNull(['message' =>'Выберите город']),
                'data' => $city
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Подтвердить",
                'attr' => [
                    "class" => "btn-filter"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'validation_groups' => [],
        ]);
    }

    public function getCitiesChoices()
    {
        $capital = $this->em->getRepository(City::class)->findOneBy(["type" => City::CAPITAL_TYPE]);
        $regionalCities = $this->em->getRepository(City::class)->findBy(["type" => City::REGIONAL_CITY_TYPE], ["name" => "ASC"]);
        $othersCities = $this->em->getRepository(City::class)->findBy(["type" => City::OTHERS_TYPE], ["name" => "ASC"]);

        $cityChoices = ["Город" => null];

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