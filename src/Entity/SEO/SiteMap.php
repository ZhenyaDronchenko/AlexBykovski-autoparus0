<?php

namespace App\Entity\SEO;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="site_map")
 */
class SiteMap
{
    const TYPES = [
        "brand_catalog",
        "brand_catalog_popular",
    ];
    const ADMIN_CHOICES = [
        "Сайт-мап Каталога от марки" => "brand_catalog",
        "Сайт-мап Каталога от марки (только популярные)" => "brand_catalog_popular",
    ];

    const TYPE_BRAND_CATALOG = "brand_catalog";
    const TYPE_BRAND_CATALOG_POPULAR = "brand_catalog_popular";

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}