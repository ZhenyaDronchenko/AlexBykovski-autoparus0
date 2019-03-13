<?php

namespace App\Entity\UniversalPage;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="universal_page_city")
 */
class UniversalPageCity extends UniversalPage
{
    /**
     * @var Collection
     *
     * Many UniversalPages have Many Images.
     * @ORM\ManyToMany(targetEntity="App\Entity\Image")
     * @ORM\JoinTable(name="universal_page_city_image",
     *      joinColumns={@ORM\JoinColumn(name="universal_page_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true)}
     *      )
     */
    protected $images;
}