<?php

namespace App\Controller\Admin;

use App\Entity\Webapp\Page;
use App\Entity\Webapp\Section;
use App\Form\Webapp\SectionType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Security\Core\Security;

class PageCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function createEntity(string $entityFqcn)
    {
        $page = new Page();
        $page->setAuthor($this->getUser());

        return $page;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('La page'),
            TextField::new('title', 'Titre'),
            SlugField::new('slug', 'slug')
                ->setTargetFieldName('title'),
            TextEditorField::new('intro', 'Introduction'),
            AssociationField::new('sections'),
            FormField::addPanel('Métadonnées'),
            CollectionField::new('metaKeywords', 'Mots clefs'),
            TextEditorField::new('metaDescription', 'Description'),
            FormField::addPanel('Options'),
            ChoiceField::new('state', 'Etat')
                ->setChoices([
                    'publiée'=>'publiee',
                    'brouillon'=>'brouillon'
                ]),
            BooleanField::new('isMenu', 'Faire de cette page un menu ?'),
            BooleanField::new('isTitleShow', 'Afficher le titre ?')->hideOnIndex(),
            BooleanField::new('isIntroShow', "Afficher l'intro ?")->hideOnIndex(),

            DateField::new('publishAt', 'Début de publication'),
            DateField::new('publishEnd', 'Fin de publication'),

        ];
    }

}
