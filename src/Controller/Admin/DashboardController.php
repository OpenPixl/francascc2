<?php

namespace App\Controller\Admin;

use App\Entity\Admin\College;
use App\Entity\Admin\Config;
use App\Entity\Admin\User;
use App\Entity\Webapp\Articles;
use App\Entity\Webapp\Category;
use App\Entity\Webapp\Page;
use App\Entity\Webapp\Section;
use App\Entity\Webapp\Support;
use App\Entity\Webapp\Theme;
use App\Entity\Webapp\Message;
use App\Repository\Webapp\ArticlesRepository;
use App\Repository\Webapp\CommentRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    protected $ArticleRepository;
    protected $CommentRepository;

    public function __construct(
        ArticlesRepository $ArticlesRepository,
        CommentRepository $Commentrepository
    )
    {
        $this->ArticleRepository = $ArticlesRepository;
        $this->CommentRepository = $Commentrepository;
    }

    /**
     * @Route("/admin", name="admin")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(): Response
    {
        return $this->render('bundles/EasyAdminBundle/welcome.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Collégiens-Citoyens');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Colleges', 'fa fa-file',College::class);
        yield MenuItem::section('Gestion');
        yield MenuItem::linkToCrud('pages', 'fa fa-pager',Page::class);
        yield MenuItem::linkToCrud('sections', 'fa fa-puzzle-piece',Section::class);
        yield MenuItem::subMenu('Contenu')->setSubItems([
            MenuItem::linkToCrud('Articles', 'fa fa-file-alt',Articles::class)->setController(ArticlesCrudController::class),
            MenuItem::linkToCrud('Code', 'fa fa-file-alt',Articles::class)->setController(OtherCrudController::class),
            MenuItem::linkToCrud('Colleges', 'fa fa-file-alt',Articles::class)->setController(ArticlesCollegesCrudController::class),
            MenuItem::linkToCrud('Category', 'fa fa-file-alt', Category::class)->setController(CategoryCrudController::class)
        ]);
        yield MenuItem::linkToCrud('Messages', 'fa fa-user', Message::class);
        yield MenuItem::section('Administration');
        yield MenuItem::linkToCrud( 'Utilisateur', 'fa fa-users', User::class)->setController(User2CrudController::class);
        yield MenuItem::linkToCrud( 'Configuration', 'fa fa-cogs', Config::class);
        yield MenuItem::linkToCrud( 'Supports', 'fa fa-cogs', Support::class);
        yield MenuItem::linkToCrud( 'Thématique', 'fa fa-cogs', Theme::class);
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
    }
}
