<?php

namespace App\Controller\Admin;

use App\Entity\Borrowing;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class BorrowingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Borrowing::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Emprunt')
            ->setEntityLabelInPlural('Emprunts')
            ->setDefaultSort(['borrowedAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        // Désactive la création et la modification depuis le back-office
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield AssociationField::new('user', 'Utilisateur');
        yield AssociationField::new('book', 'Livre');
        yield DateTimeField::new('borrowedAt', 'Emprunté le')
            ->setFormat('dd/MM/yyyy HH:mm');
        yield DateTimeField::new('returnedAt', 'Rendu le')
            ->setFormat('dd/MM/yyyy HH:mm');
    }
}