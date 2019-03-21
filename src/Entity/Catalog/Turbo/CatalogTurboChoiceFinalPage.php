<?php

namespace App\Entity\Catalog\Turbo;

use App\Entity\Catalog\CatalogPageFive;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_turbo_choice_final_page")
 */
class CatalogTurboChoiceFinalPage extends CatalogPageFive
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    protected $text3;

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