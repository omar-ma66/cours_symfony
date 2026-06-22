<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Borrowing;
use App\Repository\BorrowingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/emprunt')]
#[IsGranted('ROLE_USER')]   // ← Toutes les routes de ce contrôleur requièrent une connexion
class BorrowingController extends AbstractController
{
    #[Route('/mes-emprunts', name: 'app_borrowing_my')]
    public function myBorrowings(BorrowingRepository $borrowingRepository): Response
    {
        $borrowings = $borrowingRepository->findActiveByUser($this->getUser());

        return $this->render('borrowing/my_borrowings.html.twig', [
            'borrowings' => $borrowings,
        ]);
    }

    #[Route('/emprunter/{id}', name: 'app_borrowing_borrow', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function borrow(int $id, Book $book, Request $request, BorrowingRepository $borrowingRepository, EntityManagerInterface $em): Response
    {
        if (!$book) {
            throw $this->createNotFoundException('Livre introuvable.');
        }

        // Vérification CSRF
        if (!$this->isCsrfTokenValid('borrow-' . $id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        // Vérifier que le stock est disponible
        if ($book->getStock() <= 0) {
            $this->addFlash('error', '😕 Ce livre n\'est plus disponible.');
            return $this->redirectToRoute('app_book_show', ['id' => $id]);
        }

        // Vérifier que l'utilisateur n'a pas déjà ce livre en cours d'emprunt
        $existing = $borrowingRepository->findOneActiveByUserAndBook($this->getUser(), $book);
        if ($existing) {
            $this->addFlash('error', '📚 Tu as déjà ce livre en cours d\'emprunt.');
            return $this->redirectToRoute('app_book_show', ['id' => $id]);
        }

        // Créer l'emprunt
        $borrowing = new Borrowing();
        $borrowing->setUser($this->getUser());
        $borrowing->setBook($book);
        $borrowing->setBorrowedAt(new \DateTimeImmutable());

        // Décrémenter le stock
        $book->setStock($book->getStock() - 1);

        $em->persist($borrowing);
        $em->flush();

        $this->addFlash('success', '✅ Tu as emprunté « ' . $book->getTitle() . ' » avec succès !');

        return $this->redirectToRoute('app_book_show', ['id' => $id]);
    }

    #[Route('/rendre/{id}', name: 'app_borrowing_return', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function return(int $id, Borrowing $borrowing, Request $request, BorrowingRepository $borrowingRepository, EntityManagerInterface $em): Response
    {

        if (!$borrowing) {
            throw $this->createNotFoundException('Emprunt introuvable.');
        }

        // Vérification CSRF
        if (!$this->isCsrfTokenValid('return-' . $id, $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }

        // Contrôle de propriété : l'utilisateur ne peut rendre que SES emprunts
        if ($borrowing->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Tu ne peux pas rendre un emprunt qui ne t\'appartient pas.');
        }

        // Vérifier que l'emprunt n'a pas déjà été rendu
        if ($borrowing->getReturnedAt() !== null) {
            $this->addFlash('error', 'Ce livre a déjà été rendu.');
            return $this->redirectToRoute('app_borrowing_my');
        }

        // Marquer comme rendu
        $borrowing->setReturnedAt(new \DateTimeImmutable());

        // Remettre le stock
        $borrowing->getBook()->setStock($borrowing->getBook()->getStock() + 1);

        $em->flush();

        $this->addFlash('success', '📬 Tu as rendu « ' . $borrowing->getBook()->getTitle() . ' ». Merci !');

        return $this->redirectToRoute('app_borrowing_my');
    }


    #[route('/livres/all',name:'app_livres_all')]
    public function rendreAll(BorrowingRepository $borrowingRepository)
    {
            $allBooks  = $borrowingRepository->findAllEmprunt($this->getUser());
            
          return   $this->render("Borrowing/all_book_emprunt.html.twig",["allbooks"=>$allBooks]);
    }

    #[route('/livres/all/user',name:'app_livres_all_user')]
    #[isGranted('ROLE_ADMIN')]
    public function indexAll(BorrowingRepository $borrowingRepository)
    {
       $all =         $borrowingRepository->findAllByUser();
       return $this->render('Borrowing/all_user_emprunt.html.twig',['all'=>$all]);
    }
}