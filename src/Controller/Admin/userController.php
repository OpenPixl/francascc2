<?php

namespace App\Controller\Admin;

use App\Controller\Webapp\ArticlesController;
use App\Entity\Admin\User;
use App\Entity\Webapp\Articles;
use App\Form\Admin\userEditType;
use App\Form\Admin\userType;
use App\Repository\Admin\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user")
 */
class userController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="op_admin_user_index", methods={"GET"})
     */
    public function index(userRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $userRepository->findAllUsersWithCollege();
        $users = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/new", name="op_admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new user();
        $form = $this->createForm(userType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('op_admin_user_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="op_admin_user_show", methods={"GET"})
     */
    public function show(user $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="op_admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, user $user): Response
    {
        $form = $this->createForm(userEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('op_admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="op_admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, user $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('op_admin_user_index');
    }

    /**
     * Permet de mettre en menu la poge ou non
     * @Route("/op_admin/user/verified/{id}", name="op_admin_user_verified")
     */
    public function jsVerified(User $user, EntityManagerInterface $em) : Response
    {
        $admin = $this->getUser();
        $isActiv = $user->getIsActiv();
        // renvoie une erreur car l'utilisateur n'est pas connecté
        if(!$admin) return $this->json([
            'code' => 403,
            'message'=> "Vous n'êtes pas connecté"
        ], 403);
        // Si la page est déja publiée, alors on dépublie
        if($isActiv == true){
            $user->setIsActiv(0);
            $em->flush();
            return $this->json(['code'=> 200, 'message' => "L'utilisateur n'accède plus à l'administration"], 200);
        }
        // Si la page est déja dépubliée, alors on publie
        $user->setIsActiv(1);
        $em->flush();
        return $this->json([
            'code'=> 200,
            'message' => "L'utilisateur accède à l'administration"],
            200);
    }

    /**
     * Suppression d'une ligne index.php
     * @Route("/del/{id}", name="op_admin_user_del", methods={"POST"})
     */
    public function Del(Request $request, User $user) : Response
    {
        // Supression des articles liés à l'user
        $articles = $this->getDoctrine()->getRepository(Articles::class)->findBy(['author' => $user]);
        foreach ($articles as $article){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }
        // supression de l'utilisateur
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->json([
            'code'=> 200,
            'message' => "L'évènenemt a été supprimé",
            'liste' => $this->renderView('admin/user/include/_liste.html.twig', [
                'users' => $users
            ])
        ], 200);
    }
}
