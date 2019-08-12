<?php

namespace App\Controller;

use App\Entity\Client\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PostViewController extends Controller
{
    /**
     * @Route("/business-post/{id}", name="post_view_show_business_post", options={"expose"=true})
     * @Route("/car-post/{id}", name="post_view_show_car_post", options={"expose"=true})
     *
     * @ParamConverter("post", class="App\Entity\Client\Post", options={"id" = "id"})
     */
    public function showPostAction(Request $request, Post $post)
    {
        return $this->render('client/post-view/view.html.twig', [
            "post" => $post,
        ]);
    }
}