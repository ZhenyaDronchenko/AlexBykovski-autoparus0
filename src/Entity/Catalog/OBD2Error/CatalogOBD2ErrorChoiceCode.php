<?php

namespace App\Entity\Catalog\OBD2Error;

use App\Entity\Catalog\CatalogPageThreeWithHeadlines;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_obd2error_choice_code")
 */
class CatalogOBD2ErrorChoiceCode extends CatalogPageThreeWithHeadlines
{
}