<?php

namespace App\Twig;

use App\Provider\TitleProvider;
use App\Transformer\VariableTransformer;
use Twig_Extension;
use Twig_Function;

class StringVariableTransformerExtension extends Twig_Extension
{
    /** @var VariableTransformer $transformer */
    private $transformer;

    /**
     * ShowCodeExtension constructor.
     *
     * @param VariableTransformer $transformer
     */
    public function __construct(VariableTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function getFunctions()
    {
        return array(
            new Twig_Function('string_transform', [$this, 'transformString']),
        );
    }

    public function transformString($string, $parameters = [])
    {
        return $this->transformer->transformPage($string, $parameters);
    }
}