<?php

namespace App\Transformer;

use App\Entity\Catalog\CatalogPageFive;
use App\Entity\Catalog\CatalogPageFour;
use App\Entity\Catalog\CatalogPageOne;
use App\Entity\Catalog\CatalogPageOneReturnButton;
use App\Entity\Catalog\CatalogPageThree;
use App\Entity\Catalog\CatalogPageThreeWithHeadlines;
use App\Entity\Catalog\CatalogPageTwo;
use App\Entity\Catalog\CatalogPageTwoReturnButton;
use App\Entity\Catalog\OBD2Error\CatalogOBD2ErrorChoiceType;
use App\Entity\UniversalPage\UniversalPage;

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
        else if($object instanceof CatalogPageOneReturnButton){
            return $this->transformCatalogPageOneReturnButton($cloneObject, $parameters);
        }
        else if($object instanceof CatalogPageTwoReturnButton){
            return $this->transformCatalogPageTwoReturnButton($cloneObject, $parameters);
        }
        else if($object instanceof CatalogPageThreeWithHeadlines){
            return $this->transformCatalogPageThreeWithHeadlines($cloneObject, $parameters);
        }
        else if($object instanceof CatalogPageFive){
            return $this->transformCatalogPageFive($cloneObject, $parameters);
        }
        else if($object instanceof UniversalPage){
            return $this->transformUniversalPage($cloneObject, $parameters);
        }

        return $cloneObject;
    }

    protected function transformString($string, array $parameters = [])
    {
        foreach ($parameters as $parameter){
            if(!is_object($parameter) && !is_array($parameter)){
                continue;
            }

            if(is_object($parameter)){
                $string = $parameter->replaceVariables($string);
            }
            else{
                $key = array_keys($parameter)[0];
                $value = array_values($parameter)[0];

                $string = str_replace($key, $value, $string);
            }


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

    protected function transformCatalogPageThreeWithHeadlines(CatalogPageThreeWithHeadlines $catalogPageThree, $parameters)
    {
        $catalogPageThree->setTitle($this->transformString($catalogPageThree->getTitle(), $parameters));
        $catalogPageThree->setDescription($this->transformString($catalogPageThree->getDescription(), $parameters));
        $catalogPageThree->setText1($this->transformString($catalogPageThree->getText1(), $parameters));
        $catalogPageThree->setText2($this->transformString($catalogPageThree->getText2(), $parameters));
        $catalogPageThree->setHeadline1($this->transformString($catalogPageThree->getHeadline1(), $parameters));
        $catalogPageThree->setHeadline2($this->transformString($catalogPageThree->getHeadline2(), $parameters));

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

    protected function transformCatalogPageOneReturnButton(CatalogPageOneReturnButton $page, $parameters)
    {
        $page->setTitle($this->transformString($page->getTitle(), $parameters));
        $page->setDescription($this->transformString($page->getDescription(), $parameters));
        $page->setHeadline1($this->transformString($page->getHeadline1(), $parameters));
        $page->setHeadline2($this->transformString($page->getHeadline2(), $parameters));
        $page->setText1($this->transformString($page->getText1(), $parameters));
        $page->setText2($this->transformString($page->getText2(), $parameters));
        $page->setReturnButtonText($this->transformString($page->getReturnButtonText(), $parameters));
        $page->setReturnButtonLink($this->transformString($page->getReturnButtonLink(), $parameters));

        return $page;
    }

    protected function transformCatalogPageTwoReturnButton(CatalogPageTwoReturnButton $page, $parameters)
    {
        $page->setTitle($this->transformString($page->getTitle(), $parameters));
        $page->setDescription($this->transformString($page->getDescription(), $parameters));
        $page->setHeadline1($this->transformString($page->getHeadline1(), $parameters));
        $page->setHeadline2($this->transformString($page->getHeadline2(), $parameters));
        $page->setText1($this->transformString($page->getText1(), $parameters));
        $page->setText2($this->transformString($page->getText2(), $parameters));
        $page->setText3($this->transformString($page->getText3(), $parameters));
        $page->setReturnButtonText($this->transformString($page->getReturnButtonText(), $parameters));
        $page->setReturnButtonLink($this->transformString($page->getReturnButtonLink(), $parameters));

        return $page;
    }

    protected function transformCatalogPageFive(CatalogPageFive $page, $parameters)
    {
        $page->setTitle($this->transformString($page->getTitle(), $parameters));
        $page->setDescription($this->transformString($page->getDescription(), $parameters));
        $page->setText1($this->transformString($page->getText1(), $parameters));
        $page->setText2($this->transformString($page->getText2(), $parameters));
        $page->setHeadline1($this->transformString($page->getHeadline1(), $parameters));
        $page->setHeadline2($this->transformString($page->getHeadline2(), $parameters));
        $page->setReturnButtonText($this->transformString($page->getReturnButtonText(), $parameters));
        $page->setReturnButtonLink($this->transformString($page->getReturnButtonLink(), $parameters));

        return $page;
    }

    protected function transformUniversalPage(UniversalPage $page, $parameters)
    {
        $page->setTitle($this->transformString($page->getTitle(), $parameters));
        $page->setDescription($this->transformString($page->getDescription(), $parameters));
        $page->setHeadline1($this->transformString($page->getHeadline1(), $parameters));
        $page->setText1($this->transformString($page->getText1(), $parameters));
        $page->setText2($this->transformString($page->getText2(), $parameters));
        $page->setReturnButtonText($this->transformString($page->getReturnButtonText(), $parameters));
        $page->setReturnButtonLink($this->transformString($page->getReturnButtonLink(), $parameters));
        $page->setLastBreadCrumb($this->transformString($page->getLastBreadCrumb(), $parameters));

        return $page;
    }
}