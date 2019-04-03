<?php

namespace App\Entity\General;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="main_page_action")
 */
class MainPageAction
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $link;

    /**
     * @var MainPage
     *
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\General\MainPage", inversedBy="actions")
     * @ORM\JoinColumn(name="main_page_id", referencedColumnName="id")
     */
    private $mainPage;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param null|string $link
     */
    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    /**
     * @return MainPage
     */
    public function getMainPage(): MainPage
    {
        return $this->mainPage;
    }

    /**
     * @param MainPage $mainPage
     */
    public function setMainPage(MainPage $mainPage): void
    {
        $this->mainPage = $mainPage;
    }
}