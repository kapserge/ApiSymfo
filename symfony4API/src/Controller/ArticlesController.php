<?php
namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ArticlesController extends FOSRestController
{
    private $articlesRepository;
    private $em;
    public function __construct(ArticleRepository $articlesRepository, EntityManagerInterface $em)
    {
        $this->articlesRepository = $articlesRepository;
        $this->em = $em;
    }

    public function testUser($user)
    {
        if ($this->getUser() === $user || in_array("ROLE_ADMIN",$this->getUser()->getRoles()) ) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }
    public function testUserDroit()
    {
        if (in_array("ROLE_ADMIN",$this->getUser()->getRoles()) ) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }


    public function PostError($validationErrors){
        $error = array("error :");
        /** @var ConstraintViolationListInterface $validationErrors */
        /** @var ConstraintViolation $constraintViolation */
        foreach ($validationErrors as $constraintViolation) {
            $message = $constraintViolation->getMessage();
            $propertyPath = $constraintViolation->getPropertyPath();
            array_push($error,$propertyPath.' => '.$message);
        }
        return $error;
    }

    /**
     *  @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="article")
     * @Rest\View(serializerGroups={"article"})
     */
    public function getArticlesAction()
    {
        if ($this->testUserDroit()){
            $articles = $this->articlesRepository->findAll();
        return $this->view($articles);
        } else {
            return new JsonResponse('tu n as pas les droits');
        }
    }
    /**
     *  @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="article")
     * @Rest\View(serializerGroups={"article"})
     */
    public function getArticleAction(Article $article)
    {
        if($this->testUser($article->getUser())) {
            return $this->view($article);
        } else {
            return new JsonResponse('tu n as pas les droits');
        }
    }
    /**
     *  @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="article")
     * @Rest\View(serializerGroups={"article"})
     * @Rest\Post("/articles")
     * @ParamConverter("article", converter="fos_rest.request_body")
     */
    public function postArticlesAction(Article $article, ConstraintViolationListInterface $validationErrors)
    {
        if(!($validationErrors->count() > 0) ) {
            $article->setUser($this->getUser());
            $this->em->persist($article);
            $this->em->flush();
            return $this->view($article);
        } else  {
            return new JsonResponse($this->PostError($validationErrors));
        }
    }
    /**
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="article")
     * @Rest\View(serializerGroups={"article"})
     */
    public function putArticleAction(int $id, Request $request, ValidatorInterface $validator)
    {
        $tl = $request->get('title');
        $dp = $request->get('description');
        $article = $this->articlesRepository->find($id);
        if ($tl) {
            $article->setTitle($tl);
        }
        if ($dp) {
            $article->setDescription($dp);
        }
        $this->em->persist($article);
        /** @var ConstraintViolationList $valisationErrors */
        $validationErrors = $validator->validate($article);
        if(!($validationErrors->count() > 0) ) {
            $this->em->flush();
        } else {
            return new JsonResponse($this->PostError($validationErrors));
        }
    }
    /**
     *  @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="article")
     * @Rest\View(serializerGroups={"article"})
     */
    public function deleteArticleAction(Article $article)
    {
        if ($this->testUser($article->getUser())) {
            $this->em->remove($article);
            $this->em->flush();
        } else {
            return new JsonResponse('tu n as pas les droits');
        }
    }
}