<?php

namespace App\Controller\Admin;

use App\Entity\Webapp\Section;
use Doctrine\ORM\Mapping\Builder\AssociationBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;

class SectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Section::class;
    }
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('page')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Détails de la section'),
            TextField::new('name', 'nom de section'),
            TextEditorField::new('intro', 'Introduction de section')
                ->hideOnIndex(),
            TextField::new('descriptif', 'Descriptif'),
            AssociationField::new('page'),
            ChoiceField::new('content', 'Type de contenu')
                ->setChoices([
                    'Un article'=>'ONE_ARTICLE',
                    'Une catégorie'=>'ONE_CATEGORY',
                    'Les 5 derniers articles' => 'FIVE_ARTICLES',
                    'Un collège' => 'ONE_COLLEGE',
                    'Tous les collèges' => 'ALL_COLLEGES',
                    'Autre type de contenu' => 'OTHER_CONTENT'
                ]),
            AssociationField::new('singleCollege', 'College')
                ->onlyOnForms()
                ->setHelp('Ne remplir que si le type de contenu pointe sur un collège'),
            AssociationField::new('category','Choix de la catégorie')
                ->setHelp('ne remplir que si le type de contenu pointe sur Catégorie'),
            IntegerField::new('priority', "Affichage"),
            BooleanField::new('isActiv', 'Visible'),
            FormField::addPanel('Options de la section'),
            TextField::new('className', 'Class CSS')->hideOnIndex(),
            BooleanField::new('fluid', 'Adapter la section sur toute la largeur de la page')
                ->hideOnIndex(),
            DateField::new('createAt', 'Créer le')
                ->hideOnForm(),
            DateField::new('updateAt', 'Modifier le')
                ->hideOnForm(),
        ];
    }

}
