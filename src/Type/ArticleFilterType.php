<?php

namespace App\Type;

class ArticleFilterType
{
    const SORT_UPDATED = "SORT_UPDATED";
    const SORT_CREATED = "SORT_CREATED";
    const SORT_VIEWS = "SORT_VIEWS";

    /**
     * @var string
     */
    private $typeSort;

    /**
     * @var array
     */
    private $themes;

    /**
     * @var array
     */
    private $notThemes;

    /**
     * @var integer
     */
    private $limit = 0;

    /**
     * @var integer
     */
    private $offset = 0;

    /** @var bool|null */
    private $isOur;

    /**
     * ArticleFilterType constructor.
     * @param string $typeSort
     * @param array $themes
     * @param int $limit
     * @param int $offset
     * @param boolean $isOur
     * @param array $notThemes
     */
    public function __construct(string $typeSort, array $themes = [], int $limit = 6, int $offset = 0, $isOur = null, $notThemes = [])
    {
        $this->typeSort = $typeSort;
        $this->themes = $themes;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->isOur = $isOur;
        $this->notThemes = $notThemes;
    }

    /**
     * @return string
     */
    public function getTypeSort(): string
    {
        return $this->typeSort;
    }

    /**
     * @param string $typeSort
     */
    public function setTypeSort(string $typeSort): void
    {
        $this->typeSort = $typeSort;
    }

    /**
     * @return array
     */
    public function getThemes(): array
    {
        return $this->themes;
    }

    /**
     * @param array $themes
     */
    public function setThemes(array $themes): void
    {
        $this->themes = $themes;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @return bool|null
     */
    public function getisOur(): ?bool
    {
        return $this->isOur;
    }

    /**
     * @param bool|null $isOur
     */
    public function setIsOur(?bool $isOur): void
    {
        $this->isOur = $isOur;
    }

    /**
     * @return array
     */
    public function getNotThemes(): array
    {
        return $this->notThemes;
    }

    /**
     * @param array $notThemes
     */
    public function setNotThemes(array $notThemes): void
    {
        $this->notThemes = $notThemes;
    }
}