<?php

namespace App\Controller\UserOffice;

use App\Entity\Client\Client;
use App\Entity\Client\Post;
use App\Entity\Client\PostPhoto;
use App\Entity\Image;
use App\Provider\GeoLocation\GeoLocationProvider;
use App\Upload\FileUpload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/posts/")
 *
 * @Security("has_role('ROLE_CLIENT')")
 */
class PostController extends Controller
{
    /**
     * @Route("/add-post-ajax", name="posts_add_post_ajax", options={"expose"=true})
     * @Route("/edit-post-ajax/{id}/", name="posts_edit_post_ajax", options={"expose"=true})
     *
     * @ParamConverter("postPhoto", class="App\Entity\Client\PostPhoto", options={"id" = "id"})
     */
    public function addEditPostAjaxAction(
        Request $request,
        GeoLocationProvider $provider,
        PostPhoto $postPhoto = null
    )
    {
        /** @var Client $client */
        $client = $this->getUser();
        /** @var FileUpload $uploader */
        $uploader = $this->get("app.file_upload");
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $post = $postPhoto ? $postPhoto->getPost() : null;

        if($post && $post->getClient()->getId() !== $client->getId()){
            return new JsonResponse([
                "success" => false,
            ]);
        }

        $file = $request->files->get("file");
        $ip = $request->getClientIp();
        $coordinates = $request->request->get("coordinates");
        $coordinates = $coordinates ? json_decode($coordinates, true) : null;
        $description = $request->request->get("description");
        $postType = $request->request->get("type") == Post::BUSINESS_TYPE ? Post::BUSINESS_TYPE : Post::SIMPLE_TYPE;

        $uploader->setFolder(FileUpload::USER_OFFICE_GALLERY);
        $path = $uploader->upload($file);

        if(!$post){
            $image = new Image($path);
            $geoLocation = $provider->addGeoLocationToImage($coordinates, $ip);
            $image->setGeoLocation($geoLocation);

            $post = new Post($client, $image, $postType);

            $em->persist($post);
        }
        else{
            $postPhoto->getImage()->setImage($path);
        }

        if($description) {
            $post->setDescription($description);
        }

        $em->flush();

        $em->refresh($post);

        return new JsonResponse([
            "success" => true,
            "post" => $post->toArray(),
        ]);
    }

    /**
     * @Route("/add-post-photo-ajax/{id}/", name="posts_add_post_photo_ajax", options={"expose"=true})
     * @Route("/edit-post-photo-ajax/{id}/{idPostPhoto}", name="posts_edit_post_photo_ajax", options={"expose"=true})
     *
     * @ParamConverter("post", class="App\Entity\Client\Post", options={"id" = "id"})
     * @ParamConverter("postPhoto", class="App\Entity\Client\PostPhoto", options={"id" = "idPostPhoto"})
     */
    public function addEditPostPhotoAjaxAction(
        Request $request,
        GeoLocationProvider $provider,
        Post $post,
        PostPhoto $postPhoto = null
    )
    {
        /** @var Client $client */
        $client = $this->getUser();
        /** @var FileUpload $uploader */
        $uploader = $this->get("app.file_upload");
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();

        if(!$post || $post->getClient()->getId() !== $client->getId()){
            return new JsonResponse([
                "success" => false,
            ]);
        }

        $file = $request->files->get("file");
        $ip = $request->getClientIp();
        $coordinates = $request->request->get("coordinates");
        $coordinates = $coordinates ? json_decode($coordinates, true) : null;

        $uploader->setFolder(FileUpload::USER_OFFICE_GALLERY);
        $path = $uploader->upload($file);

        if(!$postPhoto){
            $image = new Image($path);
            $geoLocation = $provider->addGeoLocationToImage($coordinates, $ip);
            $image->setGeoLocation($geoLocation);

            $postPhoto = new PostPhoto($image, $post);

            $em->persist($postPhoto);
        }
        else{
            $postPhoto->getImage()->setImage($path);
        }

        $em->flush();

        $em->refresh($post);

        return new JsonResponse([
            "success" => true,
            "postPhoto" => $postPhoto->toArray(true),
        ]);
    }

    /**
     * @Route("/remove-post-ajax/{id}", name="posts_remove_post_ajax", options={"expose"=true})
     *
     * @ParamConverter("post", class="App\Entity\Client\Post", options={"id" = "id"})
     */
    public function removePostAjaxAction(Request $request, Post $post)
    {
        if($post->getClient()->getId() !== $this->getUser()->getId()){
            return new JsonResponse([
                "success" => false,
            ]);
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse([
            "success" => true,
        ]);
    }

    /**
     * @Route("/get-all-posts-ajax", name="posts_get_all_posts_ajax", options={"expose"=true})
     */
    public function getAllPosts(Request $request)
    {
        return new JsonResponse($this->getUser()->getPostsArray());
    }

    /**
     * @Route("/remove-post-filter-ajax/{filterId}/{postId}", name="remove_post_filter_ajax", options={"expose"=true})
     *
     * @ParamConverter("post", class="App\Entity\Client\Post", options={"id" = "postId"})
     */
    public function removePostFilterAction(Request $request, $filterId, Post $post)
    {
        $filter = $post->getFilter($filterId);

        if($post->getClient()->getId() !== $this->getUser()->getId() || !$filter){
            return new JsonResponse(["success" => false]);
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($filter);
        $em->flush();

        $em->refresh($post);

        return new JsonResponse([
            "success" => true,
            "post" => $post->toArray(),
        ]);
    }
}