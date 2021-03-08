<?php

namespace App\Controller\Webapp;

use App\Entity\Admin\Config;
use App\Entity\Webapp\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PageController
 * @package App\Controller\Webapp
 * @Route("/webapp/page", name="op_webapp_page_")
 */
class PageController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('webapp/page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    /**
     * Liste toutes les pages de menu
     * @Route("/listmenu", name="index")
     */
    public function listmenu()
    {
        $config = $this->getDoctrine()->getRepository(Config::class)->find(1);
        $pages = $this->getDoctrine()
            ->getRepository(Page::class)
            ->ListMenu();

        return $this->render('webapp/page/listmenu.html.twig',[
            'pages' => $pages,
            'config'=> $config,
        ]);
    }


}
