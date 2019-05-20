<?php

namespace App\Controller\Forum;

use App\Entity\Brand;
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Error\TypeOBD2Error;
use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceBrand;
use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceCode;
use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceModel;
use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceType;
use App\Entity\Forum\OBD2Forum\OBD2ForumFinalPage;
use App\Entity\Forum\OBD2ForumComment;
use App\Entity\Forum\OBD2ForumMessage;
use App\Entity\Forum\OBD2ForumMessageTechnicalData;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\UserData\UserOBD2ErrorCode;
use App\Form\Type\ErrorCodeSearchType;
use App\Transformer\VariableTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/forum-obd2-action")
 */
class OBD2ForumController extends Controller
{
    /**
     * @Route("/get-messages/{urlBrand}/{urlType}/{urlCode}/{urlModel}", name="ajax_get_obd2_forum_messages_with_model", options={"expose"=true})
     * @Route("/get-messages/{urlBrand}/{urlType}/{urlCode}", name="ajax_get_obd2_forum_messages_without_model", options={"expose"=true})
     */
    public function ajaxGetMessagesAction(Request $request, $urlBrand, $urlType, $urlCode, $urlModel = null)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $type = $em->getRepository(TypeOBD2Error::class)->findOneBy(["url" => $urlType]);
        $code = $em->getRepository(CodeOBD2Error::class)->findOneBy(["url" => $urlCode]);
        $model = $urlModel ? $em->getRepository(Model::class)->findOneBy([
            "url" => $urlModel,
            "brand" => $brand,
        ]) : null;

        if(!($brand instanceof Brand) || !($type instanceof TypeOBD2Error) || !($code instanceof CodeOBD2Error)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $messages = $em->getRepository(OBD2ForumMessage::class)->findByParameters($brand, $type, $code, $model);

        $parsedMessages = [];

        /** @var OBD2ForumMessage $message */
        foreach ($messages as $message) {
            $parsedMessages[$message->getId()] = $message->toArray();
        }

        return new JsonResponse([
            "success" => true,
            "messages" => $parsedMessages,
        ]);
    }

    /**
     * @Route("/add-message/{urlBrand}/{urlType}/{urlCode}/{urlModel}", name="ajax_obd2_forum_add_message", options={"expose"=true})
     *
     * @Security("has_role('ROLE_CLIENT')")
     */
    public function ajaxAddMessageAction(Request $request, $urlBrand, $urlType, $urlCode, $urlModel)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $type = $em->getRepository(TypeOBD2Error::class)->findOneBy(["url" => $urlType]);
        $code = $em->getRepository(CodeOBD2Error::class)->findOneBy(["url" => $urlCode]);
        $model = $em->getRepository(Model::class)->findOneBy([
            "url" => $urlModel,
            "brand" => $brand,
        ]);
        $messageType = $request->get("type");
        $message = $request->get("message");

        if(!($brand instanceof Brand) || !($model instanceof Model) ||
            !($type instanceof TypeOBD2Error) || !($code instanceof CodeOBD2Error) || !$messageType || !$message ||
            !in_array($messageType, OBD2ForumMessage::$availableTypes)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $technicalData = new OBD2ForumMessageTechnicalData($brand, $model, $type, $code);
        $message = new OBD2ForumMessage($message, $this->getUser(), $technicalData, $messageType);

        $em->persist($message);
        $em->flush();

        $em->refresh($message);

        return new JsonResponse([
            "success" => true,
            "message" => $message->toArray(),
        ]);
    }

    /**
     * @Route("/add-comment/{id}", name="ajax_obd2_forum_add_comment_to_message", options={"expose"=true})
     *
     * @ParamConverter("message", class="App\Entity\Forum\OBD2ForumMessage", options={"id" = "id"})
     *
     * @Security("has_role('ROLE_CLIENT')")
     */
    public function ajaxAddCommentToMessageAction(Request $request, OBD2ForumMessage $message)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $commentText = $request->get("comment");

        if(!$commentText){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $comment = new OBD2ForumComment($commentText, $this->getUser(), $message);

        $em->persist($comment);
        $em->flush();
        $em->refresh($message);

        return new JsonResponse([
            "success" => true,
            "message" => $message->toArray(),
        ]);
    }
}