<?php

namespace App\Provider\Advert;

use App\Type\CatalogAdvertFilterType;

class AdvertProvider
{
    /** @var SpecificAdvertProvider */
    private $specificAdvertProvider;

    /** @var GeneralAdvertProvider */
    private $generalAdvertProvider;

    /**
     * AdvertProvider constructor.
     * @param SpecificAdvertProvider $specificAdvertProvider
     * @param GeneralAdvertProvider $generalAdvertProvider
     */
    public function __construct(SpecificAdvertProvider $specificAdvertProvider, GeneralAdvertProvider $generalAdvertProvider)
    {
        $this->specificAdvertProvider = $specificAdvertProvider;
        $this->generalAdvertProvider = $generalAdvertProvider;
    }

    public function provideSortedSpecificAdverts(CatalogAdvertFilterType $catalogFilter)
    {
        return $this->specificAdvertProvider->provideSortedListAdverts($catalogFilter);
    }

    public function provideSortedGeneralAdverts(CatalogAdvertFilterType $catalogFilter)
    {
        return $this->generalAdvertProvider->provideSortedListAdverts($catalogFilter);
    }
}