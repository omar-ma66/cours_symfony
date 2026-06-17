<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
// ---------------------------------------------------------------------------------------------------------

    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
// ---------------------------------------------------------------------------------------------------------
    #[route('/auteur/delete/{id}',name:'app_author_delete',requirements:['id'=>'\d+'],methods:['GET'])]
    public function delete(int $id ,EntityManagerInterface $em ,Author $author):Response
    {
        $em->remove($author);
        $em->flush();
            return new Response("l'auteur $id est suprimer ");
    }
// ---------------------------------------------------------------------------------------------------------

#[route('/author/all')]
public function all(AuthorRepository $authorRepository):Response
{
   $data =     $authorRepository->getAuth();
   dd($data);
   return new Response($data);
}

}
