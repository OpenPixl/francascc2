<?php

namespace App\Controller\App;

use App\Entity\Admin\College;
use App\Entity\Admin\Config;
use App\Entity\Webapp\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController
 * @package App\Controller\App
 * @Route("/", name="op_webapp")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="_home")
     */
    public function index()
    {
        $config = $this->getDoctrine()->getRepository(Config::class)->find(1);
        if(!$config)
        {
            return $this->redirectToRoute('op_webapp');
        }
        return $this->redirectToRoute('op_webapp_homepage');
    }

    /**
     * Affiche la page de menu
     * @Route("/app/{slug}", name="_page")
     */
    public function showPage($slug)
    {
        $page = $this->getDoctrine()
            ->getRepository(Page::class)
            ->findOneBy(['slug' => $slug]);

        if (!$page) {
            throw $this->createNotFoundException(
                "La page n'existe pas" .$slug
            );
        }


        return $this->render('webapp/page/page.html.twig', [
            'page' => $page
        ]);
    }

    /**
     * Affiche automatiquement la page d'acceuil
     * @Route("/home", name="_homepage")
     */
    public function HomePage()
    {
        $page = $this->getDoctrine()
            ->getRepository(Page::class)
            ->findOneBy(['id' => 4]);

        if (!$page) {
            throw $this->createNotFoundException(
                "La page n'existe pas"
            );
        }


        return $this->render('webapp/page/page.html.twig', [
            'page' => $page
        ]);
    }
    /**
     * Page React de connexion de l'espace college (espcoll)
     * @Route("/webapp/espcoll/", name="_espcoll")
     */
    public function epscoll() : Response
    {
        $user = $this->getUser();
        $college = $this
            ->getDoctrine()
            ->getRepository(College::class)
            ->CollegeByUser($user);

        return $this->render('app/espcoll.html.twig', [
            'college' => $college,
        ]);
    }
}
