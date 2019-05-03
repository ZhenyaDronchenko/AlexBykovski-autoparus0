<?php

namespace App\Entity\Forum\OBD2Forum;

use App\Entity\Catalog\CatalogPageOneReturnButton;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="obd2forum_choice_model")
 */
class OBD2ForumChoiceModel extends CatalogPageOneReturnButton
{
}