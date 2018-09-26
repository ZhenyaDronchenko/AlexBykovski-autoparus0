<?php

namespace App\Transformer;

use App\Entity\Catalog\CatalogPageOne;
use App\Entity\Catalog\CatalogPageTwo;

class VariableTransformer
{
    public function transformPage($object, $parameters)
    {
        $cloneObject = clone $object;

        if(is_string($object)){
            return $this->transformString($cloneObject, $parameters);
        }
        elseif($object instanceof CatalogPageOne){
            return $this->transformCatalogPageOne($cloneObject, $parameters);
        }
        else if($object instanceof CatalogPageTwo){
            return $this->transformCatalogPageTwo($cloneObject, $parameters);
        }

        return $cloneObject;
    }

    protected function transformString($string, array $parameters = [])
    {
        foreach ($parameters as $parameter){
            if(!is_object($parameter)){
                continue;
            }

            $string = $parameter->replaceVariables($string);
        }

        return $string;
    }

    protected function transformCatalogPageOne(CatalogPageOne $catalogPageOne, $parameters)
    {
        $catalogPageOne->setTitle($this->transformString($catalogPageOne->getTitle(), $parameters));
        $catalogPageOne->setDescription($this->transformString($catalogPageOne->getDescription(), $parameters));
        $catalogPageOne->setHeadline1($this->transformString($catalogPageOne->getHeadline1(), $parameters));
        $catalogPageOne->setHeadline2($this->transformString($catalogPageOne->getHeadline2(), $parameters));
        $catalogPageOne->setText1($this->transformString($catalogPageOne->getText1(), $parameters));
        $catalogPageOne->setText2($this->transformString($catalogPageOne->getText2(), $parameters));

        return $catalogPageOne;
    }

    protected function transformCatalogPageTwo(CatalogPageTwo $catalogPageOne, $parameters)
    {
        $catalogPageOne->setTitle($this->transformString($catalogPageOne->getTitle(), $parameters));
        $catalogPageOne->setDescription($this->transformString($catalogPageOne->getDescription(), $parameters));
        $catalogPageOne->setHeadline1($this->transformString($catalogPageOne->getHeadline1(), $parameters));
        $catalogPageOne->setHeadline2($this->transformString($catalogPageOne->getHeadline2(), $parameters));
        $catalogPageOne->setText1($this->transformString($catalogPageOne->getText1(), $parameters));
        $catalogPageOne->setText2($this->transformString($catalogPageOne->getText2(), $parameters));
        $catalogPageOne->setText3($this->transformString($catalogPageOne->getText3(), $parameters));

        return $catalogPageOne;
    }
}