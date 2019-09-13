<?php

namespace App\Controller\Catalog;
use App\Entity\Admin;
use App\Entity\Advert\AutoSparePart\AutoSparePartGeneralAdvert;
use App\Entity\Article\Article;
use App\Entity\Brand;
use App\Entity\Catalog\Brand\CatalogBrandChoiceBrand;
use App\Entity\Catalog\Brand\CatalogBrandChoiceCity;
use App\Entity\Catalog\Brand\CatalogBrandChoiceFinalPage;
use App\Entity\Catalog\Brand\CatalogBrandChoiceInStock;
use App\Entity\Catalog\Brand\CatalogBrandChoiceModel;
use App\Entity\Catalog\Brand\CatalogBrandChoiceSparePart;
use App\Entity\Catalog\OBD2Error\CatalogOBD2ErrorChoiceCode;
use App\Entity\Catalog\OBD2Error\CatalogOBD2ErrorChoiceReason;
use App\Entity\Catalog\OBD2Error\CatalogOBD2ErrorChoiceTranscript;
use App\Entity\Catalog\OBD2Error\CatalogOBD2ErrorChoiceType;
use App\Entity\City;
use App\Entity\Error\CodeOBD2Error;
use App\Entity\Error\TypeOBD2Error;
use App\Entity\General\MainPage;
use App\Entity\General\NotFoundPage;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Entity\UserData\UserOBD2ErrorCode;
use App\Form\Type\ErrorCodeSearchType;
use App\Provider\InfoPageProvider;
use App\Transformer\VariableTransformer;
use App\Type\ArticleFilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/obd2")
 */
class OBD2ErrorCatalogController extends Controller
{
    /**
     * @Route("", name="show_obd2_error_catalog_choice_type")
     */
    public function showChoiceTypePageAction(Request $request)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $titleHomepage = $em->getRepository(MainPage::class)->findAll()[0]->getTitle();

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

