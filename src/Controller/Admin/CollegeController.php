<?php

namespace App\Controller\Admin;

use App\Entity\Admin\College;
use App\Form\Admin\CollegeType;
use App\Repository\Admin\CollegeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/webapp/college", name="op_webapp_college")
 */
class CollegeController extends AbstractController
{
    /**
     * @Route("/", name="_index", methods={"GET"})
     */
    public function index(CollegeRepository $collegeRepository): Response
    {
        return $this->render('admin/college/index.html.twig', [
            'colleges' => $collegeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        $college = new College();
        $form = $this->createForm(CollegeType::class, $college);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($college);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_college_espcoll',[
                'iduser' => $user->getId(),
            ]);
        }

        return $this->render('admin/college/new.html.twig', [
            'college' => $college,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="_show", methods={"GET"})
     */
    public function show(College $college): Response
    {
        return $this->render('admin/college/show.html.twig', [
            'college' => $college,
        ]);
    }

    /**
     * Affiche un collège depuis la page des collèges
     * @Route("/blog/{id}", name="_show2", methods={"GET"})
     */
    public function show2(College $college): Response
    {
        return $this->render('admin/college/show2.html.twig', [
            'college' => $college,
        ]);
    }

    /**
     * Affiche le bloc d'admin des collèges leur espace privé
     * @Route("/bloc_admin/", name="_adminonly", methods={"GET"})
     */
    public function blocAdminCollege(College $college): Response
    {
        return $this->render('admin/college/include/_blocAdminCollege.html.twig', [
            'college' => $college,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, College $college): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(CollegeType::class, $college);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_webapp_college_espcoll',[
                'iduser' => $user->getId(),
            ]);
        }

        return $this->render('admin/college/edit.html.twig', [
            'college' => $college,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="_delete", methods={"DELETE"})
     */
    public function delete(Request $request, College $college): Response
    {
        if ($this->isCsrfTokenValid('delete'.$college->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($college);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_college_index');
    }

    /**
     * @Route("/section/{idsection}", name="_collegesbysection", methods={"GET"})
     */
    public function listCollegesBySection($idsection): Response
    {
        $colleges = $this
            ->getDoctrine()
            ->getRepository(College::class)
            ->listCollegesBySection($idsection)
        ;

        return $this->render('admin/college/listcollegesbysection.html.twig',[
            'colleges' => $colleges,
        ]);
    }

    /**
     * @param $iduser
     * @return Response
     * @Route("/espace/{iduser}", name="_espcoll")
     */
    public function findCollegeById($iduser): Response
    {
        $college = $this
            ->getDoctrine()
            ->getRepository(College::class)
            ->CollegeByUser($iduser)
        ;
        if (!$college) {
            $this->redirectToRoute('admin');
        }

        return $this->render('admin/college/collegebyuser.html.twig', [
            'college' => $college,
        ]);
    }
}
