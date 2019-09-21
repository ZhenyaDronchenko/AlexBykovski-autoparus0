<?php

namespace App\Entity\SEO;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="site_map")
 */
class SiteMap
{
    const TYPE_BRAND_CATALOG = "brand_catalog";
    const TYPE_BRAND_CATALOG_POPULAR = "brand_catalog_popular";
    const TYPE_SPARE_PART_CATALOG_OBD2_MINSK = "spare_part_catalog_obd2_minsk";
    const TYPE_SPARE_PART_CATALOG_OBD2_ALL_CITIES = "spare_part_catalog_obd2_all_cities";
    const TYPE_BRAND_CATALOG_OBD2_TURBO_CITY = "brand_catalog_obd2_turbo_city";
    const TYPE_FORUM_OBD2_ERRORS_OBD2_UNIVERSAL_PAGES = "forum_obd2_errors_obd2_universal_pages";
    const TYPE_UNIVERSAL_PRODUCT_GENERAL_PAGES = "universal_product_general_pages";
    const TYPE_FRESH_PRODUCT_PAGES = "fresh_product_pages";
    const TYPE_ALL_ARTICLES = "all_articles";

    const ADMIN_CHOICES = [
        "Сайт-мап Каталога от марки" => self::TYPE_BRAND_CATALOG,
        "Сайт-мап Каталога от марки (только популярные)" => self::TYPE_BRAND_CATALOG_POPULAR,
        "Сайт-мап Каталог от Запчасти + OBD2 ошибки" => self::TYPE_SPARE_PART_CATALOG_OBD2_MINSK,
        "Сайт-мап Каталог от запчасти + OBD2 ошибки по всем городам" => self::TYPE_SPARE_PART_CATALOG_OBD2_ALL_CITIES,
        "Сайт-мап Каталога от марки (только популярные) + OBD2 + turbo + city" => self::TYPE_BRAND_CATALOG_OBD2_TURBO_CITY,
        "Сайт-мап Форум OBD2 + OBD2 Ошибки + Универсальные страницы" => self::TYPE_FORUM_OBD2_ERRORS_OBD2_UNIVERSAL_PAGES,
        "Сайт-мап универсальных карточек товара" => self::TYPE_UNIVERSAL_PRODUCT_GENERAL_PAGES,
        "Сайт-мап свежих карточек товара" => self::TYPE_FRESH_PRODUCT_PAGES,
        "Сайт-мап всех статей" => self::TYPE_ALL_ARTICLES,
    ];

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