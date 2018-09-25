<?php

namespace App\Controller;

use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search")
 */
class SearchController extends Controller
{
    /**
     * @Route("/spare-part")
     */
    public function searchSparePart(Request $request)
    {
        $text = $request->query->get("text");

        if(!is_string($text) || strlen($text) < 1){
            return new JsonResponse([]);
        }

        $spareParts = $this->getDoctrine()->getRepository(SparePart::class)->searchByText($text);

        $parsedSpareParts = [];

        /** @var SparePart $sparePart */
        foreach ($spareParts as $sparePart){
            $parsedSpareParts[] = $sparePart->toSearchArray();
        }

        return new JsonResponse($parsedSpareParts);
    }
}