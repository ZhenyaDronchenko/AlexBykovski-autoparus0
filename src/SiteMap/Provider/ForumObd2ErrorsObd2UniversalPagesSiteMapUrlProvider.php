<?php

namespace App\SiteMap\Provider;

use App\Entity\Brand;
use App\Entity\City;
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Error\TypeOBD2Error;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Entity\UniversalPage\UniversalPageBrand;
use App\Entity\UniversalPage\UniversalPageCity;
use App\Entity\UniversalPage\UniversalPageSparePart;
use App\SiteMap\SiteMapUrlProvider;
use App\SiteMap\SiteMapFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ForumObd2ErrorsObd2UniversalPagesSiteMapUrlProvider implements SiteMapUrlProvider
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var RouterInterface */
    private $router;

    /** @var string $publicPath */
    private $publicPath;

    /**
     * BrandCatalogSiteMapBuilder constructor.
     *
     * @param EntityManagerInterface $em
     * @param RouterInterface $router
     * @param string $publicPath
     */
    public function __construct(EntityManagerInterface $em, RouterInterface $router, string $publicPath)
    {
        $this->em = $em;
        $this->router = $router;
        $this->publicPath = $publicPath;
    }


    public function provide(string $requestFile): array
    {
        $type = strlen($requestFile) && $requestFile[0] === '_' ? substr($requestFile, 1) : $requestFile;

        switch ($type){
            case SiteMapFactory::SITE_MAP_INDEX:
                return $this->provideIndex();
            default:
                return $this->provideSimple($type);
        }
    }

    public function provideIndex(): array
    {
        $baseUrl = $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $urls = [$baseUrl . 'sitemap1.xml'];

        $this->setSitemapUrlsForumOBD2($urls);
        $this->setSitemapUrlsUniversalPages($urls);

        return $urls;
    }

    public function provideSimple(string $type): array
    {
        switch($type){
            case "1":
                return $this->provideFirstSitemap();
            default:
                if(strpos($type, "forumobd2_") === 0){
                    list($brandUrl, $modelUrl) = explode("___", str_replace("forumobd2_", "", $type));

                    return $this->provideForumOBD2Sitemap($brandUrl, $modelUrl);
                }
                elseif (strpos($type, "universal_pages_") === 0){
                    return $this->provideUniversalPageUrls(str_replace("universal_pages_", "", $type));
                }

                return [];
        }
    }

    private function setSitemapUrlsForumOBD2(&$urls)
    {
        $brandUrls = $this->em->getRepository(Brand::class)->findAllUrlsForSiteMap();
        $baseUrl = $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL);

        foreach ($brandUrls as $brandUrl){
            $modelUrls = $this->em->getRepository(Model::class)->findAllUrlByBrandUrl($brandUrl["url"]);

            foreach ($modelUrls as $modelUrl){
                $urls[] = $baseUrl . 'sitemap_forumobd2_' . $brandUrl["url"] . '___' . $modelUrl["url"] . '.xml';
            }
        }
    }

    private function setSitemapUrlsUniversalPages(&$urls)
    {
        $baseUrl = $this->router->generate("homepage", [], UrlGeneratorInterface::ABSOLUTE_URL);

        $urls[] = $baseUrl . 'sitemap_universal_pages_brand.xml';
        $urls[] = $baseUrl . 'sitemap_universal_pages_spare_part.xml';
        $urls[] = $baseUrl . 'sitemap_universal_pages_city.xml';
    }

    private function provideFirstSitemap()
    {
        $types = $this->em->getRepository(TypeOBD2Error::class)->findAllUrlsForSiteMap();
        $absUrlConst = UrlGeneratorInterface::ABSOLUTE_URL;

        $obd2BasePage = $this->router->generate("show_obd2_error_catalog_choice_type", [], $absUrlConst);

        $urls = [$obd2BasePage];

        foreach ($types as $type){
            $urls[] = $this->router->generate("show_obd2_error_catalog_choice_code", ["urlType" => $type["url"]], $absUrlConst);
            $codes = $this->em->getRepository(CodeOBD2Error::class)->findAllUrlsForSiteMap($type["url"]);

            foreach ($codes as $code){
                if(!$code["url"]){
                    continue;
                }

                $parameters = ["urlType" => $type["url"], "urlCode" => $code["url"]];

                $urls[] = $this->router->generate("show_obd2_error_catalog_choice_transcript",
                    $parameters, $absUrlConst);

                $urls[] = $this->router->generate("show_obd2_error_catalog_choice_reason",
                    $parameters, $absUrlConst);
            }
        }

        return $urls;
    }

    private function provideForumOBD2Sitemap($brandUrl, $modelUrl)
    {
        $urls = [];
        $absUrlConst = UrlGeneratorInterface::ABSOLUTE_URL;

        $brand = $this->em->getRepository(Brand::class)->findOneBy(["url" => $brandUrl]);
        $model = $this->em->getRepository(Model::class)->findOneBy([
            "brand" => $brand,
            "url" => $modelUrl,
        ]);

        if(!$brandUrl || !$modelUrl || !$brand || !$model){
            return $urls;
        }

        $types = $this->em->getRepository(TypeOBD2Error::class)->findAllUrlsForSiteMap();

        $urls[] = $this->router->generate("obd2_forum_choice_type", ["urlBrand" => $brandUrl], $absUrlConst);

        foreach ($types as $type){
            if(!$type["url"]){
                continue;
            }

            $urls[] = $this->router->generate("obd2_forum_choice_code", [
                "urlBrand" => $brandUrl,
                "urlType" => $type["url"],
            ], $absUrlConst);

            $codes = $this->em->getRepository(CodeOBD2Error::class)->findAllUrlsForSiteMap($type["url"]);

            foreach ($codes as $code){
                if(!$code["url"]){
                    continue;
                }

                $parameters = ["urlBrand" => $brandUrl, "urlType" => $type["url"], "urlCode" => $code["url"]];

                $urls[] = $this->router->generate("obd2_forum_choice_model", $parameters, $absUrlConst);

                $parameters["urlModel"] = $modelUrl;

                $urls[] = $this->router->generate("obd2_forum_final_page", $parameters, $absUrlConst);
            }
        }

        return $urls;
    }

    private function provideUniversalPageUrls($type)
    {
        switch ($type){
            case "brand":
                return $this->provideUniversalPageUrlsBrand();
            case "spare_part":
                return $this->provideUniversalPageUrlsSparePart();
            case "city":
                return $this->provideUniversalPageUrlsCity();
            default:
                return [];
        }
    }

    private function provideUniversalPageUrlsBrand()
    {
        $urls = [];
        $absUrlConst = UrlGeneratorInterface::ABSOLUTE_URL;

        $brandUrls = $this->em->getRepository(Brand::class)->findAllUrlsForSiteMap();
        $pages = $this->em->getRepository(UniversalPageBrand::class)->findAll();

        /** @var UniversalPageBrand $page */
        foreach ($pages as $page){
            foreach ($brandUrls as $brandUrl){
                $urls[] = $this->router->generate("universal_page_brand_specific_brand", [
                    "urlBrand" => $brandUrl["url"],
                    "id" => $page->getId(),
                ], $absUrlConst);
            }
        }

        return $urls;
    }

    private function provideUniversalPageUrlsSparePart()
    {
        $urls = [];
        $absUrlConst = UrlGeneratorInterface::ABSOLUTE_URL;

        $sparePartUrls = $this->em->getRepository(SparePart::class)->findAllUrlsForSiteMap();
        $pages = $this->em->getRepository(UniversalPageSparePart::class)->findAll();

        /** @var UniversalPageBrand $page */
        foreach ($pages as $page){
            foreach ($sparePartUrls as $sparePartUrl){
                $urls[] = $this->router->generate("universal_page_brand_specific_spare_part", [
                    "urlSp" => $sparePartUrl["url"],
                    "id" => $page->getId(),
                ], $absUrlConst);
            }
        }

        return $urls;
    }

    private function provideUniversalPageUrlsCity()
    {
        $urls = [];
        $absUrlConst = UrlGeneratorInterface::ABSOLUTE_URL;

        $cityUrls = $this->em->getRepository(City::class)->findAllUrlsForSiteMap();
        $pages = $this->em->getRepository(UniversalPageCity::class)->findAll();

        /** @var UniversalPageBrand $page */
        foreach ($pages as $page){
            foreach ($cityUrls as $cityUrl){
                $urls[] = $this->router->generate("universal_page_city_specific_city", [
                    "urlCity" => $cityUrl["url"],
                    "id" => $page->getId(),
                ], $absUrlConst);
            }
        }

        return $urls;
    }
}