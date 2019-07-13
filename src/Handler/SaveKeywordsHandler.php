<?php

namespace App\Handler;


use App\Entity\Brand;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Entity\UserData\ImportAdvertError;
use Doctrine\ORM\EntityManagerInterface;

class SaveKeywordsHandler
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * SaveKeywordsHandler constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function saveKeywords($type, $value, ImportAdvertError $error)
    {
        if(!$value){
            return false;
        }

        switch($type){
            case "brand":
                /** @var Brand $brand */
                $brand = $this->em->getRepository(Brand::class)->find($value);
                $brand->addKeyWord($error->getFieldValue());

                break;
            case "spare-part":
                /** @var SparePart $sparePart */
                $sparePart = $this->em->getRepository(SparePart::class)->find($value);
                $sparePart->addKeyWord($error->getFieldValue());

                break;
            case "model":
                foreach (explode(',', $value) as $modelId){
                    /** @var Model $model */
                    $model = $this->em->getRepository(Model::class)->find($modelId);
                    $model->addKeyWord($error->getFieldValue());
                }

                break;
        }

        return $this->em->flush();
    }
}