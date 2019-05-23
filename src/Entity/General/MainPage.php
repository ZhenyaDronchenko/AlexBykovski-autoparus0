<?php

namespace App\Entity\General;

use App\Entity\Brand;
use App\Entity\Client\Client;
use App\Type\PostsFilterType;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="main_page")
 */
class MainPage
{
    const HOMEPAGE_FILTER_ALL_USERS_ROUTE = "homepage_all_users";

    const USER_FILTER_ROUTE = "homepage_filter_user";
    const USER_FILTER_ALL_USERS_ROUTE = "homepage_filter_user_all_users";

    const BRAND_FILTER_ROUTE = "homepage_filter_brand";
    const BRAND_MODEL_FILTER_ROUTE = "homepage_filter_brand_model";
    const BRAND_FILTER_ALL_USERS_ROUTE = "homepage_filter_brand_all_users";
    const BRAND_MODEL_FILTER_ALL_USERS_ROUTE = "homepage_filter_brand_model_all_users";

    const CITY_ACTIVITY_FILTER_ROUTE = "homepage_filter_city_activity";
    const CITY_ACTIVITY_FILTER_ALL_USERS_ROUTE = "homepage_filter_city_activity_all_users";



    const HOMEPAGE_FILTER_ALL_USERS_TITLE = "Автопарус.бай - общая полная лента";

    const USER_FILTER_TITLE = "%s, %s %s %s %s";
    const USER_FILTER_ALL_USERS_TITLE = "%s, %s %s %s %s, общее";
    const USER_FILTER_GENERAL_TITLE = "Автопарус - Движение - Жизнь";
    const USER_FILTER_ALL_USERS_GENERAL_TITLE = "Автопарус - Движение - Жизнь. Общее";

    const BRAND_FILTER_TITLE = "Автопарус.бай - лента статей %s";
    const BRAND_MODEL_FILTER_TITLE = "Автопарус.бай - лента статей %s %s";
    const BRAND_FILTER_ALL_USERS_TITLE = "Автопарус.бай - общая полная лента %s";
    const BRAND_MODEL_FILTER_ALL_USERS_TITLE = "%s %s, всё интересное вокруг %s %s";
    const BRAND_FILTER_GENERAL_TITLE = "Автопарус.бай - лента статей";
    const BRAND_FILTER_ALL_USERS_GENERAL_TITLE = "Автопарус.бай - общая полная лента автомобилей";

    const CITY_ACTIVITY_FILTER_TITLE = "Автопарус.by %s в %s";
    const CITY_ACTIVITY_FILTER_ALL_USERS_TITLE = "Автопарус.by %s в %s, общая информация";

    const CITY_FILTER_TITLE = "Автопарус - предложения в %s";
    const CITY_FILTER_ALL_USERS_TITLE = "Автопарус - все предложения в %s";

    const CITY_FILTER_GENERAL_TITLE = "Автопарус - предложения";
    const CITY_FILTER_ALL_USERS_GENERAL_TITLE = "Автопарус - все предложения";



    const HOMEPAGE_FILTER_ALL_USERS_DESCRIPTION = "Приглашаем на наш сайт - полностью посвященный автотематике и помощи автомобилистам и тем кто в дороге и путешествии.";

    const USER_FILTER_DESCRIPTION = "Страница пользователя %s %s, %s, %s %s";
    const USER_FILTER_ALL_USERS_DESCRIPTION = "Страница пользователя %s %s, %s";
    const USER_FILTER_GENERAL_DESCRIPTION = "Автопарус - место где можно рассказать о своем авто и о путешествиях, интересное от наших пользователей.";
    const USER_FILTER_ALL_USERS_GENERAL_DESCRIPTION = "Автопарус - место где можно рассказать о своем авто и о путешествиях, интересное от всех наших пользователей.";

    const BRAND_FILTER_DESCRIPTION = "Autoparus.by - лента статей об автомобилях %s, а также интересные и выгодные предложения по марке %s";
    const BRAND_MODEL_FILTER_DESCRIPTION = "Лента статей о %s %s, а также интересные и выгодные предложения по марке %s";
    const BRAND_FILTER_ALL_USERS_DESCRIPTION = "Приветствуем владельцев автомобилей %s и просто интересующихся маркой %s - на сайте полностью посвященном автомобильной тематике и тем кто в дороге или путешествии.";
    const BRAND_MODEL_FILTER_ALL_USERS_DESCRIPTION = "Приглашаем владельцев автомобилей %s %s и другого транспорта - на наш сайт об авто, мото, вело тематике и всевозможных путешествиях.";
    const BRAND_FILTER_GENERAL_DESCRIPTION = "Autoparus.by - лента статей об автомобилях, а также интересные и выгодные предложения";
    const BRAND_FILTER_ALL_USERS_GENERAL_DESCRIPTION = "Приветствуем владельцев автомобилей - на сайте полностью посвященном автомобильной тематике и тем кто в дороге или в путешествии.";

