<?php

namespace App\Controller\App;

use App\Entity\Admin\College;
use App\Entity\Admin\Config;
use App\Entity\Webapp\Message;
use App\Entity\Webapp\Page;
use App\Entity\Webapp\Section;
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
        return $this->redirectToRoute('op_webapp_public_homepage');
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
     * @Route("/home", name="_public_homepage")
     */
    public function HomePage()
    {
        $config = $this->getDoctrine()->getRepository(Config::class)->find(1);
        $sections = $this->getDoctrine()->getRepository(Section::class)->findBy(array('favorites' => 1), array('position' => 'ASC'));
        //dd($sections);
        return $this->render('webapp/public/index.html.twig',[
            'config' => $config,
            'sections' => $sections,
        ]);
    }
    /**
     * Page React de connexion de l'espace college (espcoll)
     * @Route("/espcoll/dashboard/", name="_espcoll")
     */
    public function epscoll() : Response
    {
        $user = $this->getUser();
        $college = $this
            ->getDoctrine()
            ->getRepository(College::class)
            ->CollegeByUser($user);

        $messages = $this->getDoctrine()->getRepository(Message::class)->listMessagesByUser($user->getId());

        return $this->render('espacecollege/dashboard/espcoll.html.twig', [
            'college' => $college,
            'messages' => $messages
        ]);
    }
}
