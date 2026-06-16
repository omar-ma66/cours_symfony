<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[route('/livres', name: 'app_book_index')]
    public function index(): Response
    {

        $books = [
            ['title' => 'Dune', 'author' => 'Frank Herbert', 'dispo' => 'oui'],
            ['title' => '1984', 'author' => 'George Orwell', 'dispo' => 'non'],
            ['title' => 'Fondation', 'author' => 'Isaac Asimov', 'dispo' => 'oui'],
            ['title' => 'Le Petit Prince', 'author' => 'Antoine de Saint-Exupéry', 'dispo' => 'non'],
            ['title' => 'Harry Potter', 'author' => 'JK Rowling', 'dispo' => 'oui'],
        ];

        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
            'books' => $books,
        ]);
    }
    // ---------------------------------------------------------------------------------------

    #[route('/livres/{id}', name: 'app_book_show', requirements: ['id' => '\d{1,4}'], methods: ['GET', 'POST'])]
    public function show(int $id): Response
    {
        $book = [
            'id' => $id,
            'title' => 'Livre n° ' . $id,
            'author' => 'Auteur inconnu',
            'description' => 'Un livre passionnant de notre bibliotheque.'
        ];

        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
    // ---------------------------------------------------------------------------------------

    #[route('/livres/page/{page}', name: 'app_book_list', requirements: ['page' => '\d+'], methods: ['GET'])]
    public function list(int $page = 1): Response
    {

        return new Response("Page numéro $page de la liste des livres ");
    }
    // ---------------------------------------------------------------------------------------

    #[route('/auteur/{name}', name: 'app_author_show')]
    public function showAuteur(string $name): Response
    {

        return new Response("Page de l' auteur $name ");
    }
    // ---------------------------------------------------------------------------------------

    #[route('categorie/{categorie}/livre/{id}', name: 'app_book_by_category')]
    public function showByCategory(string $categorie, int $id): Response
    {

        return new Response("vous avez choisi la catégorie [ $categorie ] numero de livre [$id]");
    }
    // ---------------------------------------------------------------------------------------


}