    const CITY_ACTIVITY_FILTER_DESCRIPTION = "Интересные предложения от автокомпаний города %s, %s.";
    const CITY_ACTIVITY_FILTER_ALL_USERS_DESCRIPTION = "Интересные предложения от автокомпаний города %s, %s, общая информация";

    const CITY_FILTER_DESCRIPTION = "Интересные предложения от автокомпаний города %s.";
    const CITY_FILTER_ALL_USERS_DESCRIPTION = "Интересные предложения от всех автокомпаний города %s.";

    const CITY_FILTER_GENERAL_DESCRIPTION = "Интересные предложения от автокомпаний";
    const CITY_FILTER_ALL_USERS_GENERAL_DESCRIPTION = "Интересные предложения от всех автокомпаний";

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $middleText;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $middleLink;

    /**
     * @var Collection
     *
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\General\MainPageAction", mappedBy="mainPage", cascade={"persist", "remove"})
     */
    private $actions;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return null|string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param null|string $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return null|string
     */
    public function getMiddleText(): ?string
    {
        return $this->middleText;
    }

    /**
     * @param null|string $middleText
     */
    public function setMiddleText(?string $middleText): void
    {
        $this->middleText = $middleText;
    }

    /**
     * @return null|string
     */
    public function getMiddleLink(): ?string
    {
        return $this->middleLink;
    }

    /**
     * @param null|string $middleLink
     */
    public function setMiddleLink(?string $middleLink): void
    {
        $this->middleLink = $middleLink;
    }

    /**
     * @return Collection
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    /**
     * @param Collection $actions
     */
    public function setActions(Collection $actions): void
    {
        $this->actions = $actions;
    }

