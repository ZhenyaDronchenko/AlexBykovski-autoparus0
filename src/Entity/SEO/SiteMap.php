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
        "Сайт-мап Каталог от Запчасти + OBD2 ошибки" => "spare_part_catalog_obd2_minsk",
        "Сайт-мап Каталог от запчасти + OBD2 ошибки по всем городам" => "spare_part_catalog_obd2_all_cities",
        "Сайт-мап Каталога от марки (только популярные) + OBD2 + turbo + city" => "brand_catalog_obd2_turbo_city",
        "Сайт-мап Форум OBD2 + OBD2 Ошибки + Универсальные страницы" => "forum_obd2_errors_obd2_universal_pages",
    ];

    const TYPE_BRAND_CATALOG = "brand_catalog";
    const TYPE_BRAND_CATALOG_POPULAR = "brand_catalog_popular";
    const TYPE_SPARE_PART_CATALOG_OBD2_MINSK = "spare_part_catalog_obd2_minsk";
    const TYPE_SPARE_PART_CATALOG_OBD2_ALL_CITIES = "spare_part_catalog_obd2_all_cities";
    const TYPE_BRAND_CATALOG_OBD2_TURBO_CITY = "brand_catalog_obd2_turbo_city";
    const TYPE_FORUM_OBD2_ERRORS_OBD2_UNIVERSAL_PAGES = "forum_obd2_errors_obd2_universal_pages";

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