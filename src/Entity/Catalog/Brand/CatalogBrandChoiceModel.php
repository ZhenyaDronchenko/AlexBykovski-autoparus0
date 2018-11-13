<?php

namespace App\Entity\Catalog\Brand;

use App\Entity\Catalog\CatalogPageOneReturnButton;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_brand_choice_model")
 */
class CatalogBrandChoiceModel extends CatalogPageOneReturnButton
{
}