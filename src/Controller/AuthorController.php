<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/auteur')]
final class AuthorController extends AbstractController
{
    #-----------------------------------------------------------------------------------------------------------------------------
    #[route('/all',name:'app_auteur_all')]
    public function allAteur(AuthorRepository $authorRepository)
    {
      $all =  $authorRepository->findAll();

        dd( count($all));

    }

    #-----------------------------------------------------------------------------------------------------------------------------
    #[Route(name: 'app_author_index', methods: ['GET'])]
    public function index(AuthorRepository $authorRepository): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }
    #-----------------------------------------------------------------------------------------------------------------------------
    #-----------------------------------------------------------------------------------------------------------------------------
    #[Route('/nouveau', name: 'app_author_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();
                    $this->addFlash('succes',"la creation de l' auteur "." ".$author->getFirstName()." ".$author->getLastName()." est un succes");

            return $this->redirectToRoute('app_author_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('author/new.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }
    #-----------------------------------------------------------------------------------------------------------------------------
    #-----------------------------------------------------------------------------------------------------------------------------
    #[Route('/{id}', name: 'app_author_show', methods: ['GET'])]
    public function show(Author $author): Response
    {
        return $this->render('author/show.html.twig', [
            'author' => $author,
        ]);
    }
    #-----------------------------------------------------------------------------------------------------------------------------
    #-----------------------------------------------------------------------------------------------------------------------------
    #[Route('/{id}/modifier', name: 'app_author_edit', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_ADMIN')]
    public function edit(Request $request, Author $author, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
                    $this->addFlash('succes',"la mise a jour de l' auteur "." ".$author->getFirstName()." ".$author->getLastName()." est un succes");
            return $this->redirectToRoute('app_author_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('author/edit.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }
    #-----------------------------------------------------------------------------------------------------------------------------
    #-----------------------------------------------------------------------------------------------------------------------------
    #[Route('/{id}', name: 'app_author_delete', methods: ['POST'])]
    #[isGranted('ROLE_ADMIN')]
    public function delete(Request $request, Author $author, EntityManagerInterface $entityManager): Response
    {
         if( count($author->getBooks()->toArray()) == 0) //  ou $author->getBooks()->count() == 0
          {  
        if ($this->isCsrfTokenValid('delete'.$author->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($author);
            $entityManager->flush();
           $this->addFlash('succes',"la supprétion l' auteur "." ".$author->getFirstName()." ".$author->getLastName()." est un succes");

        }
          }
          else
            {
           $this->addFlash('error',"la supprétion l' auteur "." ".$author->getFirstName()." ".$author->getLastName()." n'est pas possible");

            }

        return $this->redirectToRoute('app_author_index', [], Response::HTTP_SEE_OTHER);
    }
}
    #-----------------------------------------------------------------------------------------------------------------------------
    #-----------------------------------------------------------------------------------------------------------------------------