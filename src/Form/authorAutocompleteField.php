<?php
// src/Form/Field/AuthorAutocompleteField.php

namespace App\Form\Field;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class AuthorAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class'             => Author::class,
            'placeholder'       => 'Rechercher un auteur...',
            'choice_label'      => function (Author $author): string {
                return $author->getFirstName() . ' ' . $author->getLastName();
            },
            'searchable_fields' => ['firstName', 'lastName'],
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
