<?php

namespace App\Entity\General;

use App\Type\PostsFilterType;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="main_page")
 */
class MainPage
{
    const USER_FILTER_ROUTE = "homepage_filter_user";
    const BRAND_MODEL_FILTER_ROUTE = "homepage_filter_brand_model";
    const BRAND_FILTER_ROUTE = "homepage_filter_brand";
    const CITY_ACTIVITY_FILTER_ROUTE = "homepage_filter_city_activity";

    const USER_FILTER_TITLE = "Автопарус, %s %s %s";
    const BRAND_MODEL_FILTER_TITLE = "%s %s история пользователя";
    const CITY_ACTIVITY_FILTER_TITLE = "Автопарус, %s, %s";

    const USER_FILTER_DESCRIPTION = "Автопарус - свободное пространство для автолюбителей. %s %s - лента организации";
    const BRAND_MODEL_FILTER_DESCRIPTION = "Автопарус - то место, где каждый желающий может рассказать о своём авто или путешествии, задать интересующий вопрос другим пользователям. %s %s форум";
    const CITY_ACTIVITY_FILTER_DESCRIPTION = "Автопарус - свободное пространство для автолюбителей. %s, %s";

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
            case self::USER_FILTER_ROUTE:
                $user = $filter->getUser();
                $name = $user ?
                    ($user->getSellerData() && $user->getSellerData()->getSellerCompany() &&
                        $user->getSellerData()->getSellerCompany()->getCompanyName() ?
                        $user->getSellerData()->getSellerCompany()->getCompanyName() : $user->getName()) : "";
                $unp = $user && $user->getSellerData() && $user->getSellerData()->getSellerCompany() &&
                        $user->getSellerData()->getSellerCompany()->getUnp() ?
                        $user->getSellerData()->getSellerCompany()->getUnp() : "";
                $id = $user ? $user->getId() : "";

                $this->title = sprintf(self::USER_FILTER_TITLE, $name, $unp, $id);

                break;
            case self::BRAND_MODEL_FILTER_ROUTE:
            case self::BRAND_FILTER_ROUTE:
                $brand = $filter->getBrand() ? $filter->getBrand()->getName() : "";
                $model = $filter->getModel() ? $filter->getModel()->getName() : "";

                $this->title = sprintf(self::BRAND_MODEL_FILTER_TITLE, $brand, $model);

                break;
            case self::CITY_ACTIVITY_FILTER_ROUTE:
                $city = $filter->getCity() ? $filter->getCity()->getName() : "";
                $activity = $filter->getActivity() ? $filter->getActivity() : "";

                $this->title = sprintf(self::CITY_ACTIVITY_FILTER_TITLE, $city, $activity);

                break;
            default:
                return "";
        }
    }

    public function setFilteredDescription($route, PostsFilterType $filter)
    {
        switch ($route){
            case self::USER_FILTER_ROUTE:
                $user = $filter->getUser();
                $name = $user ?
                    ($user->getSellerData() && $user->getSellerData()->getSellerCompany() &&
                    $user->getSellerData()->getSellerCompany()->getCompanyName() ?
                        $user->getSellerData()->getSellerCompany()->getCompanyName() : $user->getName()) : "";
                $unp = $user && $user->getSellerData() && $user->getSellerData()->getSellerCompany() &&
                $user->getSellerData()->getSellerCompany()->getUnp() ?
                    $user->getSellerData()->getSellerCompany()->getUnp() : "";

                $this->description = sprintf(self::USER_FILTER_DESCRIPTION, $name, $unp);

                break;
            case self::BRAND_MODEL_FILTER_ROUTE:
            case self::BRAND_FILTER_ROUTE:
                $brand = $filter->getBrand() ? $filter->getBrand()->getBrandEn() : "";
                $model = $filter->getModel() ? $filter->getModel()->getModelEn() : "";

                $this->description = sprintf(self::BRAND_MODEL_FILTER_DESCRIPTION, $brand, $model);

                break;
            case self::CITY_ACTIVITY_FILTER_ROUTE:
                $city = $filter->getCity() ? $filter->getCity()->getName() : "";
                $activity = $filter->getActivity() ? $filter->getActivity() : "";

                $this->description = sprintf(self::CITY_ACTIVITY_FILTER_DESCRIPTION, $city, $activity);

                break;
            default:
                return "";
        }
    }
}