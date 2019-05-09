<?php

namespace App\Twig;

use App\Provider\TitleProvider;
use Twig_Extension;
use Twig_Function;

class TitleTransformerExtension extends Twig_Extension
{
    /** @var TitleProvider $titleProvider */
    private $titleProvider;

    /**
     * ShowCodeExtension constructor.
     *
     * @param TitleProvider $titleProvider
     */
    public function __construct(TitleProvider $titleProvider)
    {
        $this->titleProvider = $titleProvider;
    }

    public function getFunctions()
    {
        return array(
            new Twig_Function('title_provider', [$this, 'getClassTitle']),
        );
    }

    public function getClassTitle($class, $parameters = [])
    {
        return $this->titleProvider->getSinglePageTitle($class, $parameters);
    }
}