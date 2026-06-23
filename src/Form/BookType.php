<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
 use App\Entity\Category;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\Field\AuthorAutocompleteField;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,
            [
                'label'=>'Titre',
                'attr'=>['placeholder'=>'Ex: Dune'],
            ])
            ->add('description',TextareaType::class,
            [
                'label'=>'Description',
                'required'=>false,
                'attr'=>
                    [
                        'rows'=>5,
                        'cols'=>30,
                        'placeholder'=>'Resume du livre...',
                    ]
            ])
            ->add('stock',IntegerType::class,[
                'label'=>'Stock',
                'attr'=>['min'=> 0],
            ])
            ->add('isbn',TextType::class,[
                'label'=>'ISBN',
                'required'=>false,
                'attr'=>['maxlength'=>13]
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => function(Author $author):string
                {
                    return $author->getFirstName().' '.$author->getLastName();
                }
            ])

            //    ->add('author', AuthorAutocompleteField::class, [
            //     'class' => Author::class,
            // ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label'   => 'name',
                'multiple'       => true,
                'label'          => 'Catégories',
                'required'       => false,   
                'expanded'       => true
            ])
            ->add('Envoyer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
