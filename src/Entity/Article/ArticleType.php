<?php

namespace App\Entity\Article;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="article_type")
 */
class ArticleType
{
    const OUR_UNIQUE_MATERIAL = "our_unique_material";
    const AUTO_LADY = "auto_lady";
    const YOUTH = "youth";
    const STORIES_PERSONS = "stories_persons";

    const TYPES = [
        ArticleType::OUR_UNIQUE_MATERIAL => 'Статья - это наш уникальный материал',
        ArticleType::AUTO_LADY => 'Автоледи',
        ArticleType::YOUTH => 'Статьи',
        ArticleType::STORIES_PERSONS => 'Статьи-история персонажей',
    ];

    /**
     * @var int
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}