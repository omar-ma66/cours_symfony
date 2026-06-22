<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class BookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Livre')
            ->setEntityLabelInPlural('Livres')
            ->setDefaultSort(['title' => 'ASC'])
            ->setSearchFields(['title', 'isbn', 'author.firstName', 'author.lastName']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('title', 'Titre');
        yield TextareaField::new('description', 'Description')
            ->hideOnIndex();    // Trop long pour la liste
        yield TextField::new('isbn', 'ISBN')
            ->hideOnIndex();
        yield IntegerField::new('stock', 'Stock');
        yield AssociationField::new('author', 'Auteur');
        yield AssociationField::new('categories', 'Catégories');
    }
}