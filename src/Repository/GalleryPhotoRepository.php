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
            ->join("gph.gallery", "g")
            ->join("g.client", "cl")
            ->where("cl.id IN(:ids)")
            ->setParameter("ids", [34, 29, 28, 27, 26, 24])
            ->orderBy("im.createdAt", "DESC")
            ->getQuery()
            ->getResult();
    }
}