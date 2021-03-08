<?php

namespace App\Controller\Webapp;

use App\Entity\Admin\College;
use App\Entity\Webapp\Articles;
use App\Entity\Webapp\Section;
use App\Form\Webapp\ArticlesType;
use App\Repository\Webapp\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/webapp/articles", name="op_webapp_articles")
 */
class ArticlesController extends AbstractController
{
    /**
     * @Route("/", name="_index", methods={"GET"})
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('webapp/articles/index.html.twig', [
            'articles' => $articlesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        // récupération de l'objet college
        $college = $this
            ->getDoctrine()
            ->getRepository(College::class)
            ->CollegeByUser($user);

        $article = new Articles();
        $article->setAuthor($user);
        $article->setContent("...");
        $article->setCollege($college);
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_college_espcoll', [
                'iduser' => $user->getId(),
            ]);
        }

        return $this->render('webapp/articles/new.html.twig', [
            'article' => $article,
            'college' =>$college,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="_show", methods={"GET"})
     */
    public function show(Articles $article): Response
    {
        return $this->render('webapp/articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Articles $article): Response
    {
        $user = $this->getUser();
        $college = $this
            ->getDoctrine()
            ->getRepository(College::class)
            ->CollegeByUser($user);

        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_webapp_college_espcoll', [
                'iduser' => $user->getId(),
            ]);
        }

        return $this->render('webapp/articles/edit.html.twig', [
            'article' => $article,
            'college' =>$college,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Articles $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_webapp_articles_index');
    }

    /**
     * @Route("/section/{idsection}", name="_articlesbysection", methods={"GET"})
     */
    public function listArticlesBySection($idsection): Response
    {
        $articles = $this
            ->getDoctrine()
            ->getRepository(Articles::class)
            ->listArticlesBySection($idsection)
        ;

        return $this->render('webapp/articles/listarticlesbysection.html.twig',[
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/other/{idsection}", name="_articlesbysection", methods={"GET"})
     */
    public function listArticlesBySectionOther($idsection): Response
    {
        $articles = $this
            ->getDoctrine()
            ->getRepository(Articles::class)
            ->listArticlesBySection($idsection)
        ;

        return $this->render('webapp/articles/listarticlesbysectionother.html.twig',[
            'articles' => $articles,
        ]);
    }

    /**
     * Affiche les articles d'un college dans sa page
     * @Route("/college/{idcollege}", name="_articlesbycollege", methods={"GET"})
     */
    public function listArticlesByCollege($idcollege): Response
    {
        $articles = $this
            ->getDoctrine()
            ->getRepository(Articles::class)
            ->listArticlesByCollege($idcollege)
        ;

        return $this->render('webapp/articles/listarticlesbycollege.html.twig',[
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/college2/{idcollege}", name="_articlesbycollege", methods={"GET"})
     */
    public function listArticlesByPageCollege($idcollege): Response
    {
        $articles = $this
            ->getDoctrine()
            ->getRepository(Articles::class)
            ->listArticlesByCollege($idcollege)
        ;

        return $this->render('webapp/articles/listarticlesbypagecollege.html.twig',[
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/carousel/{category}", name="_five_articles", methods={"GET"})
     */
    public function listFiveArticles($category): Response
    {

        $articles = $this
            ->getDoctrine()
            ->getRepository(Articles::class)
            ->listFiveArticles($category)
        ;

        return $this->render('webapp/articles/listFiveArticles.html.twig',[
            'articles' => $articles,
        ]);
    }

    /**
     * Affiche un article depuis la page du collège
     * @Route("/slug/{slug}", name="_articleSlug", methods={"GET"})
     */
    public function articleCollegeSlug($slug): Response
    {
        // Code pour afficher l'article depuis le slug'
        $article = $this
            ->getDoctrine()
            ->getRepository(Articles::class)
            ->articleCollegeSlug($slug)
        ;
        return $this->render('webapp/articles/articleCollegeSlug.html.twig',[
            'article' => $article,
        ]);
    }
}
