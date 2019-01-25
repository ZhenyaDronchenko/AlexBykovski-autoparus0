<?php

namespace App\Entity\Catalog\OBD2Error;

use App\Entity\Catalog\CatalogPageThreeWithHeadlines;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_obd2error_choice_transcript")
 */
class CatalogOBD2ErrorChoiceTranscript extends CatalogPageThreeWithHeadlines
{
    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    private $text3;

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