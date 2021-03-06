<?php

namespace App\Entity\Catalog\SparePart;

use App\Entity\Catalog\CatalogPageOne;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_spare_part_choice_model")
 */
class CatalogSparePartChoiceModel extends CatalogPageOne
{
    const ALL_MODELS_URL = "all_models";
}