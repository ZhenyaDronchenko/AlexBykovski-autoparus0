<?php

namespace App\Entity\Catalog\City;

use App\Entity\Catalog\CatalogPageOne;
use App\Entity\Catalog\CatalogPageOneReturnButton;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_city_choice_model")
 */
class CatalogCityChoiceModel extends CatalogPageOneReturnButton
{
}