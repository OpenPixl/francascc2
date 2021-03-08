<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Config;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ConfigCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Config::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom du site'),
            TextEditorField::new('description'),
            BooleanField::new('isOffLine', 'mettre le site hors ligne'),
            ImageField::new('logoFile', 'Logo')
                ->setFormType(VichImageType::class)
                ->hideOnIndex(),
            FormField::addPanel('Paramètre du Bandeau'),
            BooleanField::new('isHeaderShow', 'Afficher le header ?' )
                ->hideOnIndex(),
            ImageField::new('headerFile', 'Image du header')
                ->setFormType(VichImageType::class)
                ->hideOnIndex(),
        ];
    }
}