        $updatedArticles = $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_CREATED, [], 12, 0, false));

        return $this->render('client/catalog/obd2-error/choice-type.html.twig', [
            'page' => $em->getRepository(CatalogOBD2ErrorChoiceType::class)->findAll()[0],
            'titleHomepage' => $titleHomepage,
            'types' => $parsedTypes,
            "articles" => $updatedArticles,
        ]);
    }

    /**
     * @Route("/{urlType}", name="show_obd2_error_catalog_choice_code")
     */
    public function showChoiceCodePageAction(Request $request, $urlType, VariableTransformer $transformer)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository(TypeOBD2Error::class)->findOneBy(["url" => $urlType]);

        if(!($type instanceof TypeOBD2Error)){
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

                    return $this->redirectToRoute("show_obd2_error_catalog_choice_transcript",
                        ["urlType" => $type->getUrl(), "urlCode" => $code->getUrl()]);
                }
            }
        }

        $page = $em->getRepository(CatalogOBD2ErrorChoiceCode::class)->findAll()[0];

        $updatedArticles = $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_CREATED, [], 12, 0, false));

        return $this->render('client/catalog/obd2-error/choice-code.html.twig', [
            'page' => $transformer->transformPage($page, [$type]),
            "titleHomepage" => $em->getRepository(MainPage::class)->findAll()[0]->getTitle(),
            "titleChoiceType" => $em->getRepository(CatalogOBD2ErrorChoiceType::class)->findAll()[0]->getTitle(),
            "type" => $type,
            "form" => $form->createView(),
            "articles" => $updatedArticles,
        ]);
    }

    /**
     * @Route("/{urlType}/{urlCode}", name="show_obd2_error_catalog_choice_transcript")
     */
    public function showChoiceTranscriptPageAction(Request $request, $urlType, $urlCode, VariableTransformer $transformer)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository(TypeOBD2Error::class)->findOneBy(["url" => $urlType]);
        $code = $em->getRepository(CodeOBD2Error::class)->findOneBy([
            "url" => $urlCode,
            "type" => $type,
        ]);

        $code = $code ?: CodeOBD2Error::getAbsentCode($type, $urlCode);

        if(!($type instanceof TypeOBD2Error) || !($code instanceof CodeOBD2Error)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $page = $em->getRepository(CatalogOBD2ErrorChoiceTranscript::class)->findAll()[0];
        $transformParameters = [$type, $code];
        $pageTransformed = $transformer->transformPage($page, $transformParameters);
        $pageTransformed->setText3($transformer->transformPage($page->getText3(), $transformParameters));

        $updatedArticles = $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_CREATED, [], 12, 0, false));

        return $this->render('client/catalog/obd2-error/choice-transcript.html.twig', [
            'page' => $pageTransformed,
            "titleHomepage" => $em->getRepository(MainPage::class)->findAll()[0]->getTitle(),
            "titleChoiceType" => $em->getRepository(CatalogOBD2ErrorChoiceType::class)->findAll()[0]->getTitle(),
            "titleChoiceCode" => $transformer->transformPage($em->getRepository(CatalogOBD2ErrorChoiceCode::class)->findAll()[0]->getTitle(), $transformParameters),
            "type" => $type,
            "code" => $code,
            "articles" => $updatedArticles,
        ]);
    }

    /**
     * @Route("/{urlType}/{urlCode}/cause", name="show_obd2_error_catalog_choice_reason")
     */
    public function showChoiceReasonPageAction(Request $request, $urlType, $urlCode, VariableTransformer $transformer, InfoPageProvider $provider)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository(TypeOBD2Error::class)->findOneBy(["url" => $urlType]);
        $code = $em->getRepository(CodeOBD2Error::class)->findOneBy([
            "url" => $urlCode,
            "type" => $type,
        ]);

        $code = $code ?: CodeOBD2Error::getAbsentCode($type, $urlCode);

        if(!($type instanceof TypeOBD2Error) || !($code instanceof CodeOBD2Error)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        /** @var CatalogOBD2ErrorChoiceReason $page */
        $page = $em->getRepository(CatalogOBD2ErrorChoiceReason::class)->findAll()[0];

        $transformParameters = [$type, $code];
        $pageTransformed = $transformer->transformPage($page, $transformParameters);
        $pageTransformed->setText3($transformer->transformPage($page->getText3(), $transformParameters));
        $pageTransformed->setReturnButtonLink($transformer->transformPage($page->getReturnButtonLink(), $transformParameters));
        $pageTransformed->setReturnButtonText($transformer->transformPage($page->getReturnButtonText(), $transformParameters));

        $updatedArticles = $em->getRepository(Article::class)
            ->findAllByFilter(new ArticleFilterType(ArticleFilterType::SORT_CREATED, [], 12, 0, false));

        return $this->render('client/catalog/obd2-error/choice-reason.html.twig', [
            'page' => $pageTransformed,
            "titleHomepage" => $em->getRepository(MainPage::class)->findAll()[0]->getTitle(),
            "titleChoiceType" => $em->getRepository(CatalogOBD2ErrorChoiceType::class)->findAll()[0]->getTitle(),
            "titleChoiceCode" => $transformer->transformPage($em->getRepository(CatalogOBD2ErrorChoiceCode::class)->findAll()[0]->getTitle(), $transformParameters),
            "titleChoiceTranscript" => $transformer->transformPage($em->getRepository(CatalogOBD2ErrorChoiceTranscript::class)->findAll()[0]->getTitle(), $transformParameters),
            "type" => $type,
            "code" => $code,
            "brands" => $provider->getBrands(),
            "articles" => $updatedArticles,
        ]);
    }

    /**
     * @Route("/ajax/check-code/{urlType}", name="ajax_check_obd2_code")
     */
    public function ajaxCheckOBD2CodeAction(Request $request, $urlType, VariableTransformer $transformer)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository(TypeOBD2Error::class)->findOneBy(["url" => $urlType]);

        if(!($type instanceof TypeOBD2Error)){
            throw new NotFoundHttpException(NotFoundPage::DEFAULT_MESSAGE);
        }

        $form = $this->createForm(ErrorCodeSearchType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $text = $form->get("code")->getData();

            if(!$text || strlen($text) !== 4 || !ctype_digit($text)){
                $form->get("code")->addError(new FormError("Номер OBD2 ошибки всегда содержит 4 символа, пожалуйста проверьте корректность."));
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

                    return new JsonResponse([
                        "success" => true,
                        "urlCode" => $code->getUrl(),
                    ]);
                }
            }
        }

        return $this->render('client/forum/obd2-forum/forms/code-form.twig', [
            "form" => $form->createView(),
            "type" => $type,
        ]);
    }
}