<?php

namespace App\Transformer;

use App\Entity\Catalog\CatalogPageFour;
use App\Entity\Catalog\CatalogPageOne;
use App\Entity\Catalog\CatalogPageThree;
use App\Entity\Catalog\CatalogPageTwo;

class VariableTransformer
{
    public function transformPage($object, $parameters)
    {
        $cloneObject = is_object($object) ? clone $object : $object;

        if(is_string($object)){
            return $this->transformString($cloneObject, $parameters);
        }
        elseif($object instanceof CatalogPageOne){
            return $this->transformCatalogPageOne($cloneObject, $parameters);
        }
        else if($object instanceof CatalogPageTwo){
            return $this->transformCatalogPageTwo($cloneObject, $parameters);
        }
        else if($object instanceof CatalogPageThree){
            return $this->transformCatalogPageThree($cloneObject, $parameters);
        }
        else if($object instanceof CatalogPageFour){
            return $this->transformCatalogPageFour($cloneObject, $parameters);
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

        return preg_replace('/\[.+\]/', '', $string);
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

    protected function transformCatalogPageTwo(CatalogPageTwo $catalogPageTwo, $parameters)
    {
        $catalogPageTwo->setTitle($this->transformString($catalogPageTwo->getTitle(), $parameters));
        $catalogPageTwo->setDescription($this->transformString($catalogPageTwo->getDescription(), $parameters));
        $catalogPageTwo->setHeadline1($this->transformString($catalogPageTwo->getHeadline1(), $parameters));
        $catalogPageTwo->setHeadline2($this->transformString($catalogPageTwo->getHeadline2(), $parameters));
        $catalogPageTwo->setText1($this->transformString($catalogPageTwo->getText1(), $parameters));
        $catalogPageTwo->setText2($this->transformString($catalogPageTwo->getText2(), $parameters));
        $catalogPageTwo->setText3($this->transformString($catalogPageTwo->getText3(), $parameters));

        return $catalogPageTwo;
    }

    protected function transformCatalogPageThree(CatalogPageThree $catalogPageThree, $parameters)
    {
        $catalogPageThree->setTitle($this->transformString($catalogPageThree->getTitle(), $parameters));
        $catalogPageThree->setDescription($this->transformString($catalogPageThree->getDescription(), $parameters));
        $catalogPageThree->setText1($this->transformString($catalogPageThree->getText1(), $parameters));
        $catalogPageThree->setText2($this->transformString($catalogPageThree->getText2(), $parameters));

        return $catalogPageThree;
    }

    protected function transformCatalogPageFour(CatalogPageFour $catalogPageFour, $parameters)
    {
        $catalogPageFour->setTitle($this->transformString($catalogPageFour->getTitle(), $parameters));
        $catalogPageFour->setDescription($this->transformString($catalogPageFour->getDescription(), $parameters));
        $catalogPageFour->setText1($this->transformString($catalogPageFour->getText1(), $parameters));
        $catalogPageFour->setText2($this->transformString($catalogPageFour->getText2(), $parameters));
        $catalogPageFour->setText3($this->transformString($catalogPageFour->getText3(), $parameters));

        return $catalogPageFour;
    }
}