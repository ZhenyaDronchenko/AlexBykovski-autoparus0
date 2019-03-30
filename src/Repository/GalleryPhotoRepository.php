<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class GalleryPhotoRepository extends EntityRepository
{
    public function findAllByCreatedAt()
    {
        return $this->createQueryBuilder('gph')
            ->select('gph')
            ->join("gph.image", "im")
            ->orderBy("im.createdAt", "DESC")
            ->getQuery()
            ->getResult();
    }
}