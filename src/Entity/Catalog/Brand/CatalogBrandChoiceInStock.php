<?php

namespace App\Entity\Catalog\Brand;

use App\Entity\Catalog\CatalogPageTwo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_brand_choice_in_stock")
 */
class CatalogBrandChoiceInStock extends CatalogPageTwo
{
}