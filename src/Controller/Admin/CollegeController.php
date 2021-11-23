<?php

namespace App\Controller\Admin;

use App\Entity\Admin\College;
use App\Form\Admin\CollegeType;
use App\Repository\Admin\CollegeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollegeController extends AbstractController
{
    /**
     * @Route("/op_admin/college", name="op_admin_college_index", methods={"GET"})
     */
    public function index(CollegeRepository $collegeRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $collegeRepository->findAll();

        $college = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('admin/college/index.html.twig', [
            'colleges' => $college,
        ]);
    }

    /**
     * @Route("/webapp/college/articles/{iduser}", name="op_webapp_college_espace_article", methods={"GET"})
     */
    public function collegebyuser(CollegeRepository $collegeRepository, Request $request): Response
    {
        $iduser = $this->getUser()->getId();

        $college = $collegeRepository->CollegeByUser($iduser);

        return $this->render('admin/college/collegebyuser.html.twig', [
            'college' => $college,
        ]);
    }

    /**
     * @Route("/webapp/college/newcollege", name="op_webapp_college_newcollege", methods={"GET","POST"})
     */
    public function newcollege(Request $request): Response
    {
        $user = $this->getUser();
        $college = new College();
        $form = $this->createForm(CollegeType::class, $college);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($college);
            $entityManager->flush();

            return $this->redirectToRoute('op_webapp_college_espcoll');
        }

        return $this->render('admin/college/new.html.twig', [
            'college' => $college,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/op_admin/college/new", name="op_admin_college_new", methods={"GET","POST"})
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

            return $this->redirectToRoute('op_admin_college_index');
        }

        return $this->render('admin/college/new.html.twig', [
            'college' => $college,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/op_admin/college/{id}", name="op_admin_college_show", methods={"GET"})
     */
    public function show(College $college): Response
    {
        return $this->render('admin/college/show.html.twig', [
            'college' => $college,
        ]);
    }

    /**
     * Affiche un collège depuis la page des collèges
     * @Route("/webapp/college/blog/{id}", name="op_webapp_college_show2", methods={"GET"})
     */
    public function show2(College $college): Response
    {
        return $this->render('admin/college/show2.html.twig', [
            'college' => $college,
        ]);
    }

    /**
     * Affiche le bloc d'admin des collèges leur espace privé
     * @Route("/webapp/college/bloc_admin/", name="op_webapp_college_adminonly", methods={"GET"})
     */
    public function blocAdminCollege(College $college): Response
    {
        return $this->render('admin/college/include/_blocAdminCollege.html.twig', [
            'college' => $college,
        ]);
    }

    /**
     * @Route("/op_admin/college/{id}/edit", name="op_admin_college_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, College $college): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(CollegeType::class, $college);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_admin_college_index');
        }

        return $this->render('admin/college/edit.html.twig', [
            'college' => $college,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/webapp/college/{id}/editcollege", name="op_webapp_college_edit", methods={"GET","POST"})
     */
    public function editCollege(Request $request, College $college): Response
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

        return $this->render('admin/college/editcollege.html.twig', [
            'college' => $college,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/op_admin/college/{id}", name="op_admin_college_delete", methods={"DELETE"})
     */
    public function delete(Request $request, College $college): Response
    {
        if ($this->isCsrfTokenValid('delete'.$college->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($college);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_admin_college_index');
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
     * @Route("webapp/college/espace/{iduser}", name="op_webapp_college_espcoll")
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