    public function setFilteredTitle($route, PostsFilterType $filter)
    {
        switch ($route){
            case self::HOMEPAGE_FILTER_ALL_USERS_ROUTE:
                $this->title = self::HOMEPAGE_FILTER_ALL_USERS_TITLE;

                break;
            case self::USER_FILTER_ROUTE:
                $this->setUserFilterTitle($filter);

                break;
            case self::USER_FILTER_ALL_USERS_ROUTE:
                if(!($filter->getUsers() instanceof Client)){
                    $this->title = sprintf(self::USER_FILTER_ALL_USERS_GENERAL_TITLE);

                    break;
                }

                $parameters = $this->getParametersUserFilterRoute($filter);

                $this->title = sprintf(self::USER_FILTER_ALL_USERS_TITLE, $parameters["city"], $parameters["name"],
                    $parameters["id"], $parameters["unp"], $parameters["companyName"]);

                break;
            case self::BRAND_MODEL_FILTER_ROUTE:
                $brand = $filter->getBrand() ? $filter->getBrand()->getName() : "";
                $model = $filter->getModel() ? $filter->getModel()->getName() : "";

                $this->title = sprintf(self::BRAND_MODEL_FILTER_TITLE, $brand, $model);

                break;
            case self::BRAND_FILTER_ROUTE:
                if(!($filter->getBrand() instanceof Brand)){
                    $this->title = sprintf(self::BRAND_FILTER_GENERAL_TITLE);

                    break;
                }

                $brand = $filter->getBrand() ? $filter->getBrand()->getName() : "";

                $this->title = sprintf(self::BRAND_FILTER_TITLE, $brand);

                break;
            case self::BRAND_MODEL_FILTER_ALL_USERS_ROUTE:
                $brand = $filter->getBrand() ? $filter->getBrand()->getName() : "";
                $ruBrand = $filter->getBrand() ? $filter->getBrand()->getBrandRu() : "";
                $model = $filter->getModel() ? $filter->getModel()->getName() : "";
                $ruModel = $filter->getModel() ? $filter->getModel()->getModelRu() : "";

                $this->title = sprintf(self::BRAND_MODEL_FILTER_ALL_USERS_TITLE, $brand, $model, $ruBrand, $ruModel);

                break;
            case self::BRAND_FILTER_ALL_USERS_ROUTE:
                if(!($filter->getBrand() instanceof Brand)){
                    $this->title = sprintf(self::BRAND_FILTER_ALL_USERS_GENERAL_TITLE);

                    break;
                }

                $brand = $filter->getBrand() ? $filter->getBrand()->getName() : "";

                $this->title = sprintf(self::BRAND_FILTER_ALL_USERS_TITLE, $brand);

                break;
            case self::CITY_ACTIVITY_FILTER_ROUTE:
                if(!$filter->getActivity() && !$filter->getCity()){
                    $this->title = sprintf(self::CITY_FILTER_GENERAL_TITLE);

                    break;
                }
                elseif (!$filter->getActivity() && $filter->getCity()){
                    $inCity = $filter->getCity() ? $filter->getCity()->getPrepositional() : "";

                    $this->title = sprintf(self::CITY_FILTER_TITLE, $inCity);

                    break;
                }

                $inCity = $filter->getCity() ? $filter->getCity()->getPrepositional() : "";
                $activity = $filter->getActivity() ? $filter->getActivity() : "";

                $this->title = sprintf(self::CITY_ACTIVITY_FILTER_TITLE, $activity, $inCity);

                break;
            case self::CITY_ACTIVITY_FILTER_ALL_USERS_ROUTE:
                if(!$filter->getActivity() && !$filter->getCity()){
                    $this->title = sprintf(self::CITY_FILTER_ALL_USERS_GENERAL_TITLE);

                    break;
                }
                elseif (!$filter->getActivity() && $filter->getCity()){
                    $inCity = $filter->getCity() ? $filter->getCity()->getPrepositional() : "";

                    $this->title = sprintf(self::CITY_FILTER_ALL_USERS_TITLE, $inCity);

                    break;
                }

                $inCity = $filter->getCity() ? $filter->getCity()->getPrepositional() : "";
                $activity = $filter->getActivity() ? $filter->getActivity() : "";

                $this->title = sprintf(self::CITY_ACTIVITY_FILTER_ALL_USERS_TITLE, $activity, $inCity);

                break;
            default:
                break;
        }
    }

