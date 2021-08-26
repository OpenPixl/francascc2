<?php

namespace App\Controller\Webapp;

use App\Entity\Webapp\Page;
use App\Entity\Webapp\Section;
use App\Form\Webapp\SectionType;
use App\Repository\Webapp\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SectionController extends AbstractController
{
    /**
     * Liste toutes les sections
     * @Route("/webapp/section/", name="op_webapp_section_index", methods={"GET"})
     */
    public function index(SectionRepository $sectionRepository): Response
    {
        return $this->render('webapp/section/index.html.twig', [
            'sections' => $sectionRepository->findAll(),
        ]);
    }

    /**
     * Ajoute une section
     * @Route("/webapp/section/new", name="op_webapp_section_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('webapp_section_index');
        }

        return $this->render('webapp/section/new.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Affiche une section selon son Id
     * @Route("/webapp/section/{id}", name="op_webapp_section_show", methods={"GET"})
     */
    public function show(Section $section): Response
    {
        return $this->render('webapp/section/show.html.twig', [
            'section' => $section,
        ]);
    }

    /**
     * Edite une section selon son Id
     * @Route("/webapp/section/{id}/edit", name="op_webapp_section_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Section $section): Response
    {
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('webapp_section_index');
        }

        return $this->render('webapp/section/edit.html.twig', [
            'section' => $section,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/webapp/section/{id}", name="op_webapp_section_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Section $section): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($section);
            $entityManager->flush();
        }

        return $this->redirectToRoute('webapp_section_index');
    }

    /**
     * Liste toutes les sections de l'application liées à une page.
     * @Route("/listallsections/{page}", name="op_webapp_section_listallsections")
     */
    public function ListAllSections($page)
    {
        $sections = $this
            ->getDoctrine()
            ->getRepository(Section::class)
            ->ListAllSections($page)
        ;

        return $this->render('webapp/section/listsections.html.twig',[
            'sections' => $sections,
        ]);
    }

    /**
     * @Route("/webapp/section/webapp/section/bypage/{page}", name="op_webapp_section_bypage", methods={"GET"})
     */
    public function byPage(SectionRepository $sectionRepository, $page): Response
    {
        $element = $this->getDoctrine()->getRepository(Page::class)->find($page);

        return $this->render('webapp/section/bypage.html.twig', [
            'sections' => $sectionRepository->findbypage($page),
            'element' => $element,
        ]);
    }

    /**
     * Permet d'activer ou de désactiver la mise en vedette d'une section sur la page d'accueil
     * @Route("/webapp/section/jsstar/{id}/json", name="op_webapp_section_star")
     */
    public function jsstar(Section $section, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $isstar = $section->getFavorites();
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        // Si la page est déja publiée, alors on dépublie
        if($isstar == true){
            $section->setfavorites(0);
            $em->flush();
            return $this->json([
                'code'      => 200,
                'message'   => "La section n'est plus publiée sur la page d'acccueil."
            ], 200);
        }
        // Si la page est déja dépubliée, alors on publie
        $section->setFavorites(1);
        $em->flush();
        return $this->json([
            'code'          => 200,
            'message'       => "La section est publiée sur la page d'acccueil."
        ], 200);
    }
}
