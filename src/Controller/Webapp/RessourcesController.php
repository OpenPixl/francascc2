<?php

namespace App\Controller\Webapp;

use App\Entity\Admin\College;
use App\Entity\Webapp\RessourceCat;
use App\Entity\Webapp\Ressources;
use App\Form\Webapp\RessourcesType;
use App\Repository\Webapp\RessourcesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 */
class RessourcesController extends AbstractController
{
    /**
     * @Route("/admin/ressources", name="op_webapp_ressources_index", methods={"GET"})
     */
    public function index(RessourcesRepository $ressourcesRepository): Response
    {
        return $this->render('webapp/ressources/index.html.twig', [
            'ressources' => $ressourcesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/webapp/ressources", name="op_webapp_ressources_sectionlistall", methods={"GET"})
     */
    public function sectionListAll(Request $request, PaginatorInterface $paginator): Response
    {
        $categories = $this->getDoctrine()->getRepository(RessourceCat::class)->findAll();

        $data = $this->getDoctrine()->getRepository(Ressources::class)->findAll();
        $ressources = $paginator->paginate(
            $data, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('webapp/ressources/sectionlistall.html.twig', [
            'categories' => $categories,
            'ressources' => $ressources,
            'page' => $request->query->getInt('page', 1),
        ]);
    }

    /**
     * @Route("/webapp/ressources/filter", name="op_webapp_ressources_filter", methods={"GET","POST"})
     */
    public function filter(Request $request, RessourcesRepository $ressourcesRepository,PaginatorInterface $paginator): Response
    {
        $filters = $request->get("categories");
        $data = $ressourcesRepository->listFilters($filters);

        $ressources = $paginator->paginate(
            $data, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->json([
            'code'      => 200,
            'message'   => "Ok",
            'liste' => $this->renderView('webapp/ressources/include/_contentSectionList.html.twig', [
                'ressources' => $ressources,
                'page' => $request->query->getInt('page', 1),
            ])
        ], 200);
    }

    /**
     * @Route("/webapp/ressources/{category}", name="op_webapp_ressources_sectionlistall", methods={"GET"})
     */
    public function sectionlistOneCategory(Request $request, PaginatorInterface $paginator, $category): Response
    {
        $ressourcecat = $this->getDoctrine()->getRepository(RessourceCat::class)->find($category);
        $category = $ressourcecat->getId();

        $data = $this->getDoctrine()->getRepository(Ressources::class)->ListByCategory($category);

        $ressources = $paginator->paginate(
            $data, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('webapp/ressources/sectiononecategory.html.twig', [
            'ressources' => $ressources,
            'page' => $request->query->getInt('page', 1),
        ]);
    }



    /**
     * @Route("/admin/ressources/new", name="op_webapp_ressources_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ressource = new Ressources();
        $form = $this->createForm(RessourcesType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ressource);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_ressources_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('webapp/ressources/new.html.twig', [
            'ressource' => $ressource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/ressources/collegenew/{idcollege}", name="op_webapp_ressources_collegenew", methods={"GET","POST"})
     */
    public function collegeNew($idcollege, Request $request): Response
    {
        // On récupère l'entité collège
        $college = $this->getDoctrine()->getRepository(College::class)->find($idcollege);

        $ressource = new Ressources();
        $ressource->setCollege($college);
        $form = $this->createForm(RessourcesType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ressource);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_ressources_index', [
                'id' => $college->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('webapp/ressources/newressourcebycollege.html.twig', [
            'college' => $college,
            'ressource' => $ressource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/ressources/{id}", name="op_webapp_ressources_show", methods={"GET"})
     */
    public function show(Ressources $ressource): Response
    {
        return $this->render('webapp/ressources/show.html.twig', [
            'ressource' => $ressource,
        ]);
    }

    /**
     * @Route("/admin/ressources/{id}/edit", name="op_webapp_ressources_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ressources $ressource): Response
    {
        $form = $this->createForm(RessourcesType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_webapp_ressources_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('webapp/ressources/edit.html.twig', [
            'ressource' => $ressource,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/ressources/{id}", name="op_webapp_ressources_delete", methods={"POST"})
     */
    public function delete(Request $request, Ressources $ressource): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ressource->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ressource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('webapp_ressources_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Affiche une ressource dans le frontend
     * @Route("/webapp/ressources/show/{slug}/{page}", name="op_webapp_ressources_ressourceshow", methods={"GET"})
     */
    public function RessourceShow($slug, $page, Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(RessourceCat::class)->findAll();

        $ressource = $this->getDoctrine()->getRepository(Ressources::class)->ressourceSlug($slug);


        return $this->render('webapp/ressources/ressourceshow.html.twig', [
            'ressource' => $ressource,
            'categories' => $categories,
            'page' => $page
        ]);
    }

    /**
     * Suppression d'une ligne index.php
     * @Route("/webapp/ressources/del/{id}", name="op_webapp_ressources_del", methods={"POST"})
     */
    public function DelEvent(Request $request, Ressources $ressource, PaginatorInterface $paginator) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($ressource);
        $entityManager->flush();

        $data = $this->getDoctrine()->getRepository(Ressources::class)->findAll();
        $ressources = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            15
        );

        return $this->json([
            'code'=> 200,
            'message' => "La ressource a été supprimé",
            'liste' => $this->renderView('webapp/ressources/include/_liste.html.twig', [
                'ressources' => $ressources,
                'page' => $request->query->getInt('page', 1),
            ]),

        ], 200);
    }
}
