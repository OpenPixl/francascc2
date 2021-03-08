<?php

namespace App\Controller\Admin;

use App\Entity\Admin\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class User2CrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            EmailField::new('Email', 'email'),
            TextField::new('loginName', 'Surnom'),
            TextField::new('password')
                ->hideOnForm(),
            CollectionField::new('Roles'),
            AssociationField::new('college', 'College'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $createUser = Action::new('Créer', 'fas fa-file-invoice')
            ->linkToCrudAction('user2CrudController');

        // in PHP 7.4 and newer you can use arrow functions
        // ->displayIf(fn ($entity) => $entity->isPublished())

        return $actions
            // ...
            ->add(Crud::PAGE_NEW, $createUser);
    }
}
