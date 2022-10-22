<?php

namespace App\Controller\Admin;

use App\Entity\Admin\College;
use App\Entity\Admin\User;
use App\Form\Admin\CollegeType;
use App\Repository\Admin\CollegeRepository;
use Doctrine\ORM\EntityManagerInterface;
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

        // on récupére l'objet user de l'administrateur en cours
        //$iduser = $this->getUser()->getId();
        //$user = $this->getDoctrine()->getRepository(User::class)->find($iduser);
        // on crée l'instance College depuis la classe "College" et on injecte l'admin en cours
        $college = new College();
        //$college->setUser($user);
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
     * @Route("/admin/college/newcollegeAdmin/{iduser}", name="op_admin_college_newcollegeadmin", methods={"GET","POST"})
     */
    public function newcollegeAdmin(Request $request, $iduser): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($iduser);

        $college = new College();
        $college->setUser($user);
        $form = $this->createForm(CollegeType::class, $college);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($college);
            $entityManager->flush();

            return $this->redirectToRoute('op_admin_user_index');
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
        $college->setUser($user);
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

            return $this->redirectToRoute('op_webapp_college_edit',[
                'id' => $user->getId(),
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
     * Suppression d'une ligne dans le index.php
     * @Route("/op_admin/college/del/{id}", name="op_admin_college_del", methods={"POST"})
     */
    public function Del(Request $request, College $college) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($college);
        $entityManager->flush();

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->json([
            'code'=> 200,
            'message' => "Le college a été supprimé",
            'liste' => $this->renderView('admin/user/include/_liste.html.twig', [
                'users' => $users
            ])
        ], 200);
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

    /**
     * Permet de mettre en menu la poge ou non
     * @Route("/op_admin/college/verified/{id}", name="op_admin_college_verified")
     */
    public function jsVerified(College $college, EntityManagerInterface $em) : Response
    {
        $admin = $this->getUser();
        $isActive = $college->getIsActive();
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$admin) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        // Si la page est déja publiée, alors on dépublie
        if($isActive == true){
            $college->setIsActive(0);
            $em->flush();
            return $this->json(['code'=> 200, 'message' => "Le college est désactivé pour l'instant"], 200);
        }
        // Si la page est déja dépubliée, alors on publie
        $college->setIsActive(1);
        $em->flush();
        return $this->json([
            'code'=> 200,
            'message' => "Le college est activé et sera visible sur le site."],
            200);
    }
}
