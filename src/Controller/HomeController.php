<?php

namespace App\Controller;

use  App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $utilisateur = $this->getUser();

/** @var User $utilisateur */
        dd($utilisateur->getFirstName());
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'name'=>$utilisateur
        ]);
    }

    #[Route('/about' ,name:'app_about')]
       public function about(): Response
    {
        return $this->render('home/about.html.twig', [
            'app_name' => 'Bibliotech',
        ]);
    }
}
