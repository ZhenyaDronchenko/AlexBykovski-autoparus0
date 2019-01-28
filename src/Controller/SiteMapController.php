<?php

namespace App\Controller;

use App\Entity\SEO\SiteMap;
use App\SiteMap\SiteMapFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

//https://support.google.com/webmasters/answer/75712?visit_id=636803154165836618-2077691311&rd=1
//https://support.google.com/webmasters/answer/183668?hl=ru#
//https://digitalfortress.tech/tutorial/generate-sitemap-in-symfony-in-2-simple-steps/
class SiteMapController extends Controller
{
    /**
     * @Route("/sitemap{path}.xml", name="sitemap_all", defaults={"_format"="xml"}, requirements={"path"=".+"})
     */
    public function showSiteMapSimpleAction(Request $request, $path)
    {
        /** @var SiteMapFactory $siteMapFactory */
        $siteMapFactory = $this->get("app.site_map.factory");
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        $activeSiteMap = $em->getRepository(SiteMap::class)->findAll()[0]->getType();

        $urls = [];

        if($activeSiteMap){
            $builder = $siteMapFactory->factory($activeSiteMap);

            $urls = $builder->provide($path);
        }

        if(!count($urls)){
            return new Response('Sitemap по данному url: /sitemap' . $path . '.xml - не существует', 404, ["Content-Type" => "text/html; charset=UTF-8"]);
        }

        $template = $path === '_' . SiteMapFactory::SITE_MAP_INDEX ? 'sitemap/index.html.twig' : 'sitemap/simple.html.twig';

        return $this->render($template, [
            "urls" => $urls,
        ]);
    }
}