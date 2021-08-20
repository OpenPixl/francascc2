<?php

namespace App\Controller\Admin;


use App\Repository\Webapp\ArticlesRepository;
use App\Repository\Webapp\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="op_admin_dashboard_index")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard/index.html.twig');
    }
}
