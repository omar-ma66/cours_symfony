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
    public function index(): Response
    {
    
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
          
        ]);
    }

    #[Route('/about' ,name:'app_about')]
       public function about(): Response
    {
        return $this->render('home/about.html.twig', [
            'app_name' => 'Bibliotech',
        ]);
    }

    #[route('/stimulus',name:'app_stimulus_test')]
    public function testStimulus()
    {
        return $this->render('monTestStimulus/test.html.twig');
    }
}
