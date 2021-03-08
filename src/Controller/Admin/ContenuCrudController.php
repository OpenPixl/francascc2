<?php

namespace App\Controller\Admin;

use App\Entity\Webapp\Contenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ContenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contenu::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
