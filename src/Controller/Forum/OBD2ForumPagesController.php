<?php

namespace App\Controller\Forum;

use App\Entity\Article\Article;
use App\Entity\Brand;
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Error\TypeOBD2Error;
use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceBrand;
use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceCode;
use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceModel;
use App\Entity\Forum\OBD2Forum\OBD2ForumChoiceType;
use App\Entity\Forum\OBD2Forum\OBD2ForumFinalPage;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\UserData\UserOBD2ErrorCode;
use App\Form\Type\ErrorCodeSearchType;
use App\Transformer\VariableTransformer;
use App\Type\ArticleFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/forum-obd2")
 */
class OBD2ForumPagesController extends Controller
{
    /**
     * @Route("", name="obd2_forum_choice_brand")
     */
    public function showChoiceBrandPageAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $allBrands = $em->getRepository(Brand::class)->findBy(["active" => true], ["name" => "ASC"]);

        $popularBrands = array_filter($allBrands, function(Brand $brand){
            return $brand->isPopular();
        });

        return $this->render('client/forum/obd2-forum/choice-brand.html.twig', [
            'allBrands' => $allBrands,
            'popularBrands' => $popularBrands,
            'page' => $em->getRepository(OBD2ForumChoiceBrand::class)->findAll()[0],
        ]);
    }

    /**
     * @Route("/{urlBrand}", name="obd2_forum_choice_type")
     */
    public function showChoiceModelPageAction(Request $request, $urlBrand, VariableTransformer $transformer)
    {
        if($urlBrand === "add-comment"){
            return $this->redirectToRoute("ajax_obd2_forum_add_comment_to_message");
        }

        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);

        if(!($brand instanceof Brand)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $types = $em->getRepository(TypeOBD2Error::class)->findAll();

        $parsedTypes = [];

        /** @var TypeOBD2Error $type */
        foreach ($types as $type){
            $designation = $type->getDesignation();

            $parsedTypes[$type->getDesignation()] = $type->toArray();

            if($designation === TypeOBD2Error::P_TYPE){
                $parsedTypes[TypeOBD2Error::SECOND_BUTTON_P] = $type->toArray();
            }
        }

        uksort($parsedTypes, function($key1, $key2){
            return array_search($key1, TypeOBD2Error::TYPE_CATALOG_ORDER) > array_search($key2, TypeOBD2Error::TYPE_CATALOG_ORDER);
        });

        $page = $em->getRepository(OBD2ForumChoiceType::class)->findAll()[0];

        return $this->render('client/forum/obd2-forum/choice-type.html.twig', [
            'types' => $parsedTypes,
            'page' => $transformer->transformPage($page, [$brand]),
            'brand' => $brand,
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlType}", name="obd2_forum_choice_code")
     */
    public function showChoiceSparePartPageAction(Request $request, $urlBrand, $urlType, VariableTransformer $transformer)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $type = $em->getRepository(TypeOBD2Error::class)->findOneBy(["url" => $urlType]);

        if(!($brand instanceof Brand) || !($type instanceof TypeOBD2Error)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $form = $this->createForm(ErrorCodeSearchType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $text = $form->get("code")->getData();

            if(!$text || strlen($text) !== 4 || !ctype_digit($text)){
                $form->get("code")->addError(new FormError("Неверный код. Код должен состоять из 4 цифр."));
            }
            else {

                $code = $em->getRepository(CodeOBD2Error::class)->findOneBy([
                    "code" => $text,
                    "type" => $type,
                ]);

                if (!($code instanceof CodeOBD2Error)) {
                    $form->get("code")->addError(new FormError("Данная ошибка не найдена <br/> Возможно она пока не внесена в базу"));

                    $userCode = $em->getRepository(UserOBD2ErrorCode::class)->findOneBy([
                        "code" => $text,
                        "type" => $type,
                    ]);

                    if (!($userCode instanceof UserOBD2ErrorCode)) {
                        $newUserCode = new UserOBD2ErrorCode($text, $type, $this->getUser());

                        $em->persist($newUserCode);
                    } else {
                        $userCode->increaseCounter();
                    }

                    $em->flush();
                } else {
                    $code->increaseCounter();

                    $em->flush();

                    return $this->redirectToRoute("obd2_forum_choice_model",
                        ["urlBrand" => $brand->getUrl(), "urlType" => $type->getUrl(), "urlCode" => $code->getUrl()]);
                }
            }
        }

        $page = $em->getRepository(OBD2ForumChoiceCode::class)->findAll()[0];

        return $this->render('client/forum/obd2-forum/choice-code.html.twig', [
            'page' => $transformer->transformPage($page, [$brand, $type]),
            "type" => $type,
            "brand" => $brand,
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlType}/{urlCode}", name="obd2_forum_choice_model")
     */
    public function showChoiceCityPageAction(Request $request, $urlBrand, $urlType, $urlCode, VariableTransformer $transformer)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brand::class)->findOneBy(["url" => $urlBrand]);
        $type = $em->getRepository(TypeOBD2Error::class)->findOneBy(["url" => $urlType]);
        $code = $em->getRepository(CodeOBD2Error::class)->findOneBy(["url" => $urlCode]);

        if(!($brand instanceof Brand) || !($type instanceof TypeOBD2Error) || !($code instanceof CodeOBD2Error)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(OBD2ForumChoiceModel::class)->findAll()[0];

        $models = $em->getRepository(Model::class)->findBy(["brand" => $brand], ["name" => "ASC"]);

        $parsedModels = [];

        /** @var Model $model */
        foreach ($models as $model) {
            $parsedModels[$model->getUrl()] = $model->getName();
        }

        return $this->render('client/forum/obd2-forum/choice-model.html.twig', [
            'page' => $transformer->transformPage($page, [$brand, $type, $code]),
            'brand' => $brand,
            'type' => $type,
            'code' => $code,
            'models' => $parsedModels,
        ]);
    }

    /**
     * @Route("/{urlBrand}/{urlType}/{urlCode}/{urlModel}", name="obd2_forum_final_page")
     */
    public function showCatalogInStockAction(Request $request, $urlBrand, $urlType, $urlCode, $urlModel, VariableTransformer $transformer)
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

        if(!($brand instanceof Brand) || !($model instanceof Model) ||
            !($type instanceof TypeOBD2Error) || !($code instanceof CodeOBD2Error)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(OBD2ForumFinalPage::class)->findAll()[0];

        return $this->render('client/forum/obd2-forum/final-page.html.twig', [
            'page' => $transformer->transformPage($page, [$brand, $type, $code, $model]),
            'brand' => $brand,
            'type' => $type,
            'code' => $code,
            'model' => $model,
        ]);
    }
}