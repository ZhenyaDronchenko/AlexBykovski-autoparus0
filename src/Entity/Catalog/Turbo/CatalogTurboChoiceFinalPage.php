<?php

namespace App\Entity\Catalog\Turbo;

use App\Entity\Catalog\CatalogPageThreeWithHeadlines;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_turbo_choice_final_page")
 */
class CatalogTurboChoiceFinalPage extends CatalogPageThreeWithHeadlines
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $returnButtonLink;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $returnButtonText;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    protected $text3;

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

    /**
     * @return null|string
     */
    public function getText3(): ?string
    {
        return $this->text3;
    }

    /**
     * @param null|string $text3
     */
    public function setText3(?string $text3): void
    {
        $this->text3 = $text3;
    }
}