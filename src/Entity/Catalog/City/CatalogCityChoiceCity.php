<?php

namespace App\Entity\Catalog\City;

use App\Entity\Catalog\CatalogPageOne;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_city_choice_city")
 */
class CatalogCityChoiceCity extends CatalogPageOne
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $returnButtonLink;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $returnButtonText;

    /**
     * @return null|string
     */
    public function getReturnButtonLink(): ?string
    {
        return $this->returnButtonLink;
    }

    /**
     * @param null|string $returnButtonLink
     */
    public function setReturnButtonLink(?string $returnButtonLink): void
    {
        $this->returnButtonLink = $returnButtonLink;
    }

    /**
     * @return null|string
     */
    public function getReturnButtonText(): ?string
    {
        return $this->returnButtonText;
    }

    /**
     * @param null|string $returnButtonText
     */
    public function setReturnButtonText(?string $returnButtonText): void
    {
        $this->returnButtonText = $returnButtonText;
    }
}