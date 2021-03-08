<?php

namespace App\Controller\Admin;

use App\Entity\Webapp\Message;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MessageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Message::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', ''),
            TextField::new('author', 'Expéditeur'),
            TextField::new('subject', 'Sujet'),
            CollectionField::new('Recipient', 'Pour'),
            DateField::new('CreateAt', 'Envoyé le')
        ];
    }

}
