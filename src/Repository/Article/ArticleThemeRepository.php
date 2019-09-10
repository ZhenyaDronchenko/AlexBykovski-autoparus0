<?php

namespace App\Repository\Article;

use App\Entity\Client\Client;
use App\Entity\Client\SellerCompany;
use App\Entity\User;
use App\Type\ArticleFilterType;
use App\Type\PostsFilterType;
use Doctrine\ORM\EntityRepository;

class ArticleThemeRepository extends EntityRepository
{
    public function findAllExcludeThemes($themeUrls)
    {
        return $this->createQueryBuilder('ath')
            ->select('ath')
            ->where("ath.url NOT IN(:urls)")
            ->setParameter("urls", $themeUrls)
            ->getQuery()
            ->getResult();
    }
}