    public function setFilteredDescription($route, PostsFilterType $filter)
    {
        switch ($route){
            case self::HOMEPAGE_FILTER_ALL_USERS_ROUTE:
                $this->description = self::HOMEPAGE_FILTER_ALL_USERS_DESCRIPTION;

                break;
            case self::USER_FILTER_ROUTE:
                $this->setUserFilterDescription($filter);

                break;
            case self::USER_FILTER_ALL_USERS_ROUTE:
                if(!($filter->getUsers() instanceof Client)){
                    $this->description = sprintf(self::USER_FILTER_ALL_USERS_GENERAL_DESCRIPTION);

                    break;
                }

                $parameters = $this->getParametersUserFilterRoute($filter);

                $this->description = sprintf(self::USER_FILTER_ALL_USERS_DESCRIPTION, $parameters["name"],
                    $parameters["id"], $parameters["city"]);

                break;
            case self::BRAND_MODEL_FILTER_ROUTE:
                $enBrand = $filter->getBrand() ? $filter->getBrand()->getBrandEn() : "";
                $ruBrand = $filter->getBrand() ? $filter->getBrand()->getBrandRu() : "";
                $enModel = $filter->getModel() ? $filter->getModel()->getModelEn() : "";

                $this->description = sprintf(self::BRAND_MODEL_FILTER_DESCRIPTION, $enBrand, $enModel, $ruBrand);

                break;
            case self::BRAND_FILTER_ROUTE:
                if(!($filter->getBrand() instanceof Brand)){
                    $this->description = sprintf(self::BRAND_FILTER_GENERAL_DESCRIPTION);

                    break;
                }

                $brand = $filter->getBrand() ? $filter->getBrand()->getName() : "";
                $ruBrand = $filter->getBrand() ? $filter->getBrand()->getBrandRu() : "";

                $this->description = sprintf(self::BRAND_FILTER_DESCRIPTION, $brand, $ruBrand);

                break;
            case self::BRAND_MODEL_FILTER_ALL_USERS_ROUTE:
                $enBrand = $filter->getBrand() ? $filter->getBrand()->getBrandEn() : "";
                $enModel = $filter->getModel() ? $filter->getModel()->getModelEn() : "";

                $this->description = sprintf(self::BRAND_MODEL_FILTER_ALL_USERS_DESCRIPTION, $enBrand, $enModel);

                break;
            case self::BRAND_FILTER_ALL_USERS_ROUTE:
                if(!($filter->getBrand() instanceof Brand)){
                    $this->description = sprintf(self::BRAND_FILTER_ALL_USERS_GENERAL_DESCRIPTION);

                    break;
                }

                $brand = $filter->getBrand() ? $filter->getBrand()->getName() : "";
                $ruBrand = $filter->getBrand() ? $filter->getBrand()->getBrandRu() : "";

                $this->description = sprintf(self::BRAND_FILTER_ALL_USERS_DESCRIPTION, $brand, $ruBrand);

                break;
            case self::CITY_ACTIVITY_FILTER_ROUTE:
                if(!$filter->getActivity() && !$filter->getCity()){
                    $this->description = sprintf(self::CITY_FILTER_GENERAL_DESCRIPTION);

                    break;
                }
                elseif (!$filter->getActivity() && $filter->getCity()){
                    $city = $filter->getCity() ? $filter->getCity()->getName() : "";

                    $this->description = sprintf(self::CITY_FILTER_DESCRIPTION, $city);

                    break;
                }

                $city = $filter->getCity() ? $filter->getCity()->getName() : "";
                $activity = $filter->getActivity() ? $filter->getActivity() : "";

                $this->description = sprintf(self::CITY_ACTIVITY_FILTER_DESCRIPTION, $city, $activity);

                break;
            case self::CITY_ACTIVITY_FILTER_ALL_USERS_ROUTE:
                if(!$filter->getActivity() && !$filter->getCity()){
                    $this->description = sprintf(self::CITY_FILTER_ALL_USERS_GENERAL_DESCRIPTION);

                    break;
                }
                elseif (!$filter->getActivity() && $filter->getCity()){
                    $city = $filter->getCity() ? $filter->getCity()->getName() : "";

                    $this->description = sprintf(self::CITY_FILTER_ALL_USERS_DESCRIPTION, $city);

                    break;
                }

                $city = $filter->getCity() ? $filter->getCity()->getName() : "";
                $activity = $filter->getActivity() ? $filter->getActivity() : "";

                $this->description = sprintf(self::CITY_ACTIVITY_FILTER_ALL_USERS_DESCRIPTION, $city, $activity);

                break;
            default:
                break;
        }
    }

    private function getParametersUserFilterRoute(PostsFilterType $filter)
    {
        $user = $filter->getUsers() instanceof Client ? $filter->getUsers() : null;
        $companyName = $user ?
            ($user->getSellerData() && $user->getSellerData()->getSellerCompany() &&
            $user->getSellerData()->getSellerCompany()->getCompanyName() ?
                $user->getSellerData()->getSellerCompany()->getCompanyName() : $user->getName()) : "";
        $unp = $user && $user->getSellerData() && $user->getSellerData()->getSellerCompany() &&
        $user->getSellerData()->getSellerCompany()->getUnp() ?
            $user->getSellerData()->getSellerCompany()->getUnp() : "";
        $id = $user ? $user->getId() : "";
        $city = $user ? $user->getCity() : "";
        $name = $user ? $user->getName() : "";

        return [
            "companyName" => $companyName,
            "unp" => $unp,
            "id" => $id,
            "city" => $city,
            "name" => $name,
        ];
    }

    private function setUserFilterTitle(PostsFilterType $filter)
    {
        if(!($filter->getUsers() instanceof Client)){
            $this->title = sprintf(self::USER_FILTER_GENERAL_TITLE);
        }
        else {
            $parameters = $this->getParametersUserFilterRoute($filter);

            $this->title = sprintf(self::USER_FILTER_TITLE, $parameters["city"], $parameters["name"],
                $parameters["id"], $parameters["unp"], $parameters["companyName"]);
        }
    }

    private function setUserFilterDescription(PostsFilterType $filter)
    {
        if(!($filter->getUsers() instanceof Client)){
            $this->description = sprintf(self::USER_FILTER_GENERAL_DESCRIPTION);
        }
        else {
            $parameters = $this->getParametersUserFilterRoute($filter);

            $this->description = sprintf(self::USER_FILTER_DESCRIPTION, $parameters["name"],
                $parameters["id"], $parameters["city"], $parameters["companyName"], $parameters["unp"]);
        }
    }
}