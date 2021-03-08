<?php

namespace App\Controller\Admin;

use App\Entity\Webapp\Support;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SupportCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Support::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Type de support'),
            ImageField::new('imageFile', 'image')
                ->setFormType(VichImageType::class),
        ];
    }

}
