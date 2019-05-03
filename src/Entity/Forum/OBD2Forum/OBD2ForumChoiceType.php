<?php

namespace App\Entity\Forum\OBD2Forum;

use App\Entity\Catalog\CatalogPageOneReturnButton;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="obd2forum_choice_type")
 */
class OBD2ForumChoiceType extends CatalogPageOneReturnButton
{
}