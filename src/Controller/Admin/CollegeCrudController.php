<?php

namespace App\Controller\Admin;

use App\Entity\Admin\College;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CollegeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return College::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $college = new College();
        $college->setUser($this->getUser());

        return $college;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel("Informations liées à l'établissement"),
            TextField::new('name', "Nom de l'établissement"),
            TextField::new('address', "Adresse")
                ->hideOnIndex(),
            TextField::new('complement', "complement")
                ->hideOnIndex(),
            TextField::new('zipcode', "Code Postal")
                ->hideOnIndex(),
            TextField::new('city', "Commune"),
            EmailField::new('collegeEmail', "Email")
                ->hideOnIndex(),
            TextField::new('collegePhone', "Téléphone"),
            FormField::addPanel("Informations liées à l'établissement"),
            TextField::new('animateur', "Référent"),
            TextEditorField::new('GroupDescription', "Texte de présentation")
                ->hideOnIndex(),
            ImageField::new('logoFile', 'Logo du groupe')
                ->setFormType(VichImageType::class)
                ->hideOnIndex(),
            ImageField::new('headerFile', 'Bandeau du groupe')
                ->setFormType(VichImageType::class)
                ->hideOnIndex(),
            BooleanField::new('isActive', 'College actif dans le dispositif')
        ];
    }

}
